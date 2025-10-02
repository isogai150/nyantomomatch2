@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}
  <div class="pege-ttl">
    <h2>マイページ</h2>
    <h3>プロフィール情報の確認・編集ができます</h3>
  </div>

  <div class="profile">
    <div class="imgfile">
      {{-- <img src="{{ asset('images/' . $user->image_path) }}" alt="ユーザーアイコン"> --}}
    </div>

    <h2>ユーザー名</h2>
    <p>ユーザーステータス</p>
    <p>自己紹介</p>
    <p>登録日</p>
    {{-- <h2>{{ $user->name }}</h2> --}}
    {{-- <p>{{ $user->role }}</p> --}}
    {{-- <p>{{ $user->description }}</p> --}}
    {{-- <p>{{ $user->created_at }}</p> --}}
  </div>

  <div class="information">
    <h3>基本情報</h3>
    {{-- <form action="{{ route('mypege.edit', ['user' => $user->user_id]) }}" method="POST"> --}}
    @csrf
      <div class="form-group">
        <h3>氏名</h3>
        <input type="text" class="form-control" name="name" id="name" value="山田太郎" />
        {{-- <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? $user->name }}" /> --}}
      </div>
      <div class="form-group">
        <h3>メールアドレス</h3>
        <input type="text" class="form-control" name="email" id="email" value="@gmail.com" />
        {{-- <input type="text" class="form-control" name="email" id="email" value="{{ old('email') ?? $user->email }}" /> --}}
      </div>
      <div class="form-group">
        <h3>自己紹介（飼育経験濃霧・現在の居住環境・家族構成など入力できる範囲で入力してください）</h3>
        <input type="text" class="form-control" name="description" id="description" value="コメントコメント" />
        {{-- <input type="text" class="form-control" name="description" id="description" value="{{ old('description') ?? $user->description }}" /> --}}
      </div>
      <div class="profile-right">
        <input type="submit" value="編集" class="btn btn-primary">
      </div>
    </form>
  </div>

  <div class="authority">
    <h3>投稿権限申請フォーム</h3>
    <div class="authority-outline">
      <h3>投稿権限について</h3>
      <p>
        投稿権限を取得すると、猫の里親募集投稿を作成・管理できるようになります。 申請には審査があり、承認まで数日かかる場合があります。
      </p>
    </div>
    {{-- <form action="{{ route('request-post-permission', ['user' => $user->user_id]) }}" method="POST"> --}}
    @csrf
      <div class="form-group">
        <h3>申請理由</h3>
        <input type="text" class="form-control" name="reason" id="reason" value="" />
        <p>0/500文字</p>
      </div>
      <div>
        <h3>利用規約に同意する</h3>
        <p>投稿権限の利用規約および責任について同意します。</p>
        <a href="#">利用規約を確認する</a>
        <input type="submit" value="申請を送信する" class="btn btn-primary">
      </div>
    </form>
  </div>
</div>
@endsection