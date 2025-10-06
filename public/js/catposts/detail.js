'use strict';

$(function () {

    // ===============================
    // サムネイルクリックでメイン画像切り替え
    // ===============================
    $(document).on('click', '.thumbnail', function () {
        const $main = $('#main-image');
        const $thumb = $(this);

        // 画像サムネイルの場合
        if ($thumb.is('img')) {
            // もしメインが動画なら画像に置き換え
            if ($main.is('video')) {
                const newImg = $('<img>', {
                    id: 'main-image',
                    class: 'main-photo',
                    src: $thumb.attr('src'),
                    alt: '投稿画像'
                });
                $main.replaceWith(newImg);
            } else {
                // すでに画像の場合はsrcだけ切り替え
                $main.attr('src', $thumb.attr('src'));
            }
        }

        // 動画サムネイルの場合
        if ($thumb.is('video')) {
            const videoSrc = $thumb.find('source').attr('src');
            const newVideo = $('<video>', {
                id: 'main-image',
                class: 'main-photo',
                controls: true,
                autoplay: true,
                muted: true
            });
            const newSource = $('<source>', {
                src: videoSrc,
                type: 'video/mp4'
            });
            newVideo.append(newSource);
            $main.replaceWith(newVideo);
        }
    });

    // ===============================
    // お気に入りボタンの見た目切り替え
    // ===============================
    $(document).on('click', '.favorite-btn', function () {
        $(this).toggleClass('active');
    });

});
