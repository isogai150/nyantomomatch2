@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dm/index.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

    <div class="pege-ttl">
        <h2>メッセージ</h2>
        <div>
            <h3>里親希望者や投稿者とのやり取りを管理できます</h3>
        </div>
    </div>

    <div class="container">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $message)
                <li>{{ $message }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <!-- 検索バー -->
    <div class="search-container">
        <form action="{{ route('dm.index') }}" method="GET" class="search-form">
            <div class="search-input-wrapper">
                <svg class="search-icon" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="ユーザー名またはメッセージで検索..."
                    value="{{ request('search') }}"
                    autocomplete="off">
                @if(request('search'))
                    <button type="button" class="clear-button" data-clear-url="{{ route('dm.index') }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- 検索結果の表示 -->
    @if(request('search'))
        <div class="search-result-info">
            「{{ request('search') }}」の検索結果: {{ count($conversationUsers) }}件
        </div>
    @endif

        <div class="message-list">
            @forelse($conversationUsers as $conversationData)
                <a href="{{ route('dm.show', ['dm' => $conversationData['pair_id']]) }}" class="message-item">
                    <!-- ユーザーアイコン -->
                    <div class="user-icon">
                        @if($conversationData['user']->image_path)
                            <img src="{{ asset('storage/profile_images/' . $conversationData['user']->image_path) }}"
                                alt="{{ $conversationData['user']->name }}"
                                class="user-avatar">
                        @else
                            <div class="user-avatar-placeholder">
                                <span>{{ mb_substr($conversationData['user']->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- メッセージ情報 -->
                    <div class="message-content">
                        <!-- ユーザー名 -->
                        <h3 class="user-name">{{ $conversationData['user']->name }}さん</h3>

                        <!-- 最後のメッセージ -->
                        <p class="last-message">{{ $conversationData['last_message'] }}</p>

                        <div class="message-meta">
                            <!-- 送受信時間 -->
                            <span class="time-ago">{{ $conversationData['time_ago'] }}</span>

                            <!-- メッセージ数 -->
                            <span class="message-count">
                                <svg class="message-icon" viewBox="0 0 24 24">
                                    <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                {{ $conversationData['message_count'] }}件
                            </span>
                        </div>
                    </div>

                    <!-- 矢印アイコン -->
                    <div class="arrow-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <svg class="empty-icon" viewBox="0 0 24 24">
                        <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    @if(request('search'))
                        <p class="empty-text">「{{ request('search') }}」に一致する結果が見つかりませんでした</p>
                    @else
                        <p class="empty-text">メッセージはまだありません</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/dm/index.js') }}"></script>
@endsection




