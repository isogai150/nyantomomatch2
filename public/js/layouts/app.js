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

});
