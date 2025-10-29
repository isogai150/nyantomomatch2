@extends('adminlte::page')

@section('title', 'ユーザー詳細')

@section('content_header')
    <h1>ユーザー詳細</h1>
@stop

@section('content')
<div class="main-content">

    {{-- 戻るボタン --}}
    <a href="{{ route('admin.users') }}">＜ 戻る</a>

    <div class="detail-wrapper">

        {{-- 左：ユーザー基礎情報 --}}
        <div class="user-info">
            <div class="user-image">
                <img src="{{ $user->image_path 
                    ? Storage::url('profile_images/' . $user->image_path) 
                    : asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" 
                    alt="プロフィール画像">
            </div>

            <p><strong>名前：</strong>{{ $user->name }}</p>
            <p><strong>メールアドレス：</strong>{{ $user->email }}</p>
            <p><strong>アカウント作成日：</strong>{{ $user->created_at->format('Y/m/d H:i') }}</p>

            <p>
                <strong>権限：</strong>
                {{ $user->role === 1 ? '投稿権限ユーザー' : '一般ユーザー' }}
            </p>

            <p>
                <strong>ステータス：</strong>
                @if ($user->is_banned)
                    <span class="status-banned">BAN</span>
                @else
                    <span class="status-active">通常</span>
                @endif
            </p>

            {{-- BAN / 解除ボタン --}}
            <form action="{{ route($user->is_banned ? 'admin.user.unban' : 'admin.user.ban', $user->id) }}"
                method="POST">
                @csrf
                <button class="ban-btn {{ $user->is_banned ? 'unban' : 'ban' }}">
                    {{ $user->is_banned ? 'BAN解除' : 'BANする' }}
                </button>
            </form>
        </div>

        {{-- 右：活動統計 --}}
        <div class="user-stats">
            <h3>活動統計</h3>

            <div class="stats-box">
                <p><strong>投稿数：</strong>{{ $postCount }}</p>
                <p><strong>メッセージ数：</strong>{{ $messageCount }}</p>
                <p><strong>通報された数：</strong>{{ $reportCount }}</p>
            </div>
        </div>

    </div>

</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/user/detail.css') }}">
@endsection
