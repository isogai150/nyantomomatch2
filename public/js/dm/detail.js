'use strict';

$(function() {
  // ======================
  // Laravelから渡された設定値を取得
  // ======================
  const fetchUrl  = window.dmConfig.fetchUrl;
  const sendUrl   = window.dmConfig.sendUrl;
  const csrfToken = window.dmConfig.csrfToken;
  const authId    = window.dmConfig.authId;

  // ======================
  // Enterキー送信（Shift+Enterで改行）
  // ======================
  $('#message-input').on('keypress', function(e) {
    if (e.which === 13 && !e.shiftKey) {
      e.preventDefault(); // 改行防止
      $('#dm-form').submit(); // フォーム送信
    }
  });

  // ======================
  // メッセージ送信処理
  // ======================
  $('#dm-form').on('submit', function(e) {
    e.preventDefault();
    const message = $('#message-input').val().trim();
    if (!message) return;

    $.ajax({
      url: sendUrl,   // Laravelから渡された送信URL
      type: "POST",
      data: {
        _token: csrfToken,  // CSRF対策トークン
        message: message
      },
      success: function(res) {
        appendMessage(res.message, true);
        $('#message-input').val('');
      },
      error: function() {
        alert('メッセージ送信に失敗しました。');
      }
    });
  });

  // ======================
  // メッセージ取得処理（3秒ごと）
  // ======================
  function fetchMessages() {
    $.ajax({
      url: fetchUrl,
      type: "GET",
      success: function(res) {
        $('#dm-messages').html(''); // 一度リセット
        res.messages.forEach(function(msg) {
          appendMessage(msg, msg.user_id === authId);
        });
      },
      error: function() {
        console.error('メッセージ取得に失敗しました。');
      }
    });
  }

  // ======================
  // メッセージをHTMLに追加
  // ======================
  function appendMessage(msg, isMine) {
    $('#dm-messages').append(`
      <div class="dm-message ${isMine ? 'mine' : 'other'}">
        <div class="dm-text">${msg.content}</div>
        <div class="dm-time">${msg.created_at}</div>
      </div>
    `);
    $('#dm-messages').scrollTop($('#dm-messages')[0].scrollHeight);
  }

  // ======================
  // 3秒ごとにfetch実行
  // ======================
  setInterval(fetchMessages, 3000);
});
