@extends('adminlte::page')

@section('title', 'DM通報一覧ページ')

@section('content_header')
    <h1>DM通報一覧ページ</h1>
@stop

@section('content')
    {{-- ここにメインのコードを記述 --}}
    {{-- ============================================================== --}}
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ヘッダー --}}
        <div class="list-header">
            <ul>
                <li>通報者</li>
                <li>メッセージ</li>
                <li>ステータス</li>
                <li>詳細</li>
                <li>アクション</li>
            </ul>
        </div>

        {{-- データ行 --}}
        @forelse($reports as $report)
            <list-item>
                <ul>
                    <li>
                        <p>通報者：{{ $report->user->name ?? '不明' }}</p>
                    </li>
                    <li>
                        <p>メッセージ：{{ $report->content ?? '通報がありません' }}
                        </p>
                    </li>


                    {{-- ステータス --}}
                    <li>
                        @if ($report->status == 0)
                            <span class="status-pending">対応待ち</span>
                        @elseif($report->status == 1)
                            <span class="status-approved">解決済み</span>
                        @elseif($report->status == 2)
                            <span class="status-rejected">却下</span>
                        @endif
                    </li>

                    {{-- 詳細表示 --}}
                    <li>
                        <a href="{{ route('admin.report.detail', $report->id) }}" class="discription-btn">詳細表示</a>
                    </li>

                    {{-- アクションボタン --}}
                    <li class="action-btn">
                        @if ($report->status == 0)
                            <div class="ok-btn">
                                <form action="{{ route('admin.report.resolve', $report->id) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" onclick="return confirm('解決済みにしますか？')">対応済み</button>
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
