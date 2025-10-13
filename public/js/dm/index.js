'use strict';

$(function() {
    // エンターキーで検索実行
    $('.search-input').on('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $(this).closest('form').submit();
        }
    });

    // 検索クリア機能
    $('.clear-button').on('click', function() {
      const clearUrl = $(this).data('clear-url');
      if (clearUrl) {
        window.location.href = clearUrl;
      }
    });

    // 検索のクリアボタン
    $('.clear-button').click(function() {
        window.location.href = $(this).data('clear-url');
    });

    // 削除フォームのイベント伝播を止める
    $('.delete-form, .delete-button').click(function(e) {
        e.stopPropagation();
    });

    $('.delete-form').submit(function(e) {
        e.stopPropagation();
    });
});





