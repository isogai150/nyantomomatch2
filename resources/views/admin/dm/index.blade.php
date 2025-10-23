@extends('adminlte::page')

@section('title', 'DM一覧ページ')

@section('content_header')
    <h1>DM一覧ページ</h1>
@stop

@section('content')
{{--ここにメインのコードを記述--}}
{{-- ============================================================== --}}
    <div class="main-content">
        <div class="list-header">
            <ul>
                <li>ユーザーアイコン</li>
                <li>ユーザー名</li>
                <li>投稿日時</li>
                <li>詳細表示</li>
            </ul>
        </div>
        <list-item>
            @foreach ($dms as $dm)
                <ul>

                    <li>
                      <ul class="action-btn">
                        <li class="icon">
                          {{ $dm->userA->image_path }}
                        </li>
                        <p class="dot">・</p>
                        <li class="icon">
                          {{ $dm->userB->image_path }}
                        </li>
                      </ul>
                    </li>

                    <li>
                      {{-- ユーザー名（A・B）の表示 --}}
                        <h3>{{ $dm->userA->name }}・{{ $dm->userB->name }}<br>のDM</h3>
                    </li>

                    <li>
                      {{-- メッセージの最終送信日の表示 --}}
                        <span class="text">送信日</span><br>{{ $dm->created_at }}
                    </li>

                    <li>
                        <a href="{{ route('admin.dm.detail', $dm->id) }}" class="discription-btn">詳細表示</a>
                        {{-- <a href="#" class="discription-btn">詳細表示</a> --}}
                    </li>
                </ul>
            @endforeach

        </list-item>
    </div>
{{-- ============================================================== --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/dm/index.css') }}">
@stop
@stop