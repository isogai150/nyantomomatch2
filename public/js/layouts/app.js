'use strict';

$(function () {
  // ハンバーガーメニューの開閉
  $('#hamburger-btn').on('click', function () {
    $(this).toggleClass('active');
    $('.hamburger-menu nav').toggleClass('open');
  });

  // ログアウト処理
  $('#logout-btn').on('click', function(e) {
    e.preventDefault(); // <a>タグのデフォルト動作を防ぐ
    if (confirm('本当にログアウトしますか？')) {
      $('#logout-form').submit();
    }
  });

    // 開く
  $('.footer-modal-open').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation(); // イベントの伝播を防止
    const target = $(this).data('target');
    $('.footer-modal').hide(); // 他モーダルを先に閉じる
    $('#' + target).fadeIn(200).css('display', 'flex');
  });

  // 閉じるボタン
  $('.footer-close').on('click', function () {
    $(this).closest('.footer-modal').fadeOut(200);
  });

  // 背景クリックでも閉じる
  $(document).on('click', '.footer-modal', function (e) {
    if ($(e.target).is('.footer-modal')) {
      $(this).fadeOut(200);
    }
  });
});
