'use strict';

$(function () {
    // モーダル表示
    $('.modal-open').on('click', function () {
        $('#loginModal').fadeIn();
    });

    $('.modal .close').on('click', function () {
        $('#loginModal').fadeOut();
    });

    $(document).on('click', function (e) {
        if ($(e.target).is('#loginModal')) {
            $('#loginModal').fadeOut();
        }
    });

    // お気に入り
    $('.favorite-btn').on('click', function() {
        $(this).toggleClass('active');
    });
});
