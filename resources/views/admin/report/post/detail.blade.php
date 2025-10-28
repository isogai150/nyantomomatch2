@extends('adminlte::page')

@section('title', '通報詳細')

@section('content_header')
    <h1>投稿通報詳細</h1>
@stop

@section('content')

<div class="report-detail-container">

    <div class="back-area">
        <a href="{{ route('admin.post.reports') }}" class="btn btn-secondary btn-sm">＜ 一覧に戻る</a>
    </div>

    {{-- 成功メッセージ --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    {{-- 警告メッセージ --}}
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif


    <div class="report-box">

        <h3>通報情報</h3>
        <table class="table table-bordered">
            <tr>
                <th>通報ID</th>
                <td>{{ $report->id }}</td>
            </tr>
            <tr>
                <th>通報者</th>
                <td>{{ $report->user->name ?? '削除済み' }}</td>
            </tr>
            <tr>
                <th>通報日時</th>
                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <th>ステータス</th>
                <td>
                    @if($report->status === 0)
                        <span class="badge bg-warning">未対応</span>
                    @elseif($report->status === 1)
                        <span class="badge bg-success">対応済み</span>
                    @else
                        <span class="badge bg-secondary">却下</span>
                    @endif
                </td>
            </tr>
        </table>

        <h3 class="mt-4">通報対象の投稿情報</h3>
        <table class="table table-bordered">
            <tr>
                <th>投稿タイトル</th>
                <td>{{ $report->post->title ?? '削除済み' }}</td>
            </tr>
            <tr>
                <th>投稿者</th>
                <td>{{ $report->post->user->name ?? '削除済み' }}</td>
            </tr>
            <tr>
                <th>投稿日時</th>
                <td>{{ $report->post->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        </table>

    </div>

    {{-- ステータス操作 --}}
    <div class="action-buttons">
        @if($report->status === 0)
            <form action="{{ route('admin.post.report.resolve', $report->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-success">対応済みにする</button>
            </form>

            <form action="{{ route('admin.post.report.reject', $report->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-danger">却下する</button>
            </form>
        @else
            <p class="text-muted">* すでに処理済みです</p>
        @endif
    </div>

</div>

@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/report/post/detail.css') }}">
@stop
