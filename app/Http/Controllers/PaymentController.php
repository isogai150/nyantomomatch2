<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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
        // Stripe秘密キー設定（.envからconfig/stripe.php 経由）
        Stripe::setApiKey(config('stripe.stripe_secret_key'));

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
                ],
            ]);

            // クライアントで3Dセキュア認証を行うためのclient_secretを返す
            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
            ]);

        } catch (\Exception $e) {
            // 決済失敗時
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 決済完了ページ表示機能
    public function success()
    {
        return view('payment.success');
    }

    // キャンセルページ表示機能
    public function cancel()
    {
        return view('payment.cancel');
    }
}
