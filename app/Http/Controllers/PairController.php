<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dm\DmSearchRequest;
use Illuminate\Http\Request;
use App\Models\Pair;     // DMルーム（1対1チャットの親）
use App\Models\Message;  // メッセージテーブル
use App\Models\User;     // ユーザーテーブル
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Post;

class PairController extends Controller
{
    // DM詳細表示
    public function show($dm)
    {
        // Pairテーブルから指定されたDMルームを取得（存在しない場合は404エラー）
        $dm = Pair::with(['userA', 'userB'])->findOrFail($dm);

        // ログイン中のユーザーが userA か userB かを判定して「相手ユーザー」を特定
        $partner = $dm->userA->id === auth()->id() ? $dm->userB : $dm->userA;

        $post = $dm->post_id;

        // このDMに紐づく全メッセージを取得（古い順に並べる）
        // → Pairモデルに「messages()」のリレーションが定義されている前提
        $messages = $dm->messages()
            ->orderBy('created_at', 'asc') // 第1引数にカラム名、第2引数に並び順
            ->get();

        //「dm.detail」ビューにデータを渡す
        // compact() は ['dm' => $dm, 'partner' => $partner, 'messages' => $messages] と同義
        return view('dm.detail', compact('dm', 'partner', 'post', 'messages'));
    }

    // Ajaxでメッセージ一覧を取得（3秒ごとに呼び出される）
    public function fetch($dm)
    {
        // PairのID（＝dm_id）が一致するメッセージをすべて取得（古い順）
        $messages = Message::where('dm_id', $dm)
            ->orderBy('created_at', 'asc')
            ->get()
            // map()：取得したコレクションをフロント用に整形して返す
            ->map(function ($msg) {
                return [
                    'user_id' => $msg->user_id,
                    'content' => e($msg->content),
                    'created_at' => $msg->created_at->format('Y/m/d H:i'),
                ];
            });

        // JSON形式で返す（Ajaxで受け取れる）
        return response()->json(['messages' => $messages]);
    }

    // Ajaxでメッセージを送信する処理
    public function send(Request $request, $dmId)
    {
        // 入力チェック（未入力や文字数制限のエラー防止）
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // メッセージを新規作成してDBに登録
        $message = Message::create([
            'dm_id' => $dmId,             // どのDMルームに紐づくか
            'user_id' => auth()->id(),    // 送信者ID（現在ログイン中のユーザー）
            'content' => $request->message, // 本文
        ]);

        // フロント（JavaScript側）が扱いやすい形で返す
        return response()->json([
            'message' => [
                'user_id' => $message->user_id,
                'content' => e($message->content),
                'created_at' => $message->created_at->format('Y/m/d H:i'),
            ]
        ]);
    }

    //DM一覧表示
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
            // 相手のユーザーIDを特定
            $otherUserId = $pair->userA_id == $userId ? $pair->userB_id : $pair->userA_id;
            $otherUser = User::find($otherUserId);

            if (!$otherUser) continue;

            // このペアのメッセージを取得
            $messages = Message::where('pair_id', $pair->id)
                ->orderBy('created_at', 'desc')
                ->get();

            if ($messages->isEmpty()) continue;

            // 最後のメッセージ
            $lastMessage = $messages->first();

            // 時間差を計算
            $timeAgo = $this->getTimeAgo($lastMessage->created_at);

            // 検索フィルタリング（あいまい検索）
            if ($searchQuery) {
                $searchLower = mb_strtolower($searchQuery);
                $userName = mb_strtolower($otherUser->name);

                // ユーザー名での検索
                $userNameMatch = mb_strpos($userName, $searchLower) !== false;

                // メッセージ内容での検索
                $messageMatch = false;
                foreach ($messages as $message) {
                    if (mb_strpos(mb_strtolower($message->content), $searchLower) !== false) {
                        $messageMatch = true;
                        break;
                    }
                }

                // どちらにも一致しない場合はスキップ
                if (!$userNameMatch && !$messageMatch) {
                    continue;
                }
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

        // 最新のメッセージ順にソート
        usort($conversationUsers, function ($a, $b) {
            return $b['last_message_time'] <=> $a['last_message_time'];
        });

        return view('dm.index', compact('conversationUsers'));
    }

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
