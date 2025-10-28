@extends('adminlte::page')

@section('title', 'DM詳細表示ページ')

@section('content_header')
    <h1>DM詳細表示ページ</h1>
@stop

@section('content')
{{--ここにメインのコードを記述--}}
{{-- ============================================================== --}}

<p>1028</p>

            @foreach ($reports as $report)
            @foreach ($users as $user)
                <ul>



                    <li>
                        <p>メッセージID：{{ $report->message_id }}</p>
                    </li>
                    <li>
                        <p>投稿日：{{ $report->created_at }}</p>
                    </li>



        {{-- @php
            $isUserA = ($report->user_id === $report->userA_id);
            $sender = $isUserA ? $report->userA : $report->userB;
        @endphp --}}



                    <li>
                        <p>投稿者：{{ $user->name }}</p>
                    </li>
                    <li>
                        {{ $report->status }}
                    </li>

                    <li>
                        <a href="#">解決済み</a>
                    </li>

                    <li>
                        <a href="#">却下</a>
                    </li>

                    <li>
                        <a href="#">詳細表示</a>
                    </li>

                </ul>
            @endforeach
            @endforeach


{{-- ============================================================== --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/report/index.css') }}">
@stop
