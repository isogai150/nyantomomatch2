@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/favorite/index.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

  <div class="page-ttl">
    <h2>お気に入り</h2>
    <h3>気になる猫たちをお気に入りに保存して、後で確認できます</h3>
    <p>※譲渡成立しているものは表示されません</p>
  </div>

  @if($catposts->isEmpty())
    <div class="empty-message">
      <p>お気に入り登録された投稿はありません</p>
    </div>
  @else

  <div class="container">
    @foreach ($catposts as $catpost)
      <div class="catpost-card">
        <div class="post-image">
          {{-- 投稿画像（最初の1枚を表示） --}}
          @if ($catpost->images->isNotEmpty())
            {{-- Seeder時（開発中） --}}
            {{-- <img src="{{ asset(str_replace('public/', '', $catpost->images->first()->image_path)) }}" alt="投稿画像"> --}}
            {{-- 本番用（storage:linkを使用してストレージパスへ変更予定） --}}
            <img src="{{ Storage::url($catpost->images->first()->image_path) }}" alt="投稿画像">
            {{-- <img src="{{ Storage::disk(config('filesystems.default'))->url($catpost->images->first()->image_path) }}" alt="投稿画像"> --}}
          @else
            <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image">
          @endif

          {{-- お気に入りボタン --}}
          <form action="{{ route('favorites.toggle', $catpost->id) }}" method="POST">
            @csrf
            <button type="submit" class="favorite-btn active" aria-label="お気に入りを解除">
              ❤
            </button>
          </form>
        </div>

        <div class="post-information">
          <div class="post-information-top">
            <h3>{{ $catpost->title }}</h3>
          </div>

          <div class="post-information-center">
            <ul>
              <li>{{ $catpost->unit_age }}</li>
              <li>{{ $catpost->gender_class }}</li>
              <li>{{ $catpost->region }}</li>
            </ul>
          </div>
          <div class="post-information-status">
            <p class="{{ $catpost->status_class }}">{{ $catpost->status_label }}</p>
          </div>
          <div class="post-information-created_at">
            <p>お気に入り登録日: {{ $catpost->pivot->created_at->format('Y年n月j日') }}</p>
          </div>
          <div class="post-actions">
            {{-- メッセージ --}}
            <form action="{{ route('dm.create', ['post' => $catpost->id]) }}" method="POST" class="message-btn">
              @csrf
              <input type="hidden" name="post" value="{{ $catpost->id }}">
              <button type="submit" class="contact-btn">
                <svg class="message-icon" viewBox="0 0 24 24">
                  <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
                メッセージ</button>
            </form>

            {{-- 詳細ボタン --}}
            <a href="{{ route('posts.detail', $catpost->id) }}" class="detail-btn">詳細を見る</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

    {{-- ページネーション --}}
    @if(method_exists($catposts, 'links'))
      <div class="pagination">
        {{ $catposts->links() }}
      </div>
    @endif

  @endif
</div>
@endsection

