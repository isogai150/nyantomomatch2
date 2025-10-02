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

        <div class="post-searchs">
            <div class="post-search">
                <h2>里親募集中の猫たち</h2>
                <form action="#" method="GET">
                    <input type="text" class="text" name="search" value="{{ request('search') }}" placeholder="キーワード検索">
                    <input type="submit" class="submit" value="検索">
                </form>
            </div>

            <div class="post-sort">
                <form action="#" method="GET">
                    <select name="sort" onchange="this.form.submit()">
                        <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>新しい順</option>
                        <option value="old" {{ request('sort') == 'old' ? 'selected' : '' }}>古い順</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>人気順</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="main-center">
        <div class="catpost-grid">
            @foreach ($catposts as $catpost)
            <div class="catpost-card">
                <div class="post-image">
                    <img src="{{ asset('storage/' . $catpost->image) }}" alt="投稿画像">
                    <button class="favorite-btn {{ $catpost->is_favorite ? 'active' : '' }}">
                        ❤️
                    </button>
                </div>
                <div class="post-information">
                    <div class="post-information-top">
                        <h3>{{ $catpost->title }}</h3>
                        <span class="status">{{ $catpost->status }}</span>
                    </div>
                    <div class="post-information-center">
                        <ul>
                            <li>{{ $catpost->age }}</li>
                            <li>{{ $catpost->gender }}</li>
                            <li>{{ $catpost->region }}</li>
                        </ul>
                    </div>
                    @if (Auth::check())
                        <a href="#" class="detail-btn">詳細を見る</a>
                    @else
                        <button class="detail-btn modal-open" data-post="{{ $catpost->id }}">詳細を見る</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">×</span>
            <h2>ログインまたは<br>新規登録してください</h2>
            <a href="{{ route('login') }}" class="btn">ログイン</a>
            <a href="{{ route('register') }}" class="btn">新規登録</a>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/home/index.js') }}"></script>
@endsection
