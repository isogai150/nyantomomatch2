@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', '管理者ログイン')

@section('auth_body')
    <form method="POST" action="{{ route('admin.login') }}" novalidate>
        @csrf

        {{-- エラーメッセージ全体表示 --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

<div class="input-group mb-3">
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
        placeholder="メールアドレス" value="{{ old('email') }}" required autofocus>
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-envelope"></span>
        </div>
    </div>
    @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

<div class="input-group mb-3">
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
        placeholder="パスワード" required>
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
    </div>
    @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>


        <button type="submit" class="btn btn-block btn-primary">ログイン</button>
    </form>
@endsection