@extends('adminlte::page')

@section('title', '投稿通報詳細表示ページ')

@section('content_header')
    <h1>投稿通報詳細表示ページ - ID：{{ $report->id }}</h1>
@stop

@section('content')
{{-- ============================================================== --}}
<div class="main-content">

    {{-- ======= 戻るボタン ======= --}}
    <div class="dm-header">
        <a href="{{ route('admin.post.reports') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> 戻る
        </a>
    </div>

    {{-- ======= フラッシュメッセージ ======= --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- ======= 通報情報 ======= --}}
    <h3>通報情報</h3>
    <div class="info-row">
        <span class="info-label">通報ID：</span>
        <span class="info-value">{{ $report->id }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">通報者：</span>
        <span class="info-value">{{ $report->user->name ?? '削除済み' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">通報日時：</span>
        <span class="info-value">{{ $report->created_at->format('Y年n月j日 H:i') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">ステータス：</span>
        <span class="info-value">
            @if ($report->status == 0)
                対応待ち
            @elseif($report->status == 1)
                解決済み
            @elseif($report->status == 2)
                却下
            @endif
        </span>
    </div>

    {{-- ======= 投稿情報 ======= --}}
    <h3>通報対象の投稿情報</h3>
    <div class="info-row">
        <span class="info-label">投稿タイトル：</span>
        <span class="info-value">{{ $report->post->title ?? '削除済み' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">投稿者：</span>
        <span class="info-value">{{ $report->post->user->name ?? '削除済み' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">投稿日時：</span>
        <span class="info-value">{{ $report->post->created_at->format('Y年n月j日 H:i') }}</span>
    </div>

    {{-- ======= 投稿内容 ======= --}}
    <h3>投稿内容</h3>
    <div class="message-content">
        {{-- 画像 --}}
        @foreach ($report->post->images as $image)
            <img src="{{ Storage::disk(config('filesystems.default'))->url('post_images/' . $image->image_path) }}"
                class="thumbnail" alt="サムネイル画像">
        @endforeach

        {{-- 動画 --}}
        @foreach ($report->post->videos as $video)
            <video class="thumbnail" muted controls>
                <source src="{{ Storage::disk(config('filesystems.default'))->url('post_videos/' . $video->video_path) }}" type="video/mp4">
            </video>
        @endforeach

        <p class="description-text">{{ $report->post->description ?? '内容が削除されています。' }}</p>
    </div>

    {{-- ======= 管理者操作 ======= --}}
    @if ($report->status == 0)
        <div class="button-container">
            {{-- 投稿者BAN / BAN解除 --}}
            @if ($report->post->user && $report->post->user->is_banned == 0)
                <form action="{{ route('admin.user.ban', $report->post->user->id) }}" method="POST"
                      onsubmit="return confirm('投稿者をBANしますか？');">
                    @csrf
                    <button type="submit" class="ban-btn">投稿者BAN</button>
                </form>
            @else
                <form action="{{ route('admin.user.unban', $report->post->user->id) }}" method="POST"
                      onsubmit="return confirm('BAN解除しますか？');">
                    @csrf
                    <button type="submit" class="delete-btn">BAN解除</button>
                </form>
            @endif

            {{-- 投稿削除 --}}
            <form action="{{ route('catpost.destroy', $report->post->id) }}" method="POST"
                  onsubmit="return confirm('本当に投稿を削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="ok-btn">投稿削除</button>
            </form>
        </div>
    @else
        <p class="processed-text">処理済み</p>
    @endif
</div>
{{-- ============================================================== --}}
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/report/post/detail.css') }}">
@stop
