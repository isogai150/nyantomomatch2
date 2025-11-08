@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home/index.css') }}">
@endsection

@section('content')
    <div class="main-content">
        <div class="main-top">

            <video autoplay muted loop playsinline class="hero-video">
                <source src="{{ asset('videos/kitten-loop.mp4') }}" type="video/mp4">
            </video>

            <div class="title">
                <h1>猫との新しい出会いを<br><span>にゃん×とも×まっち</span></h1>
                <p>愛情いっぱいの猫たちが、<br class="br-sp">新しい家族との出会いを待っています。<br>あなたの人生に特別な仲間を迎えませんか？</p>
            </div>
        </div>

        <div class="post-searchs flex">
            {{-- 検索フォーム --}}
            <div class="post-search">
                <h2>里親募集中の猫たち</h2>
                <form action="{{ route('posts.index') }}" method="GET" class="flex">
                    <input type="text" class="search-input" name="search" value="{{ request('search') }}"
                        placeholder="キーワード検索">
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

        <div class="main-center">
            {{-- 投稿カード一覧 --}}
            @foreach ($catposts as $catpost)
                <div class="catpost-card">
                    <div class="post-image">
                        {{-- 投稿画像（最初の1枚を表示） --}}
                        @if ($catpost->images->isNotEmpty())
                            <img src="{{ Storage::disk(config('filesystems.default'))->url('post_images/' . $catpost->images->first()->image_path) }}"
                                alt="投稿画像">
                        @else
                            <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image">
                        @endif

                        {{-- お気に入りボタン --}}
                        @if (Auth::check())
                            <form action="{{ route('favorites.toggle', $catpost->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="favorite-btn">
                                    {{ Auth::user()->favoritePosts->contains($catpost->id) ? '❤' : '♡' }}
                                </button>
                            </form>
                        @else
                            {{-- 未ログイン時：クリックでモーダル表示 --}}
                            <button type="button" class="favorite-btn modal-open">♡</button>
                        @endif
                    </div>

                    {{-- 投稿情報 --}}
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
                        @if (Auth::check())
                            <a href="{{ route('posts.detail', $catpost->id) }}" class="detail-btn">詳細を見る</a>
                        @else
                            <button type="button" class="detail-btn modal-open"
                                data-id="{{ $catpost->id }}">詳細を見る</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>



                {{-- ページネーション --}}
            @if(isset($catposts) && $catposts->lastPage() > 1)
            <div class="pagination">
                <ul class="pagination-list">
                {{-- 前へ --}}
                @if($catposts->currentPage() > 1)
                    <li class="pagination-item">
                    <a href="{{ $catposts->previousPageUrl() }}" class="pagination-link pagination-prev">
                        prev ←
                    </a>
                    </li>
                @endif

                {{-- 最初のページ --}}
                @if($catposts->currentPage() > 3)
                    <li class="pagination-item">
                    <a href="{{ $catposts->url(1) }}" class="pagination-link">1</a>
                    </li>
                    @if($catposts->currentPage() > 4)
                    <li class="pagination-item">
                        <span class="pagination-dots">...</span>
                    </li>
                    @endif
                @endif

                {{-- 現在ページ周辺 --}}
                @for($i = max(1, $catposts->currentPage() - 2); $i <= min($catposts->lastPage(), $catposts->currentPage() + 2); $i++)
                    @if($i == $catposts->currentPage())
                    <li class="pagination-item">
                        <span class="pagination-link pagination-current">{{ $i }}</span>
                    </li>
                    @else
                    <li class="pagination-item">
                        <a href="{{ $catposts->url($i) }}" class="pagination-link">{{ $i }}</a>
                    </li>
                    @endif
                @endfor

                {{-- 最後のページ --}}
                @if($catposts->currentPage() < $catposts->lastPage() - 2)
                    @if($catposts->currentPage() < $catposts->lastPage() - 3)
                    <li class="pagination-item">
                        <span class="pagination-dots">...</span>
                    </li>
                    @endif
                    <li class="pagination-item">
                    <a href="{{ $catposts->url($catposts->lastPage()) }}" class="pagination-link">{{ $catposts->lastPage() }}</a>
                    </li>
                @endif

                {{-- 次へ --}}
                @if($catposts->hasMorePages())
                    <li class="pagination-item">
                    <a href="{{ $catposts->nextPageUrl() }}" class="pagination-link pagination-next">
                        next →
                    </a>
                    </li>
                @endif
                </ul>
            </div>
            @endif


        {{-- 未ログイン時のみ表示 --}}
        @guest
            <div class="cta-section">
                <h2>猫の里親になりませんか？<br><span class="bottom-text">登録は無料です。</span></h2>
                <a href="{{ route('register') }}" class="cta-btn">今すぐ登録する</a>
            </div>
        @endguest

    {{-- モーダルウィンドウ --}}
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">×</span>
            <h2>ログインまたは<br>新規登録してください</h2>
            <a href="{{ route('login') }}" class="btn">ログイン</a>
            <a href="{{ route('register') }}" class="btn">新規登録</a>
        </div>
    </div>


    {{-- ===== 右下固定の「AIに相談」ボタン ===== --}}
    <a href="{{ route('chat.index') }}" class="ai-consult-btn">
        AIに相談
    </a>
@endsection

@section('script')
    <script src="{{ asset('js/home/index.js') }}"></script>
@endsection
