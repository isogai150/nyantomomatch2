'use strict';

$(function () {
  // ハンバーガーメニューの開閉
  $('#hamburger-btn').on('click', function () {
    $(this).toggleClass('active');
    $('.hamburger-menu nav').toggleClass('open');
  });
});
