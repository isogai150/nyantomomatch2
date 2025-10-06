@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

  <div class="pege-ttl">
    <h2>マイページ</h2>
    <div>
      <h3>プロフィール情報の確認・編集ができます</h3>
    </div>
  </div>

{{-- プロフィール --}}
  <div class="profile">
    <div class="profile-wrapper">
      <div class="prf-imgfile">
        @if($user->image_path)
          <img src="{{ asset('images/seeder' . $user->image_path) }}" alt="{{ $user->name }}" class="profile-image">
        @else
          <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="デフォルト画像" class="profile-image">
        @endif
      </div>
      <div class="prf-content">
        <div class="prf-name">
          <h2>{{ $user->name }}
          <span class="prf-status">{{ $user->role }}</span></h2>
        </div>
        <div class="prf-description">
          <h3>{{ $user->description }}</h3>
        </div>
        <div class="prf-create">
          <p>{{ $user->create_at }}</p>
        </div>
      </div>
    </div>
  </div>

{{-- 基本情報 --}}
  <div class="information">

    {{-- バリデーションメッセージを表示するためのもの --}}
    @if($errors->any())
    <div class="alert alert-danger">
      @foreach($errors->all() as $message)
      <p>{{ $message }}</p>
      @endforeach
    </div>
    @endif
    
    <form action="{{ route('mypage.index', ['user' => $user->user_id]) }}" method="POST">
      @csrf
      <div class="information-flex">
        <div class="h3-b30px">
          <h3>基本情報</h3>
        </div>
        <input type="submit" value="更新" class="btn-primary-s">
      </div>
      <div class="form-group">
        <h3>氏名</h3>
        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? $user->name }}" />
      </div>
      <div class="form-group">
        <h3>メールアドレス</h3>
        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') ?? $user->email }}" />
      </div>
      <div class="form-group">
        <h3>自己紹介（飼育経験年数・現在の居住環境・家族構成など入力できる範囲で入力してください）</h3>
        <textarea class="form-textarea" name="description" id="description" rows="10" placeholder="こちらに長いテキストを入力してください...">
          {{ old('description', $user->description) ?? $user->description  }}
        </textarea>
      </div>
    </form>
  </div>

{{-- 投稿権限申請フォーム --}}
  <div class="authority">
    <div class="h3-b30px">
      <h3>投稿権限申請フォーム</h3>
    </div>
    <div class="authority-outline">
      <h3 class="text-blue">投稿権限について</h3>
      <p class="text-blue">
        投稿権限を取得すると、猫の里親募集投稿を作成・管理できるようになります。 申請には審査があり、承認まで数日かかる場合があります。
      </p>
    </div>
    {{-- <form action="{{ route('request-post-permission', ['user' => $user->user_id]) }}" method="POST">
      @csrf --}}
      <div class="form-group">
        <h3>申請理由</h3>
        <textarea class="form-textarea" name="reason" id="reason" rows="10" placeholder="こちらに長いテキストを入力してください...">
          {{-- {{ old('reason', $authority->reason) }} --}}
        </textarea>
        <p class="textarea-finish">0/500文字</p>
      </div>
      <div class="agree">
        <label>
        <input type="checkbox" id="agree" name="agree" value="1">
        利用規約に同意する
        </label>
        <p class="agree-text">
          投稿権限の利用規約および責任について同意します。
        <a href="#">利用規約を確認する</a></p>
      </div>
      <input type="submit" value="申請を送信する" class="btn-primary-l">
    {{-- </form> --}}
  </div>
@endsection