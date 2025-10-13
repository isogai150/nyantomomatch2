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

    //DM詳細表示
public function show($dm)
{
    // Pairテーブルから対象のDMを取得
    // with() を使って関連データ（userA, userB, post.images）もまとめて取得
    // → N+1問題（毎回SQLを発行する非効率な処理）を防ぐ
    // N+1とは親レコード１に対して複数の子レコードを一度で取得する
    $dm = Pair::with(['userA', 'userB', 'post.images'])->findOrFail($dm);

    // 三項演算子を使って、変数 $partner に「相手のユーザー情報」を格納
    // 現在ログイン中のユーザーIDが userA_id と一致するかどうかを判定
    // 一致する場合 → 相手は userB
    // 一致しない場合 → 相手は userA
    $partner = $dm->userA->id === auth()->id() ? $dm->userB : $dm->userA;

    // このDMに紐づくメッセージ一覧を取得
    // → Pairモデルで定義した「messages()」リレーションを使用
    // orderBy()：取得するデータの並び順を指定
    // created_at（作成日時）を「asc（昇順＝古い順）」で並び替え
    $messages = $dm->messages()
        ->orderBy('created_at', 'asc')
        ->get();

    // 投稿データを取得（Pair に紐づく Post モデル）
    // このあとBladeで $post->images などを使用できる
    $post = $dm->post;

    // Bladeビューを返す
    // 第1引数：表示するテンプレート（dm/detail.blade.php）
    // 第2引数：ビューに渡すデータを配列で渡す（compactは変数名をキーにして連想配列化）
    // compact('dm', 'partner', 'post', 'messages')
    // は ['dm' => $dm, 'partner' => $partner, ...] と同義
    return view('dm.detail', compact('dm', 'partner', 'post', 'messages'));
}



// Ajaxでメッセージを取得（3秒ごとに呼び出し）
public function fetch($dm)
{
    // Messageテーブルから「pair_id（＝DMルームID）」が一致するメッセージを取得
    // where（第一引数が対象のカラム, 第二引数が取得するid）
    // orderBy() で古い順（昇順）に並び替える
    $messages = Message::where('pair_id', $dm)
        ->orderBy('created_at', 'asc')
        ->get()
        // map()：コレクションの各要素（＝1件のメッセージ）を整形して新しい配列として返す
        ->map(function ($msg) {
            return [
                // 送信者のユーザーID
                'user_id' => $msg->user_id,
                // 本文（e()でHTMLエスケープして安全に表示）
                'content' => e($msg->content),
                // 作成日時を「Y/m/d H:i」形式に整形
                'created_at' => $msg->created_at->format('Y/m/d H:i'),
            ];
        });

    // JSON形式で返す（JavaScript側で res.messages から利用できる）
    return response()->json(['messages' => $messages]);
}

// Ajaxでメッセージを送信
public function send(MessageSendRequest $request, $dm)
{
    // 新規メッセージを作成してDBに登録
    // pair_id（DMルームID）と user_id（送信者）を紐づける
    $message = Message::create([
        'pair_id' => $dm,
        'user_id' => auth()->id(),
        'content' => $request->message,
    ]);

    // フロント側（JavaScript）で扱いやすい形でJSONを返す
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

        // ログインユーザーが関わるペアを取得（投稿情報も含める）
        $pairs = Pair::with(['userA', 'userB', 'post'])
        ->where('userA_id', $userId)
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

            // 検索フィルタリング（あいまい検索）
            if ($searchQuery) {
                $searchLower = mb_strtolower($searchQuery);
                $userName = mb_strtolower($otherUser->name);

                // ユーザー名での検索
                $userNameMatch = mb_strpos($userName, $searchLower) !== false;

                // メッセージ内容での検索
                $messageMatch = false;
                if ($messages->isNotEmpty()) {
                    foreach ($messages as $message) {
                        if (mb_strpos(mb_strtolower($message->content), $searchLower) !== false) {
                            $messageMatch = true;
                            break;
                        }
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
                'post' => $pair->post,
                'last_message' => $lastMessageContent,
                'time_ago' => $timeAgo,
                'message_count' => $messageCount,
                'last_message_time' => $lastMessageTime,
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

        // 既存のペアを検索（userA_id と userB_id の順序を考慮）
        $pair = Pair::where(function ($query) use ($userId, $postOwnerId) {
            $query->where('userA_id', $userId)
                ->where('userB_id', $postOwnerId);
        })->orWhere(function ($query) use ($userId, $postOwnerId) {
            $query->where('userA_id', $postOwnerId)
                ->where('userB_id', $userId);
        })->where('post_id', $postId)->first();

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
