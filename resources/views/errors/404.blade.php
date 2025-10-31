@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/errors.css') }}">
@endsection

@section('content')
<div class="error-wrapper">
    <div class="error-code">404</div>
    <div class="cat-icon">🐾</div>
    <p class="error-message">
        お探しのページは見つかりませんでした。<br>
        猫が画面の裏に隠れてしまったのかもしれません。
    </p>
    <a href="{{ route('posts.index') }}" class="btn-back">トップページへ戻る</a>
</div>
@endsection
