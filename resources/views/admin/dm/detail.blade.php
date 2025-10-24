@extends('adminlte::page')

@section('title', 'DM詳細表示ページ')

@section('content_header')
    <h1>DM詳細表示ページ</h1>
@stop

@section('content')
{{--ここにメインのコードを記述--}}
{{-- ============================================================== --}}
<div class="main-content">
    {{-- ヘッダー部分 --}}
    <div class="dm-header">
        <a href="{{ route('admin.dm') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> 戻る
        </a>
        <h2 class="dm-title">
            {{ $dm->userA->name }} ・ {{ $dm->userB->name }} のDM
        </h2>
    </div>

    {{-- メッセージ表示エリア --}}
    <div class="message-container">
        {{-- メッセージがない場合を考慮してforelseを使用 --}}
        @forelse($messages as $message)
            @php
                // メッセージの送信者がユーザーAかBかを判定
                    // 送信者とユーザーのID(A)が一致するか判定している
                    $isUserA = ($message->user_id === $dm->userA_id);
                    // 三項演算子を使用してメッセージの送信者を判断している
                    // true:送信者はuserA
                    // false:送信者はuserB
                    // これで、メッセージごとの送信者のユーザー情報を引き出している
                    $sender = $isUserA ? $dm->userA : $dm->userB;
            @endphp

            {{-- 三項演算子：$isUserAがtrue:メッセージ全体を左寄せ、false:右寄せ--}}
            <div class="message-row {{ $isUserA ? 'message-left' : 'message-right' }}">
                @if($isUserA)
                    {{-- ユーザーAのメッセージ（左側） --}}
                    <div class="user-icon">
                        @if($sender->icon_path)
                            <img src="{{ $sender->icon_path }}" alt="{{ $sender->name }}のアイコン">
                        @else
                            <div class="default-icon" style="background-color: #FFB6C1;"></div>
                        @endif
                    </div>
                @endif

                {{-- $isUserAによってCSSの配置が調整される --}}
                <div class="message-content">
                    <div class="message-bubble {{ $isUserA ? 'bubble-left' : 'bubble-right' }}">
                        {{-- コメント取得 --}}
                        {{ $message->content }}
                    </div>
                    <div class="message-time {{ $isUserA ? 'time-left' : 'time-right' }}">
                        {{-- 送信日取得 --}}
                        {{ $message->created_at->format('Y/m/d H:i') }}
                    </div>
                </div>

                @if(!$isUserA)
                    {{-- ユーザーBのメッセージ（右側） --}}
                    <div class="user-icon">
                        @if($sender->icon_path)
                            <img src="{{ $sender->icon_path }}" alt="{{ $sender->name }}のアイコン">
                        @else
                            <div class="default-icon" style="background-color: #FFFFFF;"></div>
                        @endif
                    </div>
                @endif
            </div>

        {{-- DMのメッセージが空の場合、処理がされる --}}
        @empty
            <div class="no-messages">
                <p>メッセージがありません</p>
            </div>
        @endforelse
    </div>
</div>
@stop
{{-- ============================================================== --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/dm/detail.css') }}">
@stop
