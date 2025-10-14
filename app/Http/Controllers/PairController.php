<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dm\DmSearchRequest;
use Illuminate\Http\Request;
use App\Models\Pair;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Post;
use App\Http\Requests\Dm\MessageSendRequest;

class PairController extends Controller
{
    // DM詳細表示（1対1チャット画面）
    public function show($dm)
    {
        // Pairテーブルから対象のDMを取得（関連データも一括で）
        // → N+1問題を防ぐために with() でリレーションも同時取得
        $dm = Pair::with(['userA', 'userB', 'post.images'])->findOrFail($dm);

        // 現在ログインしているユーザーと userA_id を比較して相手を特定
        $partner = $dm->userA->id === auth()->id() ? $dm->userB : $dm->userA;

        // このDMに紐づくメッセージを古い順で取得
        $messages = $dm->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        // 投稿データを取得（このDMがどの投稿に紐づくか）
        $post = $dm->post;

        // Bladeへデータを渡して画面表示
        return view('dm.detail', compact('dm', 'partner', 'post', 'messages'));
    }

    // Ajaxでメッセージを取得（3秒ごと）
    public function fetch($dm)
    {
        // pair_id が一致するメッセージを古い順に取得
        $messages = Message::where('pair_id', $dm)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id, // ← update/delete用にIDも追加
                    'user_id' => $msg->user_id,
                    'content' => e($msg->content),
                    'created_at' => $msg->created_at->format('Y/m/d H:i'),
                ];
            });

        // JSON形式で返す（JavaScript側で利用）
        return response()->json(['messages' => $messages]);
    }

    // Ajaxでメッセージ送信
    public function send(MessageSendRequest $request, $dm)
    {
        // 新規メッセージを登録
        $message = Message::create([
            'pair_id' => $dm,
            'user_id' => auth()->id(),
            'content' => $request->message,
        ]);

        // フロントで扱いやすい形に整形して返す
        return response()->json([
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'content' => e($message->content),
                'created_at' => $message->created_at->format('Y/m/d H:i'),
            ]
        ]);
    }

    // Ajaxでメッセージを編集（PUT通信）
    public function update(Request $request, Message $message)
    {
        // バリデーション：空文字禁止、最大1000文字
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // 自分以外のメッセージを編集しようとした場合は403エラー
        if ($message->user_id !== Auth::id()) {
            return response()->json(['error' => '権限がありません。'], 403);
        }

        // メッセージ本文を更新
        $message->content = $request->content;
        $message->save();

        // 更新後のメッセージをJSONで返す
        return response()->json([
            'message' => [
                'id' => $message->id,
                'content' => e($message->content),
                'updated_at' => $message->updated_at->format('Y/m/d H:i'),
            ]
        ]);
    }

    // Ajaxでメッセージを削除（DELETE通信）
    public function destroy(Message $message)
    {
        // 自分以外のメッセージを削除しようとした場合は403エラー
        if ($message->user_id !== Auth::id()) {
            return response()->json(['error' => '権限がありません。'], 403);
        }

        // 削除処理（物理削除）
        $message->delete();

        // フロント側で非表示にできるよう成功レスポンスを返す
        return response()->json(['success' => true]);
    }

    // DM一覧表示
    public function index(DmSearchRequest $request)
    {
        $userId = Auth::id();
        $searchQuery = $request->input('search');

        // ログインユーザーが関わるペアを取得
        $pairs = Pair::where('userA_id', $userId)
            ->orWhere('userB_id', $userId)
            ->get();

        $conversationUsers = [];

        foreach ($pairs as $pair) {
            // 相手のユーザーを特定
            $otherUserId = $pair->userA_id == $userId ? $pair->userB_id : $pair->userA_id;
            $otherUser = User::find($otherUserId);
            if (!$otherUser) continue;

            // メッセージ一覧を取得（新しい順）
            $messages = Message::where('pair_id', $pair->id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($messages->isEmpty()) continue;

            // 最後のメッセージを取得
            $lastMessage = $messages->first();
            $timeAgo = $this->getTimeAgo($lastMessage->created_at);

            // 検索処理（ユーザー名・メッセージ内容）
            if ($searchQuery) {
                $searchLower = mb_strtolower($searchQuery);
                $userName = mb_strtolower($otherUser->name);
                $userNameMatch = mb_strpos($userName, $searchLower) !== false;

                $messageMatch = false;
                foreach ($messages as $message) {
                    if (mb_strpos(mb_strtolower($message->content), $searchLower) !== false) {
                        $messageMatch = true;
                        break;
                    }
                }

                if (!$userNameMatch && !$messageMatch) continue;
            }

            $conversationUsers[] = [
                'user' => $otherUser,
                'pair_id' => $pair->id,
                'last_message' => $lastMessage->content,
                'time_ago' => $timeAgo,
                'message_count' => $messages->count(),
                'last_message_time' => $lastMessage->created_at,
            ];
        }

        // 最新メッセージ順にソート
        usort($conversationUsers, function ($a, $b) {
            return $b['last_message_time'] <=> $a['last_message_time'];
        });

        return view('dm.index', compact('conversationUsers'));
    }

    // 時間差を「◯分前」形式で整形する共通関数
    private function getTimeAgo($datetime)
    {
        $now = Carbon::now();
        $messageTime = Carbon::parse($datetime);

        $diffInMinutes = $now->diffInMinutes($messageTime);
        $diffInHours = $now->diffInHours($messageTime);
        $diffInDays = $now->diffInDays($messageTime);

        if ($diffInMinutes < 1) {
            return 'たった今';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . '分前';
        } elseif ($diffInHours < 24) {
            return $diffInHours . '時間前';
        } elseif ($diffInDays < 7) {
            return $diffInDays . '日前';
        } elseif ($diffInDays < 30) {
            return floor($diffInDays / 7) . '週間前';
        } elseif ($diffInDays < 365) {
            return floor($diffInDays / 30) . 'ヶ月前';
        } else {
            return floor($diffInDays / 365) . '年前';
        }
    }
}