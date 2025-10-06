@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home/index.css') }}">
@endsection

@section('content')
<div class="main-content">
  <div class="main-top">
    <div class="title">
      <h1>猫との新しい出会いを<br><span>にゃん×とも×まっち</span></h1>
      <p>愛情いっぱいの猫たちが、新しい家族との出会いを待っています。<br>あなたの人生に特別な仲間を迎えませんか？</p>
    </div>

    <div class="post-searchs flex">
      {{-- 検索フォーム --}}
      <div class="post-search">
        <h2>里親募集中の猫たち</h2>
        <form action="{{ route('posts.index') }}" method="GET" class="flex">
          <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="キーワード検索">
          <button type="submit" class="btn search-btn">検索</button>
        </form>
      </div>

      {{-- 並べ替えフォーム --}}
      <div class="post-sort">
        <form action="{{ route('posts.index') }}" method="GET">
          <select name="sort" class="btn sort-select" onchange="this.form.submit()">
            <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>新しい順</option>
            <option value="old" {{ request('sort') == 'old' ? 'selected' : '' }}>古い順</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>人気順</option>
          </select>
        </form>
      </div>
    </div>
  </div>

  <div class="main-center">
    {{-- @dd($catposts) --}}
    @foreach ($catposts as $catpost)
    {{-- @dd($catpost); --}}
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
          @if(Auth::check())
            <form action="{{ route('favorites.toggle', $catpost->id) }}" method="POST">
              @csrf
              <button type="submit" class="favorite-btn">
                {{ Auth::user()->favoritePosts->contains($catpost->id) ? '❤' : '♡' }}
              </button>
            </form>
          @endif

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

          {{-- 詳細ボタン --}}
          <a href="{{ route('posts.detail', $catpost->id) }}" class="detail-btn">詳細を見る</a>
        </div>
      </div>
    @endforeach
  </div>

  {{-- 未ログイン時のみ表示 --}}
  @guest
    <div class="cta-section">
      <h2>猫の里親になりませんか？<br><span class="bottom-text">登録は無料です。</span></h2>
      <a href="{{ route('register') }}" class="cta-btn">今すぐ登録する</a>
    </div>
  @endguest
</div>

{{-- モーダルウィンドウ --}}
<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close">×</span>
    <h2>ログインまたは<br>新規登録してください</h2>
    <a href="{{ route('login') }}" class="btn">ログイン</a>
    <a href="{{ route('register') }}" class="btn">新規登録</a>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/home/index.js') }}"></script>
@endsection
