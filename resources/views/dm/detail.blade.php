@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dm/show.css') }}">
@endsection

@section('content')
<div class="dm-wrapper">
  <div class="dm-header">
    <div class="dm-user-info">
      <div class="dm-user-icon"></div>
      <div class="dm-user-name">{{ $partner->name ?? '相手の名前' }}</div>
    </div>
  </div>

  <div id="dm-messages" class="dm-messages">
    @foreach($messages as $message)
      <div class="dm-message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}">
        <div class="dm-text">{{ $message->content }}</div>
        <div class="dm-time">{{ $message->created_at->format('Y/m/d H:i') }}</div>
      </div>
    @endforeach
  </div>

  <form id="dm-form" class="dm-form">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $partner->id }}">
    <textarea id="message-input" name="message" placeholder="メッセージを入力..." required></textarea>
    <button type="submit" id="send-btn">送信</button>
  </form>
</div>
@endsection

@section('script')
@endsection
