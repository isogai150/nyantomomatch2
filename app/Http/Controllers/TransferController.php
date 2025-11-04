<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pair;
use App\Models\Transfer;
use App\Models\TransferDocument;

class TransferController extends Controller
{
    /**
     * 資料を渡す（投稿者のみ）
     */
    public function send($dm)
    {
        $pair = Pair::with('post')->findOrFail($dm);

        if ($pair->post->user_id !== Auth::id()) {
            return back()->with('error', 'この操作を行う権限がありません。');
        }

        $pair->update(['transfer_status' => 'sent']);
        return back()->with('success', '譲渡資料を送信しました。');
    }

    /**
     * 資料確認ページ表示
     */
    public function showDocument($dm)
    {
        $pair = Pair::with('post')->findOrFail($dm);

        if ($pair->post->user_id == Auth::id()) {
            return back()->with('error', '資料閲覧権限がありません。');
        }

        return view('document.index', compact('pair'));
    }

    /**
     * 契約書提出（里親希望者のみ）
     */
    public function submit(Request $request, $dm)
    {
        $pair = Pair::with('post')->findOrFail($dm);

        if ($pair->post->user_id == Auth::id()) {
            return back()->with('error', '契約提出権限がありません。');
        }

        $request->validate([
            'buyer_signature' => 'required|string|max:50',
            'signed_date' => 'required|date',
        ]);

        TransferDocument::updateOrCreate(
            ['pair_id' => $pair->id],
            [
                'buyer_signature' => $request->buyer_signature,
                'signed_date' => $request->signed_date,
            ]
        );

        $pair->update(['transfer_status' => 'submitted']);

        return back()->with('success', '契約を提出しました。相手の合意をお待ちください。');
    }


    /**
     * 双方合意（両者押下で成立）
     */
    public function agree($dm)
    {
        $pair = Pair::findOrFail($dm);
        $currentUser = Auth::id();

        if (!in_array($currentUser, [$pair->userA_id, $pair->userB_id])) {
            return back()->with('error', '権限がありません。');
        }

        if ($pair->transfer_status === 'submitted') {
            // 最初の合意
            $pair->update([
                'transfer_status' => 'agreed_wait',
                'agreed_user_id' => $currentUser
            ]);
            return back()->with('success', 'あなたの合意を記録しました。相手の合意をお待ちください。');
        }

        if ($pair->transfer_status === 'agreed_wait') {
            // 既に合意済みの場合
            if ($pair->agreed_user_id === $currentUser) {
                return back()->with('info', '既に合意済みです。相手の合意をお待ちください。');
            }

            // 2人目の合意で完了
            $pair->update([
                'transfer_status' => 'agreed',
                'agreed_user_id' => null
            ]);
            return back()->with('success', '双方合意が完了しました！次は決済へ進みます。');
        }

        return back();
    }
}
