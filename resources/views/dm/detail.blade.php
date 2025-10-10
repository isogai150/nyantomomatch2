@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dm/detail.css') }}">
@endsection

@section('content')
    <div class="dm-wrapper">

        {{-- ======= 戻るリンク・相手ユーザー情報 ======= --}}
        <div class="dm-header">
            <div class="back-page">
                <a href="{{ route('dm.index') }}">＜　戻る</a>
            </div>

            <div class="dm-user-info">
                <div class="dm-user-icon">
                    {{-- 投稿者のプロフィール画像 --}}
                    @if (!empty($partner->image_path))
                        <img src="{{ asset(str_replace('public/', '', $post->user->image_path)) }}" alt="投稿者のプロフィール画像"
                            class="user-image">
                    @else
                        <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image"
                            class="user-image">
                    @endif
                </div>
                {{-- {{ 値があればその値を表示させる ?? 値がなければ（NULLの場合）表示させるテキスト }} --}}
                <div class="dm-user-name">{{ $partner->name ?? '相手のユーザー' }}</div>
            </div>
        </div>

        {{-- ======= 投稿情報（この猫について） ======= --}}
        {{-- @if (変数名)の書き方は変数がtureの際に表示、つまり変数に値が入っていれば表示される --}}
        @if ($post)
            <div class="dm-post-info">
                <div class="dm-post-img-area">
                    @php
                        $firstImage = optional($post->images->first())->image_path; // post_images.image_path
                        $imagePath = $firstImage ? str_replace('public/', '', $firstImage) : null;
                    @endphp

                    @if ($imagePath)
                        {{-- Seeder（public/images/seeder/...）用 --}}
                        <img src="{{ asset($imagePath) }}" alt="猫の写真" class="dm-post-img">
                        {{-- 本番で storage に移すなら下に切替（storage:link 済前提） --}}
                        {{-- <img src="{{ asset('storage/' . $imagePath) }}" alt="猫の写真" class="dm-post-img"> --}}
                    @else
                        <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image"
                            class="dm-post-img">
                    @endif
                </div>

                <div class="dm-post-text-area">
                    <h3 class="dm-post-title">{{ $post->title }}</h3>
                    <p class="dm-post-desc">この投稿についてのやり取りです。</p>
                    <div class="dm-post-buttons">
                        <a href="{{ route('posts.detail', $post->id) }}" class="btn-detail">詳細を見る</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- ======= メッセージ一覧 ======= --}}
        <div id="dm-messages" class="dm-messages">
            @foreach ($messages as $message)
                {{-- 自分のメッセージか相手のメッセージかを判別するコード --}}
                <div class="dm-message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}">
                    {{-- メッセージ本文 --}}
                    <div class="dm-text">{{ $message->content }}</div>
                    {{-- メッセージの送信時間 --}}
                    <div class="dm-time">{{ $message->created_at->format('Y年n月j日 H:i') }}</div>
                </div>
            @endforeach
        </div>

        {{-- ======= メッセージ送信フォーム ======= --}}
        <form id="dm-form" class="dm-form" method="POST" autocomplete="off">
            @csrf
            <textarea id="message-input" name="message" placeholder="メッセージを入力..." required></textarea>
            <button type="submit" id="send-btn">送信</button>
        </form>
    </div>
@endsection

@section('script')
    {{-- LaravelからJavaScriptへ値を渡す（カスタムデータ属性を使用、グローバル変数を使用しないようにするため）--}}
    {{-- jsがHTMLを確実に読み込んだ後に動くようにここに配置しました --}}
    <div id="dm-config"
        data-fetch-url="{{ route('dm.message.fetch', $dm->id) }}"
        data-send-url="{{ route('dm.message.send', $dm->id) }}"
        data-csrf-token="{{ csrf_token() }}"
        data-auth-id="{{ auth()->id() }}">
    </div>

    {{-- JSファイル読み込み --}}
    <script src="{{ asset('js/dm/detail.js') }}"></script>
@endsection
