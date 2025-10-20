@extends('adminlte::page')

@section('title', 'ダッシュボード')

@section('content_header')
    <h1>管理者ダッシュボード</h1>
@stop

@section('content')
    <p>保護猫里親マッチングプラットフォームの管理と運営状況を確認できます。</p>
<div class="row">
    {{-- ユーザー総数 --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>登録ユーザー数</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>

    {{-- 投稿総数 --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $postCount }}</h3>
                <p>投稿総数</p>
            </div>
            <div class="icon"><i class="fas fa-cat"></i></div>
        </div>
    </div>

    {{-- DM総数 --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $dmCount }}</h3>
                <p>DM総数</p>
            </div>
            <div class="icon"><i class="fas fa-envelope"></i></div>
        </div>
    </div>

    {{-- メッセージ総数 --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $messageCount }}</h3>
                <p>メッセージ総数</p>
            </div>
            <div class="icon"><i class="fas fa-comments"></i></div>
        </div>
    </div>
</div>
{{-- ===== 混合グラフ：投稿数（棒）＋ユーザー登録数（折れ線） ===== --}}
<div class="card mt-4">
    <div class="card-header bg-light">
        <h3 class="card-title">月別投稿数・ユーザー登録数（{{ date('Y') }}年）</h3>
    </div>
    <div class="card-body">
        <canvas id="mixedChart"></canvas>
    </div>
</div>

@section('css')
    <link rel="stylesheet" href="{{  asset('css/admin/dashboard/index.css') }}">
@stop

@section('js')

<script>
    window.dashboardData = {
        userData: @json($userData),
        postData: @json($postData),
    };
</script>
    <script src="{{ asset('js/admin/dashboard/index.js') }}"></script>
@stop
@stop


