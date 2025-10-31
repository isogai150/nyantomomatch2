@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/errors.css') }}">
@endsection

@section('content')
<div class="error-wrapper">
    <div class="error-code">419</div>
    <div class="cat-icon">🐱💨</div>
    <p class="error-message">
        セッションの有効期限が切れました。<br>
        もう一度ログインしてお試しください。
    </p>
    <a href="{{ route('login') }}" class="btn-back">ログインページへ</a>
</div>
@endsection
