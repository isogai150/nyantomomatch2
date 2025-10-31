@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/errors.css') }}">
@endsection

@section('content')
<div class="error-wrapper">
    <div class="error-code">503</div>
    <div class="cat-icon">😴🐾</div>
    <p class="error-message">
        ただいまメンテナンス中です。<br>
        サーバーがひと休みしていますので、<br>
        少し経ってからアクセスしてください。
    </p>
    <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
</div>
@endsection
