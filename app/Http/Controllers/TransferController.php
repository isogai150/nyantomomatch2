<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pair;
use App\Models\Post;

class TransferController extends Controller
{
    /**
     * 資料を渡す（投稿者のみ実行可）
     */
    public function send($dm)
    {
        // 対象のペアを取得
        $pair = Pair::with('post')->findOrFail($dm);

        // 投稿者チェック（本人以外が送信しようとしたら拒否）
        if ($pair->post->user_id !== Auth::id()) {
            return back()->with('error', 'この操作を行う権限がありません。');
        }

        // 状態を sent に更新
        $pair->update(['transfer_status' => 'sent']);

        // 成功メッセージを返してDM詳細へ戻す
        return back()->with('success', '譲渡資料を送信しました。');
    }

    /**
     * 資料の確認ページ表示（里親希望者用）
     */
    public function showDocument($dm)
    {
        $pair = Pair::with('post')->findOrFail($dm);

        // 関連する投稿情報などをBladeに渡す
        return view('transfer.document', compact('pair'));
    }

    /**
     * 合意ボタン押下（双方が押したら agreed に遷移）
     */
    public function agree($dm)
    {
        $pair = Pair::findOrFail($dm);
        $user = Auth::id();

        // 現在の状態に応じて処理分岐
        if ($pair->transfer_status === 'sent' || $pair->transfer_status === 'agreed_wait') {
            // セッションキー生成（誰が押したかを記録）
            $sessionKey = "transfer_agreed_{$dm}_{$user}";
            session([$sessionKey => true]);

            // 相手側ユーザーIDを取得
            $otherId = $pair->userA_id == $user ? $pair->userB_id : $pair->userA_id;
            $otherKey = "transfer_agreed_{$dm}_{$otherId}";

            // 双方が押していたら合意成立
            if (session($otherKey)) {
                $pair->update(['transfer_status' => 'agreed']);
                return back()->with('success', '双方の合意が完了しました。決済へ進めます。');
            }

            // 片方だけ押した状態（相手待ち）
            $pair->update(['transfer_status' => 'agreed_wait']);
            return back()->with('success', '合意しました。相手の同意をお待ちください。');
        }

        return back()->with('error', '現在の状態では合意操作を行えません。');
    }
}
