'use strict';

$(function () {

    // ===============================
    // モーダル開閉制御（ログイン促し用）
    // ===============================
    $(document).on('click', '.modal-open', function (e) {
        e.preventDefault(); // ページ遷移を防止
        $('#loginModal').fadeIn(); // モーダル表示
    });

    // ×ボタンで閉じる
    $(document).on('click', '.modal .close', function () {
        $('#loginModal').fadeOut();
    });

    // モーダル外クリックでも閉じる
    $(document).on('click', function (e) {
        if ($(e.target).is('#loginModal')) {
            $('#loginModal').fadeOut();
        }
    });

    // ===============================
    // お気に入りボタン見た目トグル（フロント動作のみ）
    // ===============================
    $(document).on('click', '.favorite-btn', function () {
        $(this).toggleClass('active');
    });
});
