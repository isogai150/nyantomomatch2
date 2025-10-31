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

        // 現在のユーザー
        $user = auth()->user();

        // ==============================
        // ブロック状態の確認
        // ==============================
        // 自分が相手をブロックしているか
        $isBlocking = $user->isBlocking($partner->id);

        // 相手にブロックされているか
        $isBlockedBy = $user->isBlockedBy($partner->id);

        // このDMに紐づくメッセージを古い順で取得
        $messages = $dm->messages()
            ->orderBy('created_at', 'asc')
            ->get();

        // 投稿データを取得（このDMがどの投稿に紐づくか）
        $post = $dm->post;

        // Bladeへデータを渡して画面表示
        return view('dm.detail', compact('dm', 'partner', 'post', 'messages', 'isBlocking', 'isBlockedBy'));
    }


    // Ajaxでメッセージを取得（3秒ごと）
public function fetch($dm)
{
    $dm = Pair::findOrFail($dm);

    $messages = $dm->messages()
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($msg) use ($dm) {
            return [
                'id' => $msg->id,
                'user_id' => $msg->user_id,
                'content' => $msg->content,
                'created_at' => $msg->created_at->format('Y年m月d日 H:i'),
                'pair_id' => $dm->id, // ← これを追加！
            ];
        });

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
                'created_at' => $message->created_at->format('Y年m月d日 H:i'),
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
                'updated_at' => $message->updated_at->format('Y年m月d日 H:i'),
            ]
        ]);
    }

    // // Ajaxでメッセージを削除（DELETE通信）
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

        // ログインユーザーが関わるペアを取得（投稿情報も含める）
        $pairs = Pair::with(['userA', 'userB', 'post'])
            ->where('userA_id', $userId)
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

            // メッセージがある場合
            if ($messages->isNotEmpty()) {
                $lastMessage = $messages->first();
                $lastMessageContent = $lastMessage->content;
                $timeAgo = $this->getTimeAgo($lastMessage->created_at);
                $lastMessageTime = $lastMessage->created_at;
                $messageCount = $messages->count();
            } else {
                // メッセージがない場合
                $lastMessageContent = 'まだメッセージがありません';
                $timeAgo = $this->getTimeAgo($pair->created_at);
                $lastMessageTime = $pair->created_at;
                $messageCount = 0;
            }

            // 検索処理（ユーザー名・メッセージ内容）
            if ($searchQuery) {
                $searchLower = mb_strtolower($searchQuery);
                $userName = mb_strtolower($otherUser->name);
                $userNameMatch = mb_strpos($userName, $searchLower) !== false;

                $messageMatch = false;
                if ($messages->isNotEmpty()) {
                    foreach ($messages as $message) {
                        if (mb_strpos(mb_strtolower($message->content), $searchLower) !== false) {
                            $messageMatch = true;
                            break;
                        }
                    }
                }

                if (!$userNameMatch && !$messageMatch) continue;
            }

            $conversationUsers[] = [
                'user' => $otherUser,
                'pair_id' => $pair->id,
                'post' => $pair->post,
                'last_message' => $lastMessageContent,
                'time_ago' => $timeAgo,
                'message_count' => $messageCount,
                'last_message_time' => $lastMessageTime,
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

    // DM作成（Pairを作成してDM詳細画面へ遷移）
    public function create(Request $request)
    {
        $userId = Auth::id(); // ログインユーザー（userA）
        $postId = $request->input('post'); // リクエストからpost_idを取得

        $post = Post::findOrFail($postId);
        $postOwnerId = $post->user_id; // 投稿者（userB）

        // 自分の投稿にはメッセージを送れないようにする
        if ($userId == $postOwnerId) {
            return redirect()->back()->with('error', '自分の投稿にメッセージは送れません');
        }

        // 既存のペアを検索※post_idも条件に含める（userA_id と userB_id の順序を考慮）
        $pair = Pair::where('post_id', $postId)
            ->where(function ($query) use ($userId, $postOwnerId) {
                $query->where(function ($q) use ($userId, $postOwnerId) {
                    $q->where('userA_id', $userId)
                        ->where('userB_id', $postOwnerId);
                })->orWhere(function ($q) use ($userId, $postOwnerId) {
                    $q->where('userA_id', $postOwnerId)
                        ->where('userB_id', $userId);
                });
            })->first();

        // ペアが存在しない場合は新規作成
        if (!$pair) {
            $pair = Pair::create([
                'userA_id' => $userId,
                'userB_id' => $postOwnerId,
                'post_id' => $postId,
            ]);
        }

        // DM詳細画面へリダイレクト
        return redirect()->route('dm.show', ['dm' => $pair->id]);
    }


    // DM（ペア）を論理削除
    public function delete($dm)
    {
        $userId = Auth::id();

        // 削除対象のペアを取得
        $pair = Pair::findOrFail($dm);

        // ログインユーザーがこのペアに関係しているか確認
        if ($pair->userA_id !== $userId && $pair->userB_id !== $userId) {
            return redirect()->route('dm.index')->with('error', 'このメッセージを削除する権限がありません');
        }

        // ペアを論理削除（deleted_atに現在時刻が入る）
        $pair->delete();

        return redirect()->route('dm.index')->with('success', 'メッセージを削除しました');
    }
}
