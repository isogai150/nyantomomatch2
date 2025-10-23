@extends('adminlte::page')

@section('title', 'DM詳細表示ページ')

@section('content_header')
    <h1>DM詳細表示ページ</h1>
@stop

@section('content')
{{--ここにメインのコードを記述--}}
{{-- ============================================================== --}}
@foreach($ditails as $ditail)

{{-- {{ $ditail->pair->content }} --}}
{{ $ditail->pair }}

@endforeach
{{-- ============================================================== --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/dm/index.css') }}">
@stop
@stop