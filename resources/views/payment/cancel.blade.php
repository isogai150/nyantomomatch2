@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/payment/cancel.css') }}">
@endsection

@section('content')
<div class="cancel-container">
  <div class="cancel-content">
    <h1>⚠️ 決済がキャンセルされました</h1>
    <p class="message">
      決済が完了しませんでした。<br><br class="br-sp">
      操作を中断した場合、<br class="br-sp">料金は発生していません。<br><br class="br-sp">
      再度お手続きいただくか、<br class="br-sp">トップページへお戻りください。
    </p>

    <div class="actions">
      <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
    </div>

    <div class="note">
      <p>※ この決済は <strong>Stripe</strong> により<br class="br-sp">安全に処理されています。</p>
    </div>
  </div>
</div>
@endsection
