@extends('adminlte::page')

@section('title', '投稿通報一覧ページ')

@section('content_header')
    <h1>投稿通報一覧ページ</h1>
@stop

@section('content')
{{-- ============================================================== --}}
<div class="main-content">
    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- ======= ヘッダー ======= --}}
    <div class="list-header">
        <ul>
            <li>通報者</li>
            <li>投稿タイトル</li>
            <li>ステータス</li>
            <li>詳細</li>
            <li>アクション</li>
        </ul>
    </div>

    {{-- ======= データ行 ======= --}}
    @forelse($reports as $report)
        <list-item>
            <ul>
                {{-- 通報者 --}}
                <li>
                    <p>通報者：{{ $report->user->name ?? '不明' }}</p>
                </li>

                {{-- 投稿タイトル --}}
                <li>
                    <p>タイトル：{{ $report->post->title ?? '削除済み' }}</p>
                </li>

                {{-- ステータス --}}
                <li>
                    @if ($report->status == 0)
                        <span class="status-pending">対応待ち</span>
                    @elseif($report->status == 1)
                        <span class="status-approved">対応済み</span>
                    @elseif($report->status == 2)
                        <span class="status-rejected">却下</span>
                    @endif
                </li>

                {{-- 詳細表示 --}}
                <li>
                    <a href="{{ route('admin.post.report.detail', $report->id) }}" class="discription-btn">詳細表示</a>
                </li>

                {{-- アクションボタン --}}
                <li class="action-btn">
                    @if ($report->status == 0)
                        <div class="ok-btn">
                            <form action="{{ route('admin.post.report.resolve', $report->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('対応済みにしますか？')">対応済みにする</button>
                            </form>
                        </div>
                        <div class="no-btn">
                            <form action="{{ route('admin.post.report.reject', $report->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" onclick="return confirm('却下しますか？')">却下する</button>
                            </form>
                        </div>
                    @else
                        <span style="color: #999;">処理済み</span>
                    @endif
                </li>
            </ul>
        </list-item>
    @empty
        <list-item>
            <ul>
                <li colspan="5" style="text-align: center; padding: 2rem; color: #999;">
                    通報データがありません
                </li>
            </ul>
        </list-item>
    @endforelse
</div>

{{-- ======= ページネーション ======= --}}
<div class="paginate-box">
    {{ $reports->links() }}
</div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/report/post/index.css') }}">
@stop
