@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/errors.css') }}">
@endsection

@section('content')
<div class="error-wrapper">
    <div class="error-code">403</div>
    <div class="cat-icon">🐱💢</div>
    <p class="error-message">
        このページにアクセスする権限がありません。<br>
        ニャんとも残念ですが、別のページからお試しください。
    </p>
    <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
</div>
@endsection
