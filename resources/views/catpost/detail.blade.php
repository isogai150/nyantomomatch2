@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/catpost/detail.css') }}">
@endsection

@section('content')
<div class="post-detail-container">

  <div class="post-media">
    @if($post->images->isNotEmpty())
      <img id="main-image" 
           src="{{ asset(str_replace('public/', '', $post->images->first()->image_path)) }}" 
           alt="メイン画像" class="main-photo">

      {{-- 本番用（storageリンク使用時） --}}
      {{-- <img id="main-image" src="{{ Storage::url($post->images->first()->image_path) }}" alt="メイン画像" class="main-photo"> --}}
    @else
      <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" 
           alt="No Image" class="main-photo">
    @endif

    <div class="thumbnail-list">
      @foreach($post->images as $image)
        <img src="{{ asset(str_replace('public/', '', $image->image_path)) }}" 
             class="thumbnail" alt="サムネイル画像">
      @endforeach

      @foreach($post->videos as $video)
        <video class="thumbnail" muted>
          <source src="{{ asset(str_replace('public/', '', $video->video_path)) }}" type="video/mp4">
        </video>
      @endforeach
    </div>
  </div>

  <div class="post-detail-content">

    <div class="post-main">
      <h1 class="post-title">{{ $post->title }}</h1>

      <div class="post-meta">
        <span>投稿日時：{{ $post->created_at->format('Y/m/d H:i') }}</span>
        <span>ステータス：
          <span class="{{ $post->status_class }}">{{ $post->status_label }}</span>
        </span>
      </div>

      <div class="post-description">
        {!! nl2br(e($post->body ?? '詳細情報がありません。')) !!}
      </div>
    </div>

    <aside class="post-sidebar">
      <div class="contact-box">
        <h3>お問い合わせ</h3>
        <button class="contact-btn">この猫に問い合わせる</button>
      </div>

      <div class="user-info">
        <h4>投稿者情報</h4>
        <p>名前：{{ $post->user->name }}</p>
        <p>地域：{{ $post->region ?? '未設定' }}</p>
      </div>
    </aside>

  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/catposts/detail.js') }}"></script>
@endsection
