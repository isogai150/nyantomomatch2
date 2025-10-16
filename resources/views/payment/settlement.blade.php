@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment/settlement.css') }}">
@endsection

@section('content')
<div class="main-content">
  {{-- ======= ページ上部 ======= --}}
  <div class="main-top">
    <p>決済システム</p>
    <a href="{{ route('payment.cart', $post->id) }}">＜　戻る</a>
  </div>

  {{-- ======= 決済メイン領域 ======= --}}
  <div class="main-center payment">
    {{-- 左側：入力フォーム --}}
    @auth
    <div class="payment__form-area">
      <div class="form-header">
        <h2>お支払い情報</h2>
        <p>安全な決済処理のため、正確な情報を入力してください。</p>
      </div>

      {{-- Stripeにトークン送信予定 --}}
      {{-- formのactionは直接送信されずJS経由で処理される --}}
      <form id="payment-form" class="payment-form">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <div class="form-group">
          <label for="name">お名前</label>
          <input type="text" id="name" name="name" placeholder="例: 山田 太郎">
        </div>

        {{-- 住所などの情報はStripeダッシュボード上の記録や領収書送信時に利用可能 --}}
        <div class="form-group">
          <label for="postal_code">郵便番号</label>
          <input type="text" id="postal_code" name="postal_code" placeholder="例: 123-4567">
        </div>

        <div class="form-group">
          <label for="address">住所</label>
          <input type="text" id="address" name="address" placeholder="例: 東京都渋谷区...">
        </div>

        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>

        {{-- ▼ Stripe Elements 挿入ポイント --}}
        {{-- カード番号・有効期限・CVC などのフォームをStripeが自動生成する --}}
        <div class="form-group">
          <label>クレジットカード情報</label>
          <div id="card-element" class="stripe-card-element"></div>
          <div id="card-errors" role="alert" class="error-message"></div>
        </div>
        {{-- ▲ Stripe Elements 挿入ポイント --}}

        <button type="submit" class="payment-btn">支払う</button>
        <a href="{{ route('payment.cancel') }}" class="cancel-btn">キャンセル</a>
      </form>
    </div>
    @else
    <p class="login-warning">このページを利用するにはログインが必要です。</p>
    @endauth

    {{-- 右側：支払い明細・セキュリティ情報 --}}
    <div class="payment__summary">
      <h2>支払い内容</h2>
      @if ($post)
      <ul class="summary-list">
        <li>譲渡費用 <span>{{ number_format($post->cost) }}円</span></li>
        <li>消費税（10%）<span>{{ number_format(round($post->cost * 0.1)) }}円</span></li>
        <li>Stripe手数料（3.6%）<span>{{ number_format(round($post->cost * 0.036)) }}円</span></li>
      </ul>

      <div class="total-box">
        <p>合計金額</p>
        <p class="total-amount">{{ number_format(round($post->cost * 1.136)) }}円</p>
      </div>
      @endif

      <div class="security-section">
        <ul>
          <li>この決済は <strong>Stripe</strong> により安全に処理されます。</li>
          <li>カード情報は暗号化され、当社のサーバーには保存されません。</li>
          <li>全通信はSSLで保護されています。</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  // Laravel設定ファイル(config/stripe.php)から公開キーを渡す
  window.stripePublicKey = "{{ config('stripe.stripe_public_key') }}";
</script>
{{-- Stripe.js導入用CDN（カード情報をLaravelを通さないでStripeに送るため）影響範囲を最小限にするために使うbladeのみに記載する --}}
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/payment/settlement.js') }}"></script>
@endsection
