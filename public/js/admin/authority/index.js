'use strict';

$(function () {

  // 却下処理
  $('#delete-btn').on('click', function(e) {
    if (!confirm('却下しますか？')) {
      e.preventDefault();
      return false;
    }
    alert('申請を却下しました。');
    return true;
  })
  // 承認処理
  $('#approval-btn').on('click', function(e) {
    if (!confirm('承認しますか？')) {
      e.preventDefault();
      return false;
    }
    alert('申請を承認しました。');
    return true;
  })
});