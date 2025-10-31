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
      <p>新しいパスワードを入力してください</p>
    </div>

    <div class="form-container">

      {{-- バリデーションエラー表示 --}}
      @if($errors->any())
      <div class="alert-danger">
        @foreach($errors->all() as $message)
        <p>{{ $message }}</p>
        @endforeach
      </div>
      @endif

      <form action="{{ route('password.update') }}" method="POST" novalidate>
        @csrf

        {{-- トークンを隠しフィールドで送信 --}}
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <div class="labelfonts">
            <label for="email"><div class="text-left">メールアドレス</div></label>
          </div>
          <input type="email" class="textbox" id="email" name="email" placeholder="メールアドレスを入力してください" value="{{ old('email', $email ?? '') }}" />
        </div>

        <div class="form-group">
          <div class="labelfonts">
            <label for="password"><div class="text-left">新しいパスワード</div></label>
          </div>
          <input type="password" class="textbox" id="password" name="password" placeholder="8文字以上のパスワードを入力してください">
        </div>

        <div class="form-group">
          <div class="labelfonts">
            <label for="password_confirmation"><div class="text-left">パスワード確認</div></label>
          </div>
          <input type="password" class="textbox" id="password_confirmation" name="password_confirmation" placeholder="確認のため再度パスワードを入力してください">
        </div>

        <button type="submit" class="botten">パスワードを更新</button>
      </form>

      <div class="login-prompt">
        <p><a href="{{ route('login') }}"><span>ログインページに戻る</span></a></p>
      </div>

    </div>

  </div>
</div>

</div>
@endsection

@section('script')
<script>
    // パスワードリセット成功時にアラートを表示してからログインページに遷移
    @if(session('password_reset_success'))
        alert('パスワード再設定が完了しました。');
        window.location.href = '/';
    @endif
</script>
@endsection