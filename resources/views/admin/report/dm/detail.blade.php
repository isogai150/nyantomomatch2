@extends('adminlte::page')

@section('title', 'DM通報詳細表示ページ')

@section('content_header')
    <h1>DM通報詳細表示ページ - ID：{{ $report->id }}</h1>
@stop

@section('content')
{{--ここにメインのコードを記述--}}
{{-- ============================================================== --}}

<div class="main-content">
  {{-- ヘッダー部分 --}}
  <div class="dm-header">
      <a href="{{ route('admin.report') }}" class="back-btn">
          <i class="fas fa-arrow-left"></i> 戻る
      </a>
  </div>

  <h3>通報者情報</h3>
  <div class="info-row">
    <span class="info-label">名前：</span>
    <span class="info-value">{{ $report->user->name }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">メールアドレス：</span>
    <span class="info-value">{{ $report->user->email }}</span>
  </div>

  <h3>被通報者情報</h3>
  @php
    // 通報されたメッセージの送信者を取得
    $reportedMessage = \App\Models\Message::find($report->message_id);
    $reportedUser = $reportedMessage ? $reportedMessage->user : null;
  @endphp

  @if($reportedUser)
    <div class="info-row">
      <span class="info-label">名前：</span>
      <span class="info-value">{{ $reportedUser->name }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">メールアドレス：</span>
      <span class="info-value">{{ $reportedUser->email }}</span>
    </div>
  @else
    <div class="info-row">
      <span class="info-value">情報が取得できません</span>
    </div>
  @endif

  <h3>通報情報</h3>
  <div class="info-row">
    <span class="info-label">ステータス：</span>
    <span class="info-value">
      @if($report->status == 0)
        対応待ち
      @elseif($report->status == 1)
        解決済み
      @elseif($report->status == 2)
        却下
      @endif
    </span>
  </div>
  <div class="info-row">
    <span class="info-label">通報日時：</span>
    <span class="info-value">{{ $report->created_at->format('Y/m/d H:i') }}</span>
  </div>

  <h3>メッセージ情報</h3>
  <div class="info-row">
    <span class="info-label">メッセージID：</span>
    <span class="info-value">{{ $report->message_id }}</span>
  </div>

  <h3>問題のメッセージ</h3>
  <div class="message-content">
    @if($reportedMessage)
      {{ $reportedMessage->content }}
    @else
      メッセージが削除されているか、取得できません。
    @endif
  </div>

@if($report->status == 0)
  <div class="button-container">
    <form action="{{ route('admin.user.ban', $reportedUser->id ?? 0) }}" method="post" style="display: inline;">
      @csrf
      @method('post')
      <button type="submit" class="ban-btn" onclick="return confirm('このユーザーをBANしますか?')">
        BAN
      </button>
    </form>

    @if($reportedMessage)
  @php
    // メッセージが属するDM（pair_id）を取得
    $dmId = $reportedMessage->pair_id;
  @endphp
  <form action="{{ route('admin.dm.message.delete', ['dm' => $dmId, 'message' => $reportedMessage->id]) }}" method="post" style="display: inline;">
    @csrf
    @method('delete')
    <button type="submit" class="delete-btn" onclick="return confirm('このメッセージを削除しますか?')">
      削除
    </button>
  </form>
@else
  <button type="button" class="delete-btn" disabled>
    削除(メッセージなし)
  </button>
@endif

  </div>
@else
  <p class="processed-text">処理済み</p>
@endif
</div>

{{-- ============================================================== --}}
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/report/dm/detail.css') }}">
@stop