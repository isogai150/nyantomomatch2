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
});



