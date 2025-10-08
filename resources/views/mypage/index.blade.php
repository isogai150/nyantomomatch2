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

  <div class="pege-ttl">
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
        <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" id="imageUploadForm">
        @csrf
        @method('PUT')
        <!-- 画像表示エリア（クリック可能） -->
        <label for="imageInput" style="cursor: pointer;">
          @if($user && $user->image_path)
            <img src="{{ Storage::url('profile_images/' . $user->image_path) }}" alt="{{ $user->name }}" class="profile-image" id="previewImage">
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

        {{-- 画像エラーメッセージの表示 --}}
        @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
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
        <form method="POST" action="{{ route('user.withdraw') }}" onsubmit="return confirmWithdrawal()">
          @csrf
          <input type="submit" value="退会する" class="btn btn-danger btn-primary-s">
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
        <textarea class="form-textarea" name="description" id="description" rows="10" placeholder="こちらに長いテキストを入力してください...">
          {{ old('description', $user->full_description) ?? $user->full_description  }}
        </textarea>
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
    <div class="authority-outline">
      <h3 class="text-blue">投稿権限について</h3>
      <p class="text-blue">
        投稿権限を取得すると、猫の里親募集投稿を作成・管理できるようになります。 申請には審査があり、承認まで数日かかる場合があります。
      </p>
    </div>
    {{-- <form action="{{ route('request-post-permission', ['user' => $user->id]) }}" method="POST">
      @csrf --}}
      <div class="form-group">
        <h3>申請理由</h3>
        <textarea class="form-textarea" name="reason" id="reason" rows="10" placeholder="こちらに長いテキストを入力してください...">
          {{-- {{ old('reason', $authority->reason) }} --}}
        </textarea>
        <p class="textarea-finish">0/500文字</p>
        @error('reason')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
      <div class="agree">
        <label>
        <input type="checkbox" id="agree" name="agree" value="1">
        利用規約に同意する
        </label>
        <p class="agree-text">
          投稿権限の利用規約および責任について同意します。
        <a href="#">利用規約を確認する</a></p>
        @error('agree')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror
      </div>
      <input type="submit" value="申請を送信する" class="btn-primary-l">
    {{-- </form> --}}
  </div>
@endsection

@section('script')
<script>
// ユーザーアイコン画像
document.getElementById('imageInput').addEventListener('change', function(e) {
  const file = e.target.files[0];

  if (file) {
    // 画像プレビュー
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('previewImage').src = e.target.result;
    };
    reader.readAsDataURL(file);

    // 自動で送信（オプション）
    // document.getElementById('imageUploadForm').submit();

    // または確認ダイアログを表示
    if (confirm('この画像にアップロードしますか？')) {
      document.getElementById('imageUploadForm').submit();
    } else {
      // キャンセルした場合は元の画像に戻す
      e.target.value = '';
      location.reload();
    }
  }
});

// ユーザー退会処理
// return true: フォーム送信、return false: フォーム送信キャンセル
function confirmWithdrawal() {
    return confirm('本当に退会しますか？\nこの操作は取り消せません。');
}

</script>
@endsection