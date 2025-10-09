@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dm/detail.css') }}">
@endsection

@section('content')
<div class="dm-wrapper">

  {{-- ======= 戻るリンク・相手ユーザー情報 ======= --}}
  <div class="dm-header">
    <div class="back-page">
      <a href="{{ route('dm.index') }}">＜ 戻る</a>
    </div>

    <div class="dm-user-info">
      <div class="dm-user-icon"></div>
      <div class="dm-user-name">{{ $partner->name ?? '相手のユーザー' }}</div>
    </div>
  </div>

{{-- ======= 投稿情報（この猫について） ======= --}}
@if($post)
  <div class="dm-post-info">
    <div class="dm-post-img-area">
      @php
        $firstImage = optional($post->images->first())->image_path; // post_images.image_path
        $imagePath  = $firstImage ? str_replace('public/', '', $firstImage) : null;
      @endphp

      @if ($imagePath)
        {{-- Seeder（public/images/seeder/...）用 --}}
        <img src="{{ asset($imagePath) }}" alt="猫の写真" class="dm-post-img">
        {{-- 本番で storage に移すなら下に切替（storage:link 済前提） --}}
        {{-- <img src="{{ asset('storage/' . $imagePath) }}" alt="猫の写真" class="dm-post-img"> --}}
      @else
        <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image" class="dm-post-img">
      @endif
    </div>

    <div class="dm-post-text-area">
      <h3 class="dm-post-title">{{ $post->title }}</h3>
      <p class="dm-post-desc">{{ $post->summary ?? 'この猫についてのやり取りです。' }}</p>
      <div class="dm-post-buttons">
        <a href="{{ route('posts.detail', $post->id) }}" class="btn-detail">詳細を見る</a>
      </div>
    </div>
  </div>
@endif



  {{-- ======= メッセージ一覧 ======= --}}
  <div id="dm-messages" class="dm-messages">
    @foreach($messages as $message)
      <div class="dm-message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}">
        <div class="dm-text">{{ $message->content }}</div>
        <div class="dm-time">{{ $message->created_at->format('Y年n月j日 H:i') }}</div>
      </div>
    @endforeach
  </div>

  {{-- ======= メッセージ送信フォーム ======= --}}
  <form id="dm-form" class="dm-form" autocomplete="off">
    @csrf
    <textarea id="message-input" name="message" placeholder="メッセージを入力..." required></textarea>
    <button type="submit" id="send-btn">送信</button>
  </form>
</div>
@endsection

@section('script')
<script>
  // グローバル変数を定義（LaravelがjQueryに値を渡しているイメージ）
  window.dmConfig = {
    // Laravel側のメッセージ取得URLを生成
    fetchUrl: "{{ route('dm.message.fetch', $dm->id) }}",
    // Laravel側のメッセージ送信URLを生成
    sendUrl: "{{ route('dm.message.send', $dm->id) }}",
    // Laravelはフォーム送信やAjax通信でcsrfトークンが必須
    csrfToken: "{{ csrf_token() }}",
    // 現在ログイン中のユーザーidをBladeから埋め込んで渡す
    authId: {{ auth()->id() }}
  };
</script>
<script src="{{ asset('js/dm/detail.js') }}"></script>
@endsection
