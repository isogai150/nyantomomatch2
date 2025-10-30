@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('content_header')
    <h1>譲渡成立一覧</h1>
@stop

@section('content')
    <div class="main-content">
        <div class="list-header">
            <ul>
                <li>ユーザー</li>
                <li>投稿タイトル</li>
                <li>成立日</li>
                <li>譲渡費</li>
            </ul>
        </div>

        <list-item>
            @foreach ($transfers as $transfer)
                <ul>
                    {{-- ユーザー画像 & 名前 --}}
                    <li class="user-info">
                        <img src="@if ($transfer->userA->image_path) {{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $transfer->userA->image_path) }}
                @else
                    {{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }} @endif"
                            alt="ユーザーアイコン" class="user-icon">

                        <p>{{ $transfer->userA->name }}</p>

                        <img src="@if ($transfer->userB->image_path) {{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $transfer->userB->image_path) }}
                @else
                    {{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }} @endif"
                            alt="ユーザーアイコン" class="user-icon">

                        <p>{{ $transfer->userB->name }}</p>
                    </li>

                    {{-- 投稿 --}}
                    <li class="user-info">
                        @if ($transfer->post->images->isNotEmpty())
                            <img src="{{ Storage::disk(config('filesystems.default'))->url('post_images/' . $transfer->post->images->first()->image_path) }}"
                                alt="投稿画像" class="user-icon">
                        @else
                            <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image" class="user-icon">
                        @endif
                        {{ $transfer->post->title }}
                    </li>

                    {{-- 成立日 --}}
                    <li>
                        <p>{{ $transfer->created_at }}</p>
                    </li>

                    {{-- 譲渡費 --}}
                    <li>
                        <p>{{ $transfer->post->cost_class }}</p>
                    </li>

                </ul>
            @endforeach
        </list-item>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/transfer/index.css') }}">
@endsection
