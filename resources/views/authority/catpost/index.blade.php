@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="main-content">
{{-- ここの中にコードを書く --}}

{{-- ============================================================================ --}}
{{-- ============================================================================ --}}

<div class="container py-4">
    <!-- ヘッダーと新規投稿ボタン -->
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">投稿管理(h2)</h2>
        <button class="btn new-post-btn">
            <i class="fas fa-plus"></i> 新しい投稿
        </button>
    </header>

    <h3 class="section-subtitle mb-4">あなたが投稿した里親募集の一覧です(h3)</h3>

    <!-- サマリーカードセクション -->
    <section class="row mb-5 summary-cards-container">
        <!-- 総投稿数カード -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-center p-3">
                <h3 class="summary-count">3(h3)</h3>
                <p class="summary-label text-muted">総投稿数</p>
            </div>
        </div>
        <!-- 譲渡完了カード -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card summary-card text-center p-3">
                <h3 class="summary-count">3(h3)</h3>
                <p class="summary-label text-muted">譲渡完了</p>
            </div>
        </div>
        <!-- 募集中カード -->
        <div class="col-md-4 col-sm-12 mb-3">
            <div class="card summary-card summary-recruiting text-center p-3">
                <h3 class="summary-count">3(h3)</h3>
                <p class="summary-label text-muted">募集中</p>
            </div>
        </div>
    </section>

    <!-- 投稿カードリストセクション -->
    <section class="row post-list">
        <!-- 投稿カード (サンプル 1) -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card post-card shadow-sm border-0">
                <div class="card-image"></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h3 class="post-title card-title m-0">タイトル(h3)</h3>
                        <button class="btn btn-sm delete-button">削除</button>
                    </div>
                    <div class="post-meta small text-secondary mb-2">
                        <span class="meta-item me-3">2歳</span>
                        <span class="meta-item me-3">メス</span>
                        <span class="meta-item me-3">東京都</span>
                        <span class="meta-item heart-count"><i class="fas fa-heart"></i> 5</span>
                    </div>
                    <p class="post-date text-muted mb-4">投稿開始日: 2024年1月15日</p>
                    <div class="d-flex card-actions">
                        <a href="#" class="btn btn-outline-primary detail-button flex-fill me-2">詳細</a>
                        <a href="#" class="btn edit-button flex-fill">編集</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 投稿カード (サンプル 2) -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card post-card shadow-sm border-0">
                <div class="card-image"></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h3 class="post-title card-title m-0">タイトル(h3)</h3>
                        <button class="btn btn-sm delete-button">削除</button>
                    </div>
                    <div class="post-meta small text-secondary mb-2">
                        <span class="meta-item me-3">2歳</span>
                        <span class="meta-item me-3">メス</span>
                        <span class="meta-item me-3">東京都</span>
                        <span class="meta-item heart-count"><i class="fas fa-heart"></i> 5</span>
                    </div>
                    <p class="post-date text-muted mb-4">投稿開始日: 2024年1月15日</p>
                    <div class="d-flex card-actions">
                        <a href="#" class="btn btn-outline-primary detail-button flex-fill me-2">詳細</a>
                        <a href="#" class="btn edit-button flex-fill">編集</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 投稿カード (サンプル 3) -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card post-card shadow-sm border-0">
                <div class="card-image"></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h3 class="post-title card-title m-0">タイトル(h3)</h3>
                        <button class="btn btn-sm delete-button">削除</button>
                    </div>
                    <div class="post-meta small text-secondary mb-2">
                        <span class="meta-item me-3">2歳</span>
                        <span class="meta-item me-3">メス</span>
                        <span class="meta-item me-3">東京都</span>
                        <span class="meta-item heart-count"><i class="fas fa-heart"></i> 5</span>
                    </div>
                    <p class="post-date text-muted mb-4">投稿開始日: 2024年1月15日</p>
                    <div class="d-flex card-actions">
                        <a href="#" class="btn btn-outline-primary detail-button flex-fill me-2">詳細</a>
                        <a href="#" class="btn edit-button flex-fill">編集</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- ============================================================================ --}}
{{-- ============================================================================ --}}

</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection