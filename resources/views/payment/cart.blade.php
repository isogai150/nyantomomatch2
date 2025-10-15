@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment/cart.css') }}">
@endsection

@section('content')
<div class="main-content">
  <div class="main-top">
    <p>決済システム</p>
    <a href="{{ route('posts.index') }}">topページ</a>
  </div>
  <div class="main-center">
    <div class="center-top">
      <h2>安全で簡単な決済システム</h2>
      <p>stripeを使用した信頼性の高い決済処理</p>
    </div>
    <div class="center-items">
      <div class="item-left">
        <h3>支払い内容</h3>
        @if($post)
        <div class="cart-inf">
          <p>譲渡費用（税抜き＋手数料抜き）<span>{{ $post->cost_class }}</span></p>
          <form action="{{ route('payment.form', $post->id) }}" method="GET">
            @csrf
            <input type="hidden" name="post" value="{{ $post->id }}">
            <button type="submit" class="payment-btn">購入画面へ</button>
          </form>
        </div>
        @else
        <p>購入情報がありません</p>
        @endif
      </div>
      <div class="item-right">
        <div class="security-top">
          <h3>セキュリティ</h3>
          <p>安全な決済処理を保証</p>
        </div>
        <div class="security-items">
          <ul>
            <li>SSL暗号化通信</li>
            <li>PCI DSS準拠</li>
            <li>3Dセキュア対応</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection