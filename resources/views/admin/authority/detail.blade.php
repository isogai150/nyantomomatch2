@extends('adminlte::page')

@section('title', '投稿権限申請の詳細')

@section('content_header')
    <h1>投稿権限申請の詳細</h1>
@stop

@section('content')
<div class="main-content">
  @foreach ($authoritys as $authority)
  <div class="user_inf">
  <h3>申請者情報</h3>
  <p>名前：{{ $authority->user->name }}</p>
  <p>メールアドレス：{{ $authority->user->email }}</p>
  <p>申請日:{{ $authority->created_at }}</p>
  <p>ステータス:{{ $authority->status_label }}</p>
  </div>
  <div class="authority">
    <h3>自己紹介文</h3>
    <p>{{ $authority->user->description }}</p>
    <h3>申請理由</h3>
    <p>{{ $authority->reason }}</p>
  </div>
  @endforeach
</div>
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/authority/detail.css') }}">
@stop

@stop
