@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/catpost/index.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">

{{-- ここの中にコードを書く --}}

{{-- ============================================================================ --}}
{{-- ============================================================================ --}}
<div class="main-content">

    <div class="titlefont">
        <h2>投稿管理</h2>
    </div>

    <div class="subtitle">
        <p>あなたが投稿した里親募集の一覧です</p>
    </div>

    <div class="newpost">
        <a href="">＋　新しい投稿</a>
    </div>

    <div class="summary-boxes">
        <div class="summary-item">
            <h3>{{ $myCatposts->count() }}</h3>
                <p>総投稿数</p>
        </div>
        <div class="summary-item">
            <h3>{{ $myCatposts->where('status', '譲渡完了')->count() }}</h3>
                <p>譲渡完了</p>
        </div>
        <div class="summary-item">
            <h3>{{ $myCatposts->where('status', '募集中')->count() }}</h3>
                <p>募集中</p>
        </div>
    </div>

{{-- ============================================================== --}}
{{-- 下部の自分の投稿一覧表示 --}}
{{-- ============================================================== --}}
{{-- 残りの編集部分 --}}
  <div class="post-list">
    @forelse($myCatposts as $post)
      <div class="post-card">
        <div class="post-image">
          @if($post->images->isNotEmpty())
            <img src="{{ asset('storage/' . $post->images->first()->image_path) }}" alt="猫画像">
          @else
            <div class="no-image">No Image</div>
          @endif
        </div>

        <div class="post-body">
          <h3>{{ $post->title }}</h3>

          {{-- 性別の表示変換 --}}
          @php
            $gender = match($post->gender) {
                1 => 'オス',
                2 => 'メス',
                default => '未入力',
            };
          @endphp

          <p>{{ $post->age }}歳　{{ $gender }}　{{ $post->region }}</p>

          <div class="post-info">
            <p>投稿日: {{ $post->created_at->format('Y年n月j日') }}</p>
          </div>

          {{-- ❤️いいね数追加 --}}
          <p class="favorites"><span class="heart">❤️</span>{{ $post->favorites_count ?? 0 }}</p>
        </div>

          <div class="post-actions">
            <a href="{{ route('posts.detail', $post->id) }}" class="btn-detail">詳細</a>
            <a href="{{ route('posts.edit', $post->id) }}" class="btn-edit">編集</a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="delete-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-delete">削除</button>
            </form>
          </div>
        </div>
    @empty
        <p>投稿がありません。</p>
    @endforelse
    </div>
</div>


{{-- ============================================================================ --}}
{{-- ============================================================================ --}}
{{-- コピー --}}
    {{-- <div class="post-list">
    @forelse($myCatposts as $post)
    <div class="post-card">
        <div class="post-image">
        @if($post->images->first())
            <img src="{{ asset('storage/' . $post->images->first()->path) }}" alt="猫画像">
            @else
            <div class="no-image">No Image</div>
            @endif
        </div>

        <div class="post-body">
            <h3>{{ $post->title }}</h3>
            <p>{{ $post->age }}歳　{{ $post->gender }}　{{ $post->region }}</p>

            <div class="post-info">
                <span>テスト {{ $post->favorites_count ?? 0 }}</span>
                <p>投稿日: {{ $post->created_at->format('Y年n月j日') }}</p>
            </div>

            <div class="post-actions">
                <a href="{{ route('posts.detail', $post->id) }}" class="btn-detail">詳細</a>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn-edit">編集</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">削除</button>
            </form>
            </div>
        </div>
    </div>
    @empty
        <p>投稿がありません。</p>
    @endforelse
    </div>
</div> --}}
{{-- ============================================================================ --}}

</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection
