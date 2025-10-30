@extends('adminlte::page')

@section('title', '投稿通報一覧')

@section('content_header')
    <h1 class="page-title"><i class="fas fa-flag"></i> 投稿通報一覧</h1>
@stop

@section('content')
<div class="admin-box">

    {{-- ======= フラッシュメッセージ ======= --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- ======= 通報一覧テーブル ======= --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered report-table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>投稿タイトル</th>
                    <th>通報者</th>
                    <th>ステータス</th>
                    <th>通報日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->post->title ?? '削除済み' }}</td>
                        <td>{{ $report->user->name ?? '不明' }}</td>
                        <td>
                            @if($report->status === 0)
                                <span class="badge bg-warning text-dark">未対応</span>
                            @elseif($report->status === 1)
                                <span class="badge bg-success">対応済み</span>
                            @else
                                <span class="badge bg-secondary">却下</span>
                            @endif
                        </td>
                        <td>{{ $report->created_at->format('Y年n月j日 H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.post.report.detail', $report->id) }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="fas fa-search"></i> 詳細
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted text-center py-4">通報データはありません。</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======= ページネーション ======= --}}
    <div class="paginate-box">
        {{ $reports->links() }}
    </div>

</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/report/post/index.css') }}">
@stop
