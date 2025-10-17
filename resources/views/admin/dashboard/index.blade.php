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

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('mixedChart').getContext('2d');
    const userData = @json($userData);
    const postData = @json($postData);
    const months = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];

    new Chart(ctx, {
        data: {
            labels: months,
            datasets: [
                {
                    type: 'bar',
                    label: '投稿数',
                    data: postData,
                    backgroundColor: 'rgba(234, 214, 169, 0.6)', // ベージュ
                    borderColor: '#EAD6A9',
                    borderWidth: 1,
                    yAxisID: 'y', // 左軸
                },
                {
                    type: 'line',
                    label: 'ユーザー登録数',
                    data: userData,
                    borderColor: '#503322',
                    backgroundColor: 'rgba(247, 232, 204, 0.5)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#A0794B',
                    yAxisID: 'y', // 同じ左軸に表示
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    title: { display: true, text: '件数' }
                },
                x: {
                    title: { display: true, text: '月' }
                }
            }
        }
    });
});
</script>

@stop


