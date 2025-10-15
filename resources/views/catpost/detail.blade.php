@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/catpost/detail.css') }}">
@endsection

@section('content')
    <div class="post-detail-container">

        <div class="back">
            <a href="{{ route('posts.index') }}" class="back-page">＜　一覧へ戻る</a>
        </div>

        <div class="post-media">
            @if ($post->images->isNotEmpty())
                <img id="main-image" src="{{ asset(str_replace('public/', '', $post->images->first()->image_path)) }}"
                    alt="メイン画像" class="main-photo">
            @else
                <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image"
                    class="main-photo">
            @endif

            <div class="thumbnail-list">
                @foreach ($post->images as $image)
                    <img src="{{ asset(str_replace('public/', '', $image->image_path)) }}" class="thumbnail" alt="サムネイル画像">
                @endforeach

                @foreach ($post->videos as $video)
                    <video class="thumbnail" muted>
                        <source src="{{ asset(str_replace('public/', '', $video->video_path)) }}" type="video/mp4">
                    </video>
                @endforeach
            </div>
        </div>

        <div class="post-detail-content">

            <div class="post-main">
                <div class="post-main-top">
                    <div class="top-title">
                        <h1 class="post-title">{{ $post->title }}</h1>
                        <p class="{{ $post->status_class }}">{{ $post->status_label }}</p>
                    </div>
                    <div class="post-list">
                        <ul>
                            <li>年齢<br><span>{{ $post->unit_age }}</span></li>
                            <li>性別<br><span>{{ $post->gender_class }}</span></li>
                            <li>品種<br><span>{{ $post->breed }}</span></li>
                        </ul>
                    </div>
                </div>

                <div class="post-meta">
                    <div class="region-contents">
                        <p>所在地<br><span>{{ $post->region }}</span></p>
                    </div>
                    <div class="vaccination-contents">
                        <p>予防接種<br><span>{!! nl2br(e($post->vaccination ?? '未記入')) !!}</span></p>
                    </div>
                    <div class="medical-history-contents">
                        <p>病歴<br><span>{!! nl2br(e($post->medical_history ?? '未記入')) !!}</span></p>
                    </div>
                </div>

                <div class="post-description">
                    <p>詳細情報<br><span>{!! nl2br(e($post->description ?? '詳細情報がありません。')) !!}</span></p>
                </div>

                <div class="cost-contents">
                    <p>譲渡費用<br><span>{!! nl2br(e($post->cost_class ?? '未記入')) !!}</span></p>
                </div>
            </div>

            <aside class="post-sidebar">
                <div class="contact-box">
                    <h3>お問い合わせ</h3>
                    <a href="#" class="contact-btn">メッセージを送る</a>

                    <form action="{{ route('dm.create', ['post' => $post->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="post" value="{{ $post->id }}">
                        <button type="submit" class="contact-btn">メッセージを送る</button>
                    </form>
                    <form action="{{ route('favorites.toggle', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="favorite-btn">
                            {{ Auth::user()->favoritePosts->contains($post->id) ? '❤ お気に入り解除' : '♡ お気に入り追加' }}
                        </button>
                    </form>
                </div>

<div class="user-info">
    <h3>投稿者情報</h3>

    <div class="user-main">
        {{-- 投稿者のプロフィール画像 --}}
        @if (!empty($post->user->image_path))
            <img src="{{ asset(str_replace('public/', '', $post->user->image_path)) }}"
                alt="投稿者のプロフィール画像" class="user-image">
        @else
            <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}"
                alt="No Image" class="user-image">
        @endif

        <p class="user-name">{{ $post->user->name }}</p>
    </div>

    <p class="user-description">{{ $post->user->description ?? '未記入' }}</p>

    <div class="publication-info">
        <p>掲載開始日：{{ $post->start_date }}</p>
        @isset($post->end_date)
            <p>掲載終了日：{{ $post->end_date }}</p>
        @endisset
    </div>
</div>

            </aside>

        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/catposts/detail.js') }}"></script>
@endsection
