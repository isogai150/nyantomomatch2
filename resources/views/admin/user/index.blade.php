@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('content_header')
    <h1>ユーザー一覧</h1>
@stop

@section('content')
<div class="main-content">
    <div class="list-header">
        <ul>
            <li>ユーザー</li>
            <li>メールアドレス</li>
            <li>ステータス</li>
            <li>詳細</li>
            <li>アクション</li>
        </ul>
    </div>

    <list-item>
        @foreach ($users as $user)
        <ul>
            {{-- ユーザー画像 & 名前 --}}
            <li class="user-info">
                <img src="@if($user->image_path)
                    {{ Storage::disk(config('filesystems.default'))->url('profile_images/'.$user->image_path) }}
                @else
                    {{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}
                @endif"
                alt="ユーザーアイコン" class="user-icon">

                <p>{{ $user->name }}</p>
            </li>

            {{-- メールアドレス --}}
            <li>{{ $user->email }}</li>

            {{-- ステータス表示 --}}
            <li>
                @if ($user->is_banned)
                    <span class="status-banned">BAN</span>
                @else
                    <span class="status-active">通常</span>
                @endif
            </li>

            {{-- 詳細ページへ --}}
            <li>
                <a href="{{ route('admin.user.detail', $user->id) }}" class="description-btn">詳細</a>
            </li>

            {{-- BAN / BAN解除 --}}
            <li>
                @if ($user->is_banned)
                    <form action="{{ route('admin.user.unban', $user->id) }}" method="POST" class="ok-btn" onsubmit="return confirm('BAN解除しますか？');">
                        @csrf
                        <button>解除</button>
                    </form>
                @else
                    <form action="{{ route('admin.user.ban', $user->id) }}" method="POST" class="no-btn" onsubmit="return confirm('BANしますか？');">
                        @csrf
                        <button>BAN</button>
                    </form>
                @endif
            </li>

        </ul>
        @endforeach
    </list-item>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/user/index.css') }}">
@endsection
