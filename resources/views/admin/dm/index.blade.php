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
                          @if($dm->userA->image_path)
                            <img src="{{ asset('storage/profile_images/' . $dm->userA->image_path) }}" alt="{{ $dm->userA->name }}">
                            {{-- <img src="{{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $dm->userA->image_path) }}" alt="{{ mb_substr($dm->userA->name) }}"> --}}
                            @else
                            <div class="user-avatar-placeholder">
                                <span>{{ mb_substr($dm->userA->name, 0, 1) }}</span>
                            </div>
                          @endif
                        </li>
                        <p class="dot">・</p>
                        <li class="icon">
                          @if($dm->userB->image_path)
                            <img src="{{ asset('storage/profile_images/' . $dm->userB->image_path) }}" alt="{{ $dm->userB->name }}">
                            {{-- <img src="{{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $dm->userB->image_path) }}" alt="{{ mb_substr($dm->userB->name) }}"> --}}
                          @else
                            <div class="user-avatar-placeholder">
                                <span>{{ mb_substr($dm->userB->name, 0, 1) }}</span>
                            </div>
                          @endif
                        </li>
                      </ul>
                    </li>

                    <li>
                      {{-- ユーザー名（A・B）の表示 --}}
                        <h3>{{ $dm->userA->name }}・{{ $dm->userB->name }}<br class="br-sp">のDM</h3>
                    </li>

                    <li>
                      {{-- メッセージの最終送信日の表示 --}}
                        <span class="text">送信日</span><br class="br-sp">{{ $dm->created_at->format('Y年n月j日 H:i') }}
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