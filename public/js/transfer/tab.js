'use strict';

$(function () {

    // タブ切替クリックイベント
    $('.tab-btn').on('click', function (e) {

        // ボタンがフォーム内にある場合はタブ処理を無効（POST送信干渉防止）
        if ($(this).closest('form').length) return;

        // タブボタン状態変更
        $('.tab-btn').removeClass('active').attr('aria-selected', 'false');
        $(this).addClass('active').attr('aria-selected', 'true');

        // コンテンツ切替
        const target = $(this).data('target');
        $('.tab-content').removeClass('active').attr('aria-hidden', 'true');
        $('#' + target).addClass('active').attr('aria-hidden', 'false');
    });
});
