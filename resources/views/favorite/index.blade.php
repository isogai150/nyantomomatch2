@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/favorite/index.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

  <div class="page-ttl">
    <h2>お気に入り</h2>
  </div>

  <div class="container">
    @foreach ($catposts as $catpost)
      <div class="catpost-card">
        <div class="post-image">
          {{-- 投稿画像（最初の1枚を表示） --}}
          @if ($catpost->images->isNotEmpty())
            {{-- Seeder時（開発中） --}}
            <img src="{{ asset(str_replace('public/', '', $catpost->images->first()->image_path)) }}" alt="投稿画像">
            {{-- 本番用（storage:linkを使用してストレージパスへ変更予定） --}}
            {{-- <img src="{{ Storage::url($catpost->images->first()->image_path) }}" alt="投稿画像"> --}}
          @else
            <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image">
          @endif

          {{-- お気に入りボタン --}}
          <form action="{{ route('favorites.toggle', $catpost->id) }}" method="POST">
            @csrf
            <button type="submit" class="favorite-btn">
              {{ Auth::user()->favoritePosts->contains($catpost->id) ? '❤' : '♡' }}
            </button>
          </form>
        </div>

        <div class="post-information">
          <div class="post-information-top">
            <h3>{{ $catpost->title }}</h3>
            <p class="{{ $catpost->status_class }}">{{ $catpost->status_label }}</p>
          </div>

          <div class="post-information-center">
            <ul>
              <li>{{ $catpost->unit_age }}</li>
              <li>{{ $catpost->gender_class }}</li>
              <li>{{ $catpost->region }}</li>
            </ul>
          </div>

          {{-- メッセージ --}}
          <form action="{{ route('dm.create', ['post' => $post->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="post" value="{{ $post->id }}">
            <button type="submit" class="contact-btn">メッセージ</button>
          </form>

          {{-- 詳細ボタン --}}
          <a href="{{ route('posts.detail', $catpost->id) }}" class="detail-btn">詳細を見る</a>
          <button type="button" class="detail-btn " data-id="{{ $catpost->id }}">詳細を見る</button>
        </div>
      </div>
    @endforeach
  </div>
</div>


@endsection

@section('script')
<script src="{{ asset('js/favorite/index.js') }}"></script>
@endsection
