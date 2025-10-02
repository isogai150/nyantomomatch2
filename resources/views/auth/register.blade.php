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
    <input type="text" class="textbox" name="name"></p>

    <form action="" method="GET">
      <div class="fontsbold">
        <p>メールアドレス</p>
      </div>
    <input type="email" class="textbox" name="name"></p>

    <form action="" method="GET">
      <div class="fontsbold">
        <p>パスワード</p>
      </div>
    <input type="password" class="textbox" name="name"></p>

    <form action="" method="GET">
      <div class="fontsbold">
        <p>パスワード確認</p>
      </div>
    <input type="password" class="textbox" name="name"></p>

    <form action="" method="GET">
      <div class="fontsbold">
        <p>都道府県</p>
      </div>
    <input type="text" class="textbox" name="name"></p>
  </div>

  <p><span>利用規約</span>と<span>プライバシーポリシー</span>に同意します</p>

  <form action="" method="GET">
    <input type="submit" value="アカウント作成">
  </form>


  <p>すでにアカウントをお持ちの方は<span>ログイン</span></p>

</div>
</div>


</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection