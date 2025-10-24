@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', '管理者ログイン')

@section('auth_body')
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="メールアドレス" required autofocus>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="パスワード" required>
        </div>
        <button type="submit" class="btn btn-block btn-primary">ログイン</button>
    </form>
@endsection
