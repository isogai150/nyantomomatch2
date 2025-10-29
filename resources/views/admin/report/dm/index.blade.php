@extends('adminlte::page')

@section('title', 'DM詳細表示ページ')

@section('content_header')
    <h1>DM詳細表示ページ</h1>
@stop

@section('content')
{{--ここにメインのコードを記述--}}
{{-- ============================================================== --}}
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ヘッダー --}}
    <div class="list-header">
        <ul>
            <li>メッセージID / 投稿日 / 投稿者</li>
            <li>ステータス</li>
            <li>アクション</li>
            <li>詳細</li>
        </ul>
    </div>

    {{-- データ行 --}}
    @forelse($reports as $report)
        <list-item>
            <ul>
                {{-- メッセージID / 投稿日 / 投稿者 --}}
                <li>
                    <h3>メッセージID：{{ $report->message_id }}</h3>
                    <p class="text">投稿日：{{ $report->created_at->format('Y-m-d') }}</p>
                    <p>投稿者：{{ $report->user->name }}（{{ $report->user->email }}）</p>
                </li>

                {{-- ステータス --}}
                <li>
                    @if($report->status == 0)
                        <span class="status-pending">対応待ち</span>
                    @elseif($report->status == 1)
                        <span class="status-approved">解決済み</span>
                    @elseif($report->status == 2)
                        <span class="status-rejected">却下</span>
                    @endif
                </li>

                {{-- アクションボタン --}}
                <li class="action-btn">
                    @if($report->status == 0)
                        <div class="ok-btn">
                            <form action="{{ route('admin.report.resolve', $report->id) }}" method="POST">
                                @csrf
                                @method('POST')
                                <button type="submit" onclick="return confirm('解決済みにしますか？')">解決済み</button>
                            </form>
                        </div>
                        <div class="no-btn">
                            <form action="{{ route('admin.report.reject', $report->id) }}" method="POST">
                                @csrf
                                @method('POST')
                                <button type="submit" onclick="return confirm('却下しますか？')">却下</button>
                            </form>
                        </div>
                    @else
                        <span style="color: #999;">処理済み</span>
                    @endif
                </li>

                {{-- 詳細表示 --}}
                <li>
                    <a href="{{ route('admin.report.detail', $report->id) }}" class="discription-btn">詳細表示</a>
                </li>
            </ul>
        </list-item>
    @empty
        <list-item>
            <ul>
                <li colspan="4" style="text-align: center; padding: 2rem; color: #999;">
                    通報データがありません
                </li>
            </ul>
        </list-item>
    @endforelse
</div>
@stop

{{-- ============================================================== --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/report/dm/index.css') }}">
@stop
