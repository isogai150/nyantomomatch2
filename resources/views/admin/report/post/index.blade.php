@extends('adminlte::page')

@section('title', '投稿通報一覧')

@section('content_header')
    <h1>投稿通報一覧</h1>
@stop

@section('content')
<div class="report-container">

    {{-- 成功メッセージ --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    {{-- 警告メッセージ --}}
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <table class="table table-striped report-table">
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

            @foreach($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->post->title ?? '削除済み' }}</td>
                <td>{{ $report->user->name ?? '不明' }}</td>
                <td>
                    @if($report->status === 0)
                        <span class="badge bg-warning">未対応</span>
                    @elseif($report->status === 1)
                        <span class="badge bg-success">対応済み</span>
                    @else
                        <span class="badge bg-secondary">却下</span>
                    @endif
                </td>
                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.post.report.detail', $report->id) }}"
                       class="btn btn-sm btn-info">
                        詳細
                    </a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>

    <div class="paginate-box">
        {{ $reports->links() }}
    </div>

</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/report/post/index.css') }}">
@stop
