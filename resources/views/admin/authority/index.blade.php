@extends('adminlte::page')

@section('title', '投稿権限の申請一覧')

@section('content_header')
    <h1>投稿権限の申請一覧</h1>
@stop

@section('content')
    <div class="main-content">
        <div class="list-header">
            <ul>
                <li>申請者</li>
                <li>申請日</li>
                <li>ステータス</li>
                <li>詳細</li>
                <li>アクション</li>
            </ul>
        </div>
        <list-item>
            @foreach ($authoritys as $authority)
                <ul>
                    <li>
                        <h3>{{ $authority->user->name }}</h3>
                    </li>
                    <li>{{ $authority->created_at }}</li>
                    <li>{{ $authority->status_label }}</li>
                    <li>
                        <a href="{{ route('admin.authority.detail', $authority->id) }}" class="discription-btn">詳細表示</a>
                    </li>
                    <li>
                        <ul class="action-btn">
                            <li>
                                <form action="{{ route('admin.authority.approval', $authority->id) }}" method="POST" class="ok-btn" id="approval-btn">
                                  @method('PUT')
                                    @csrf
                                    <button>承認</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.authority.cancel', $authority->id) }}" method="POST" class="no-btn" id="delete-btn">
                                    @method('DELETE')
                                    @csrf
                                    <button>却下</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endforeach
        </list-item>
    </div>

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/authority/index.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/admin/authority/index.js') }}"></script>
@stop
@stop
