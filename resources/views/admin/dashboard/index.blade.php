{{-- @extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard/index.css') }}">
@endsection

@section('content')
    <div class="main-content">
        <div class="main-top">
            <h2>管理者ダッシュボード</h2>
            <p>保護猫里親マッチングプラットフォームの管理と運営状況を確認できます。</p>
        </div>
        <div class="total-date-items">
            <div class="total-user">
                <h3>総ユーザー数</h3>
                <p>{{ $userCount }}</p>
            </div>
            <div class="total-dm">
                <h3>総DM数</h3>
                <p>{{ $dmCount }}</p>
            </div>
            <div class="tptal-message">
                <h3>総メッセージ数</h3>
                <p>{{ $messageCount }}</p>
            </div>
            <div class="totak-post">
                <h3>総投稿数</h3>
                <p>{{ $postCount }}</p>
            </div>
        </div>
    </div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
{{-- @section('script')
    <script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection --}}

@extends('adminlte::page')

@section('title', 'ダッシュボード')

@section('content_header')
    <h1>管理者ダッシュボード</h1>
@stop

@section('content')
    <p>AdminLTE が正常に動作しています！</p>
@stop


