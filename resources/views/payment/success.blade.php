@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment/success.css') }}">
@endsection

@section('content')
<div class="payment-success-container">
  <div class="success-content">
    <h1>🎉 決済が完了しました！</h1>
    <p class="message">
      ご支払いありがとうございました。<br>
      譲渡手続きの詳細については、譲渡元の方または管理者から<br class="sp-br">
      ご連絡をお待ちください。
    </p>

    @if(session('status'))
      <p class="alert-success">{{ session('status') }}</p>
    @endif

    @isset($post)
    <div class="payment-summary">
      <h2>お支払い内容</h2>
      <ul>
        <li>譲渡費用：<span>{{ number_format($post->cost) }}円</span></li>
        <li>消費税：<span>{{ number_format(round($post->cost * 0.1)) }}円</span></li>
        <li>Stripe手数料：<span>{{ number_format(round($post->cost * 0.036)) }}円</span></li>
      </ul>
      <div class="total">
        <p>合計金額</p>
        <p class="amount">{{ number_format(round($post->cost * 1.136)) }}円</p>
      </div>
    </div>
    @endisset

    <div class="actions">
      <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
    </div>

    <div class="stripe-note">
      <p>※ この決済は <strong>Stripe</strong> により安全に処理されました。</p>
    </div>
  </div>
</div>
@endsection
