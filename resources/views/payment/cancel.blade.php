@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment/cancel.css') }}">
@endsection

@section('content')
<div class="cancel-container">
  <div class="cancel-content">
    <h1>⚠️ 決済がキャンセルされました</h1>
    <p class="message">
      決済が完了しませんでした。<br>
      操作を中断した場合、料金は発生していません。<br>
      再度お手続きいただくか、トップページへお戻りください。
    </p>

    <div class="actions">
      <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
    </div>

    <div class="note">
      <p>※ この決済は <strong>Stripe</strong> により安全に処理されています。</p>
    </div>
  </div>
</div>
@endsection
