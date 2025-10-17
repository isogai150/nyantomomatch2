<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 決済実行処理（Stripeトークン受取 → Stripe APIへ）
Route::post('/checkout/process', [PaymentController::class, 'processPayment'])->name('payment.process');
