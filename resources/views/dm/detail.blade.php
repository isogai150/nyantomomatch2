@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dm/detail.css') }}">
@endsection

@section('content')
<div class="dm-wrapper">
  {{-- ======= ヘッダー部分 ======= --}}
  <div class="dm-header">
    <div class="back-page">
      {{-- herfの中身は投稿一覧ページのルーティング名が入る --}}
      <a href="#">＜　戻る</a>
    </div>
    <div class="dm-user-info">
      <div class="dm-user-icon"></div>
      <div class="dm-user-name">{{ $partner->name ?? '相手のユーザー' }}</div>
    </div>
  </div>

  {{-- ======= メッセージ一覧 ======= --}}
  <div id="dm-messages" class="dm-messages">
    @foreach($messages as $message)
      <div class="dm-message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}">
        <div class="dm-text">{{ $message->content }}</div>
        <div class="dm-time">{{ $message->created_at->format('Y/m/d H:i') }}</div>
      </div>
    @endforeach
  </div>

  {{-- ======= メッセージ送信フォーム ======= --}}
  <form id="dm-form" class="dm-form" autocomplete="off">
    @csrf
    <textarea id="message-input" name="message" placeholder="メッセージを入力..." required></textarea>
    <button type="submit" id="send-btn">送信</button>
  </form>
</div>
@endsection

@section('script')
{{-- ============================
     JSへLaravel変数を安全に渡す
     ============================ --}}
<script>
  window.dmConfig = {
    fetchUrl: "{{ route('dm.message.fetch', $dm->id) }}",  // メッセージ取得URL
    sendUrl: "{{ route('dm.message.send', $dm->id) }}",    // メッセージ送信URL
    csrfToken: "{{ csrf_token() }}",                       // CSRFトークン
    authId: {{ auth()->id() }}                             // ログインユーザーID
  };
</script>

{{-- 外部JSの読み込み --}}
<script src="{{ asset('js/dm/detail.js') }}"></script>
@endsection
