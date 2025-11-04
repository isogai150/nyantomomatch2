@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

{{-- ============================================================================ --}}
{{-- ============================================================================ --}}

<div class="background">
  <div class="allfonts">

    <div class="titlefont">
      <p>新規登録</p>
    </div>

    <div class="subtitle">
      <p>アカウントを作成して、<br class="br-sp">猫との素敵な出会いを始めましょう</p>
    </div>

    <div class="form-container">
      <form action="{{ route('register') }}" method="POST" novalidate>
      @csrf

    <div class="form-group">
      <div class="labelfonts">
        <label for="name"><div class="text-left">ユーザー名</div></label>
      </div>
        <input type="text" class="textbox" id="name" name="name" placeholder="氏名を入力してください" value="{{ old('name') }}" />
    </div>

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

    <div class="form-group">
      <div class="labelfonts">
        <label for="password-confirm"><div class="text-left">パスワード（確認）</div></label>
      </div>
        <input type="password" class="textbox" id="password-confirm" name="password_confirmation" placeholder="パスワードを再入力してください">
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

    <div class="terms-text">
      <p><a href="#" class="footer-modal-open" data-target="termsModal"><span>利用規約</span></a>
      <br class="br-sp">と<br class="br-sp">
      <a href="#" class="footer-modal-open" data-target="privacyModal"><span>プライバシーポリシー</span></a>
      <br class="br-sp">に同意します</p>
    </div>

    <button type="submit" class="botten">アカウント作成</button>

      </form>
    </div>

  <div class="login-prompt">
    <p>すでにアカウントを<br class="br-sp">お持ちの方は<br class="br-sp"><a href="{{ route('login') }}"><span>ログイン</span></a></p>
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