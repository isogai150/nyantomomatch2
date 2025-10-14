'use strict';

$(function () {
  // ユーザーアイコン画像
  $('#imageInput').on('change', function(e) {
    const file = e.target.files[0];

    if (file) {
      // 画像プレビュー
      const reader = new FileReader();
      reader.onload = function(e) {
        $('#previewImage').attr('src', e.target.result);
      };
      reader.readAsDataURL(file);

      // または確認ダイアログを表示
      if (confirm('この画像にアップロードしますか？')) {
        $('#imageUploadForm').submit();
      } else {
        // キャンセルした場合は元の画像に戻す
        $(e.target).val('');
        location.reload();
      }
    }
  });

  // ユーザー退会処理
  // return true: フォーム送信、return false: フォーム送信キャンセル
  $('#withdrawalForm').on('submit', function(e) {
    if (!confirm('本当に退会しますか？\nこの操作は取り消せません。')) {
      e.preventDefault();
      return false;
    }
    return true;
  });
});

