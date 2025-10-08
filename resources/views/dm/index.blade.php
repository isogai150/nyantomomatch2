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

  {{-- 検索フォーム：※検索ボタンのままかエンターキーで検索を実行するか決める --}}
  {{-- <div class="message-search">
    <form action="{{ route('posts.index') }}" method="GET" class="flex">
      <input type="text" class="search-input" name="search" value="{{ request('search') }}" placeholder="メッセージを検索">
      <button type="submit" class="btn search-btn">検索</button>
    </form>
  </div> --}}

  {{-- DM一覧表示 --}}
  <div class="container">
      <div class="message-list">
          @forelse($conversationUsers as $conversationData)
              <a href="{{ route('dm.show', $conversationData['pair_id']) }}" class="message-item">
                  <!-- ユーザーアイコン -->
                  <div class="user-icon">
                      @if($conversationData['user']->image_path)
                          <img src="{{ asset('storage/' . $conversationData['user']->image_path) }}" 
                                alt="{{ $conversationData['user']->name }}"
                                class="user-avatar">
                      @else
                          <div class="user-avatar-placeholder">
                              <span>{{ mb_substr($conversationData['user']->name, 0, 1) }}</span>
                          </div>
                      @endif
                  </div>
                  {{-- デバッグ用にBladeに追加 --}}
                  {{-- {{ dd($conversationData['user']->image_path) }} --}}

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
                  <p class="empty-text">メッセージはまだありません</p>
              </div>
          @endforelse
      </div>
  </div>
</div>{{-- main-content  end --}}
@endsection

{{-- @section('script')
<script>
</script>
@endsection --}}