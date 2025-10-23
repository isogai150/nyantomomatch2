@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

  <div class="page-ttl">
    <h2>マイページ</h2>
    <div>
      <h3>プロフィール情報の確認・編集ができます</h3>
    </div>
  </div>

{{-- プロフィール --}}
  <div class="profile">
    <div class="profile-wrapper">
      {{-- ユーザーアイコン --}}
      <div class="prf-imgfile">
        <div class="profile-image-container">
          <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
          @csrf
          @method('PUT')
          <!-- 画像表示エリア（クリック可能） -->
          <label for="imageInput" style="cursor: pointer;">
            @if($user && $user->image_path)
            <img src="{{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $user->image_path) }}" alt="プロフィール画像" class="profile-image" id="previewImage">
              {{-- <img src="{{ Storage::url('profile_images/' . $user->image_path) }}" alt="{{ $user->name }}" class="profile-image" id="previewImage"> --}}
            @else
              <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="デフォルト画像" class="profile-image" id="previewImage">
            @endif
            <div class="image-overlay">
              <span>画像を変更</span>
            </div>
          </label>
          <!-- 非表示のファイル入力 -->
          <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;">
          </form>
        </div>

        {{-- 画像エラーメッセージの表示 --}}
        @if(session('image_success'))
        <div class="alert alert-success">
          {{ session('image_success') }}
        </div>
        @endif
        @error('image')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="prf-content">
        <div class="prf-name">
          <h2>{{ $user->name }}
          <span class="prf-status">{{ $user->role_label }}</span></h2>
        </div>
        <div class="prf-description">
          <h3>{{ $user->short_description }}</h3>
        </div>
        <div class="prf-create">
          <p>登録日:<br class="br-sp"> {{ $user->created_at->format('Y年m月d日') }}</p>
        </div>
      </div>

      <!-- 退会ボタン -->
      <div class="btn-primary-s4">
        <form method="POST" action="{{ route('user.withdraw') }}" id="withdrawalForm">
          @csrf
          @method('DELETE')
          <input type="submit" value="退会する" class="btn btn-danger btn-primary-s4">
        </form>
      </div>
    </div>
  </div>

{{-- 基本情報 --}}
  <div class="information">
    <form action="{{ route('mypage.edit', ['user' => $user->id]) }}" method="POST">
      @csrf
      @method('put')
      <div class="information-flex">
        <div class="h3-b30px">
          <h3>基本情報</h3>
        </div>
        <input type="submit" value="更新" class="btn-primary-s">
      </div>
      <div class="form-group">
        <h3>氏名</h3>
        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? $user->name }}" />
        @error('name')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <h3>メールアドレス</h3>
        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') ?? $user->email }}" />
        @error('email')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group">
        <h3>自己紹介（飼育経験年数・現在の居住環境・家族構成など入力できる範囲で入力してください）</h3>
        <textarea class="form-textarea" name="description" id="description" rows="10" placeholder="こちらに自己紹介文を入力してください...">{{ old('description', $user->full_description) ?? $user->full_description  }}</textarea>
        @error('description')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
    </form>
  </div>

{{-- 投稿権限申請フォーム --}}
  <div class="authority">
    <div class="h3-b30px">
      <h3>投稿権限申請フォーム</h3>
    </div>

  {{-- 成功/エラーメッセージの表示 --}}
  @if(session('authority_success'))
    <div class="alert alert-success">
      {{ session('authority_success') }}
    </div>
  @endif
  @if(session('authority_error'))
    <div class="alert alert-danger">
      {{ session('authority_error') }}
    </div>
  @endif

    <div class="authority-outline">
      <h3 class="text-blue">投稿権限について</h3>
      <p class="text-blue">
        投稿権限を取得すると、猫の里親募集投稿を作成・管理できるようになります。 申請には審査があり、承認まで数日かかる場合があります。
      </p>
    </div>

    <form action="#" method="POST">
      @csrf
      <div class="form-group">
        <h3>申請理由</h3>
        <textarea class="form-textarea" name="reason" id="reason" rows="10" placeholder="こちらに申請理由を入力してください...">{{ old('reason') }}</textarea>
        <p class="textarea-finish"><span id="charCount">0</span>/500文字</p>
        @error('reason')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="agree">
        <label>
        <input type="checkbox" id="agree" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
        利用規約に同意する
        </label>
        <p class="agree-text">
          投稿権限の利用規約および責任について同意します。
          <a href="#">利用規約を確認する</a>
        </p>
        @error('agree')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>

      <input type="submit" value="申請を送信する" class="btn-primary-l">
    </form>
  </div>
@endsection

@section('script')
<script src="{{ asset('js/mypage/index.js') }}"></script>
@endsection