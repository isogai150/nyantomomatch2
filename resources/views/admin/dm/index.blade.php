@extends('adminlte::page')

@section('title', 'DM一覧ページ')

@section('content_header')
    <h1>DM一覧ページ</h1>
@stop

@section('content')
{{-- ============================================================== --}}
<div class="main-content">
    <div class="list-header">
        <ul>
            <li>ユーザー</li>
            <li>DMタイトル</li>
            <li>最終送信日</li>
            <li>詳細</li>
        </ul>
    </div>

    <list-item>
        @foreach ($dms as $dm)
            <ul>
                {{-- ======= ユーザー ======= --}}
                <li class="user-info">
                    {{-- userA --}}
                    <img src="@if ($dm->userA->image_path)
                        {{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $dm->userA->image_path) }}
                    @else
                        {{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}
                    @endif"
                    alt="{{ $dm->userA->name }}" class="user-icon">

                    <p>{{ $dm->userA->name }}</p>

                    <p class="dot">・</p>

                    {{-- userB --}}
                    <img src="@if ($dm->userB->image_path)
                        {{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $dm->userB->image_path) }}
                    @else
                        {{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}
                    @endif"
                    alt="{{ $dm->userB->name }}" class="user-icon">

                    <p>{{ $dm->userB->name }}</p>
                </li>

                {{-- ======= DMタイトル ======= --}}
                <li>
                    <p>{{ $dm->userA->name }}・{{ $dm->userB->name }} のDM</p>
                </li>

                {{-- ======= 最終送信日 ======= --}}
                <li>
                    <p>{{ $dm->created_at->format('Y年n月j日 H:i') }}</p>
                </li>

                {{-- ======= 詳細ボタン ======= --}}
                <li>
                    <a href="{{ route('admin.dm.detail', $dm->id) }}" class="description-btn">詳細表示</a>
                </li>
            </ul>
        @endforeach
    </list-item>
</div>
{{-- ============================================================== --}}
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/dm/index.css') }}">
@stop
