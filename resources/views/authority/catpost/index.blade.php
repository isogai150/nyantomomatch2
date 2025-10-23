@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/index.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">

<div class="main-content">

    <div class="titlefont">
        <h2>投稿管理</h2>
    </div>

    <div class="subtitle">
        <p>あなたが投稿した里親募集の一覧です</p>
    </div>

    <div class="newpost">
        <a href="{{ route('posts.create') }}">＋　新しい投稿</a>
    </div>


    {{-- 成功メッセージ表示 --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

{{-- ============================================================================ --}}
{{-- 総投稿数・譲渡完了・募集中 --}}
{{-- ============================================================================ --}}

    <div class="summary-boxes">
        <div class="summary-item">
            <h3>{{ $myCatposts->count() }}</h3>
                <p>総投稿数</p>
        </div>
        <div class="summary-item">
            <h3>{{ $myCatposts->where('status', '譲渡完了')->count() }}</h3>
                <p>譲渡完了</p>
        </div>
        <div class="summary-item">
            <h3>{{ $myCatposts->where('status', '募集中')->count() }}</h3>
                <p>募集中</p>
        </div>
    </div>

{{-- ============================================================== --}}
{{-- 自分の投稿一覧表示 --}}
{{-- ============================================================== --}}

    <div class="post-list">
    @forelse($myCatposts as $post)
        <div class="post-card">
        <div class="post-image">
            {{-- 投稿の画像 --}}
            @if($post->images->isNotEmpty())
            <img id="main-image" src="{{ asset(str_replace('public/', '', $post->images->first()->image_path)) }}">
            @else
            <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image"
                    class="no-image">
            @endif
        </div>

{{-- ============================================================== --}}
{{-- 一覧の詳細部分 --}}
{{-- ============================================================== --}}

        <div class="post-body">
            <div class="title">
            <h3>{{ $post->title }}</h3>
            </div>

            {{-- 性別の表示変換 --}}
            @php
            $gender = match($post->gender) {
                1 => 'オス',
                2 => 'メス',
                default => '未入力',
            };
            @endphp

            {{-- 年齢・性別・都道府県 --}}
            <p><br>{{ $post->age }}歳　{{ $gender }}　{{ $post->region }}</p>

            {{-- いいね数追加 --}}
            <p class="favorites"><span class="heart">❤</span>{{ $post->favorites_count ?? 0 }}</p>

{{-- =============================================================================================== --}}

        {{-- 里親募集中・お見合い中・譲渡成立 --}}
        <div class="post-information-top">
            <p class="{{ $post->status_class }}">{{ $post->status_label }}</p>
        </div>

{{-- =============================================================================================== --}}

            {{-- 投稿日 --}}
            <div class="post-info">
            <p><br>投稿日: {{ $post->created_at->format('Y年n月j日') }}</p>
            </div>
        </div>


        <div class="post-actions">
            <div class="action-top">
                {{-- 編集ボタン --}}
                <a href="{{ route('catpost.edit', $post->id) }}" class="btn-edit">編集</a>

                {{-- 削除ボタン --}}
                <form action="{{ route('catpost.destroy', $post->id) }}" method="POST" class="delete-form" onsubmit="return confirm('本当に削除してもよろしいですか？\n\nこの操作は取り消せません。');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">削除</button>
                </form>
            </div>

            {{-- 詳細ボタン --}}
            <a href="{{ route('posts.detail', $post->id) }}" class="btn-detail">詳細</a>
        </div>

{{-- =============================================================================================== --}}

        </div>
    @empty
        <p>投稿がありません。</p>
    @endforelse
    </div>
</div>

{{-- =============================================================================================== --}}

</div>
@endsection

@section('script')
{{-- 必要に応じてJavaScriptファイルを追加 --}}
@endsection