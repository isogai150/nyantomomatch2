@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment/settlement.css') }}">
@endsection

@section('content')
<div class="main-content">
  {{-- ======= ページ上部 ======= --}}
  <div class="main-top">
    <p>決済システム</p>
    <a href="{{ route('payment.cart', $post->id) }}">＜ 戻る</a>
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

      {{-- actionはStripeのIntent生成ルートに変更予定 --}}
      <form action="#" method="POST" class="payment-form">
        @csrf

        <div class="form-group">
          <label for="name">カード名義（漢字）</label>
          <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label for="name_kana">カード名義（カナ）</label>
          <input type="text" id="name_kana" name="name_kana" required>
        </div>

        <div class="form-group">
          <label for="postal_code">郵便番号</label>
          <input type="text" id="postal_code" name="postal_code" placeholder="例: 123-4567" required>
        </div>

        <div class="form-group">
          <label for="address">住所</label>
          <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
        </div>

        <div class="form-group">
          <label for="card_number">カード番号</label>
          <input type="text" id="card_number" name="card_number" placeholder="例: 4242 4242 4242 4242" required>
        </div>

        <div class="form-row">
          <div class="form-group half">
            <label for="expiry">有効期限</label>
            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
          </div>
          <div class="form-group half">
            <label for="cvc">CVC</label>
            <input type="text" id="cvc" name="cvc" placeholder="123" required>
          </div>
        </div>

        {{-- ▼ Stripe Elements 挿入ポイント --}}
        <div id="card-element" class="stripe-card-element"></div>
        {{-- ▲ Stripe Elements 挿入ポイント --}}

        <button type="submit" class="payment-btn">支払う</button>
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
{{-- Stripe.js導入予定 --}}
{{-- <script src="https://js.stripe.com/v3/"></script> --}}
<script src="{{ asset('js/payment/settlement.js') }}"></script>
@endsection
