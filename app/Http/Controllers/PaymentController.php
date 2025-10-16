<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Transfer;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // カート情報表示機能
    public function showcart($postId)
    {
        $post = Post::findOrFail($postId);
        return view('payment.cart', compact('post'));
    }

    // 決済情報入力ページ表示機能
    public function showForm($postId)
    {
        $post = Post::findOrFail($postId);
        return view('payment.settlement', compact('post'));
    }

    // 決済実行機能（3Dセキュア対応・PaymentIntents API使用）
    public function processPayment(Request $request)
    {
        // dd($request->all());
        // Stripe秘密キー設定（.envから直接取得）
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // 対象の投稿
        $post = Post::findOrFail($request->post_id);

        // サーバー側で金額を再計算
        $baseCost = $post->cost;
        $tax = round($baseCost * 0.1);      // 消費税10%
        $fee = round($baseCost * 0.036);    // Stripe手数料3.6%
        $totalAmount = (int) ($baseCost + $tax + $fee);
        // dd($totalAmount);

        try {
            // Stripe決済実行（PaymentIntentの作成）
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,      // 金額（単位: 円）
                'currency' => 'jpy',
                'description' => '譲渡費用の支払い',
                'payment_method_types' => ['card'], // カード決済
                'metadata' => [
                    'post_id' => $post->id,
                    'user_email' => $request->email,
                    'user_id' => Auth::id(), // 支払いユーザーIDを保持
                ],
            ]);

            // クライアントで3Dセキュア認証を行うためのclient_secretを返す
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id, // 成功時にIntent IDも返す
            ]);

        } catch (\Exception $e) {
            // 決済失敗時
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 決済完了ページ表示機能（譲渡成立データ登録＋投稿ステータス更新）
    public function success(Request $request)
    {
        // Stripe秘密キー設定（.envから直接取得）
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // クエリパラメータからPaymentIntent IDを取得
        $paymentIntentId = $request->input('payment_intent_id');

        if (!$paymentIntentId) {
            return view('payment.success')->with('message', '支払い情報が見つかりません。');
        }

        try {
            // StripeからPaymentIntent情報を取得
            $intent = PaymentIntent::retrieve($paymentIntentId);
            $meta = $intent->metadata;

            // 対象の投稿を取得
            $post = Post::findOrFail($meta->post_id);

            // すでに譲渡済みでない場合のみ登録
            if ($post->status !== 2) {
                // transfersテーブルに登録
                Transfer::create([
                    'userA_id' => $post->user_id, // 投稿者（譲渡する側）
                    'userB_id' => $meta->user_id, // 支払者（譲渡を受ける側）
                    'post_id' => $post->id,
                    'confirmed_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // 投稿ステータスを「譲渡済み（2）」に更新
                $post->update(['status' => 2]);
            }

            // 完了ページを表示
            return view('payment.success', [
                'message' => '譲渡が成立しました。',
                'post' => $post,
            ]);

        } catch (\Exception $e) {
            // 取得や登録に失敗した場合
            return view('payment.success', ['message' => '支払い情報の確認に失敗しました。']);
        }
    }

    // キャンセルページ表示機能
    public function cancel()
    {
        return view('payment.cancel');
    }
}
