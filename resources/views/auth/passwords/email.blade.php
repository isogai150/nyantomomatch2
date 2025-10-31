@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="main-content">

<div class="background">
  <div class="allfonts">

    <div class="titlefont">
      <p>パスワード再設定</p>
    </div>

    <div class="subtitle">
      <p>登録された下記のメールアドレスに<br>再設定用のリンクを送信します</p>
    </div>

    <div class="form-container">
      
      {{-- セッションメッセージ表示 --}}
      @if (session('status'))
      <div class="status-message">
        <p>{{ session('status') }}</p>
      </div>
      @endif

      {{-- バリデーションエラー表示 --}}
      @if($errors->any())
      <div class="alert-danger">
        @foreach($errors->all() as $message)
        <p>{{ $message }}</p>
        @endforeach
      </div>
      @endif

      <form action="{{ route('password.email') }}" method="POST" novalidate>
        @csrf

        <div class="form-group">
          <div class="labelfonts">
            <label for="email"><div class="text-left">メールアドレス</div></label>
          </div>
          <input type="email" class="textbox" id="email" name="email" placeholder="メールアドレスを入力してください" value="{{ old('email') }}" />
        </div>

        <button type="submit" class="botten">パスワード再設定メールを送信</button>

      </form>

      <div class="login-prompt">
        <p><a href="{{ route('login') }}"><span>ログインページに戻る</span></a></p>
      </div>

    </div>

  </div>
</div>

</div>
@endsection