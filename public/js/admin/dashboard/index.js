'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('mixedChart').getContext('2d');
    const { userData, postData } = window.dashboardData;

    const months = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];

    new Chart(ctx, {
        data: {
            labels: months,
            datasets: [
                {
                    type: 'bar',
                    label: '投稿数',
                    data: postData,
                    backgroundColor: 'rgba(234, 214, 169, 0.6)',
                    borderColor: '#EAD6A9',
                    borderWidth: 1,
                    yAxisID: 'y',
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
                    yAxisID: 'y',
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
