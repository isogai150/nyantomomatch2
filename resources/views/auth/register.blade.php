@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}



<div class="background">
  <div class="allfonts">

    <div class="titlefont">
      <p>新規登録</p>
    </div>

    <div class="subtitle">
      <p>アカウントを作成して、猫との素敵な出会いを始めましょう</p>
    </div>

    <div class="main">
      <form action="" method="GET">
        <div class="fontsbold">
          <p>氏名<br>
        </div>
      <input type="text" class="textbox" name="name" placeholder="氏名を入力してください"></p>

      <form action="" method="GET">
        <div class="fontsbold">
          <p>メールアドレス</p>
        </div>
      <input type="email" class="textbox" name="name" placeholder="メールアドレスを入力してください"></p>

      <form action="" method="GET">
        <div class="fontsbold">
          <p>パスワード</p>
        </div>
      <input type="password" class="textbox" name="name" placeholder="8文字以上のパスワードを入力してください"></p>

      <form action="" method="GET">
        <div class="fontsbold">
          <p>パスワード確認</p>
        </div>
      <input type="password" class="textbox" name="name" placeholder="パスワードを再入力してください"></p>

      <form action="" method="GET">
        <div class="fontsbold">
          <p>都道府県</p>
        </div>
      <input type="text" class="textbox" name="name" placeholder="都道府県を入力してください"></p>
    </div>

    <div class="terms-text">
      <p><a href=""><span>利用規約</span></a>
        と
        <a href=""><span>プライバシーポリシー</span></a>
        に同意します</p>
    </div>

    <div class="botten">
      <form action="" method="GET">
        <input type="submit" value="アカウント作成">
      </form>
    </div>

  <div class="login-prompt">
    <p>すでにアカウントをお持ちの方は<a href=""><span>ログイン</span></a></p>
  </div>

  </div>
</div>



</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection