<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pair;     // DMルーム（1対1チャットの親）
use App\Models\Message;  // メッセージテーブル
use App\Models\User;     // ユーザーテーブル

class PairController extends Controller
{
    /**
     * 🗨 DMの詳細画面（チャット画面）の表示
     * ルート例： /dm/{dm}
     */
    public function show($dm)
    {
        // Pairテーブルから指定されたDMルームを取得（存在しない場合は404エラー）
        $dm = Pair::with(['userA', 'userB'])->findOrFail($dm);

        // 🔹ログイン中のユーザーが userA か userB かを判定して「相手ユーザー」を特定
        $partner = $dm->userA->id === auth()->id() ? $dm->userB : $dm->userA;

        // 🔹このDMに紐づく全メッセージを取得（古い順に並べる）
        // → Pairモデルに「messages()」のリレーションが定義されている前提
        $messages = $dm->messages()
            ->orderBy('created_at', 'asc') // 第1引数にカラム名、第2引数に並び順
            ->get();

        // 🔹「dm.detail」ビューにデータを渡す
        // compact() は ['dm' => $dm, 'partner' => $partner, 'messages' => $messages] と同義
        return view('dm.detail', compact('dm', 'partner', 'messages'));
    }

    /**
     * 🔁 Ajaxでメッセージ一覧を取得（3秒ごとに呼び出される）
     * ルート例： /dm/{dm}/message/reception
     */
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

    /**
     * 💬 Ajaxでメッセージを送信する処理
     * ルート例： /dm/{dm}/message/create
     */
    public function send(Request $request, $dmId)
    {
        // 入力チェック（未入力や文字数制限のエラー防止）
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // 🔹メッセージを新規作成してDBに登録
        $message = Message::create([
            'dm_id' => $dmId,             // どのDMルームに紐づくか
            'user_id' => auth()->id(),    // 送信者ID（現在ログイン中のユーザー）
            'content' => $request->message, // 本文
        ]);

        // 🔹フロント（JavaScript側）が扱いやすい形で返す
        return response()->json([
            'message' => [
                'user_id' => $message->user_id,
                'content' => e($message->content),
                'created_at' => $message->created_at->format('Y/m/d H:i'),
            ]
        ]);
    }
}
