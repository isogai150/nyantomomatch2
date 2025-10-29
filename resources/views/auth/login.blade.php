@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">

@endsection

@section('content')
<div class="main-content">

<div class="background">
  <div class="allfonts">

    <div class="titlefont">
      <p>ログイン</p>
    </div>

    <div class="subtitle">
      <p>アカウントにログインして、<br class="br-sp">猫との出会いを始めましょう</p>
    </div>

    <div class="form-container">
      <form action="{{ route('login') }}" method="POST" novalidate>
      @csrf


    <div class="form-group">
      <div class="labelfonts">
        <label for="email"><div class="text-left">メールアドレス</div></label>
      </div>
        <input type="email" class="textbox" id="email" name="email" placeholder="メールアドレスを入力してください" value="{{ old('email') }}" />
      </div>

    <div class="form-group">
      <div class="labelfonts">
        <label for="password"><div class="text-left">パスワード</div></label>
      </div>
        <input type="password" class="textbox" id="password" name="password" placeholder="8文字以上のパスワードを入力してください">
      </div>

      <div class="password-forget">
        <p><a href="">パスワードを<br class="br-sp">忘れた方</a></p>
      </div>

{{-- ======================================== --}}
{{-- バリデーションメッセージを表示するためのもの --}}
@if($errors->any())
  <div class="alert-danger">
@foreach($errors->all() as $message)
  <p>{{ $message }}</p>
@endforeach
  </div>
@endif
{{-- ======================================== --}}

    <button type="submit" class="botten">ログイン</button>

      </form>
    </div>

  <div class="login-prompt">
    <p>アカウントを<br class="br-sp">お持ちでない方は<br class="br-sp"><a href="{{ route('register') }}"><span>新規登録</span></a></p>
  </div>

  </div>
</div>

{{-- ============================================================================ --}}
{{-- ============================================================================ --}}

</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection