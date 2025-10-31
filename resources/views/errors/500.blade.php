@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/errors.css') }}">
@endsection

@section('content')
<div class="error-wrapper">
    <div class="error-code">500</div>
    <div class="cat-icon">🐾💤</div>
    <p class="error-message">
        サーバーが少しお昼寝しているようです。<br>
        しばらくしてから再度お試しください。
    </p>
    <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
</div>
@endsection
