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
