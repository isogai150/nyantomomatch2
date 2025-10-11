'use strict';

$(function () {

  // LaravelからBlade経由で渡された設定値を取得
  // Bladeファイルの中の<div id="dm-config">に埋め込まれた data属性 から値を取得。
  // グローバル変数を使わずに済む。
  const config = $('#dm-config').data();

  // 各設定値をローカル変数として格納
  const fetchUrl = config.fetchUrl;     // メッセージ一覧を取得するAPIのURL
  const sendUrl = config.sendUrl;       // メッセージ送信APIのURL
  const csrfToken = config.csrfToken;   // CSRFトークン（POST通信時に必須）
  const authId = config.authId;         // 現在ログインしているユーザーID

  // ======================
  // Enterキー送信（Shift+Enterで改行）
  // ======================
  // テキストエリア内でキーが押された時にイベントを検知。
  // 「Enter」だけなら送信、「Shift + Enter」なら改行。
  $('#message-input').on('keypress', function (e) {
    // e.which は押されたキーのコード（13はEnter）
    if (e.which === 13 && !e.shiftKey) {
      // 改行を無効化（デフォルト動作を止める）
      e.preventDefault();
      // フォーム送信イベントを発火
      $('#dm-form').submit();
    }
  });

  // ======================
  // メッセージ送信処理（Ajax）
  // ======================
  // フォームの送信イベントを検知して、ページ遷移なしで非同期通信を行う。
  $('#dm-form').on('submit', function (e) {
    // ページリロードを防ぐ
    e.preventDefault();

    // 入力欄の値を取得して、前後の空白を削除
    const message = $('#message-input').val().trim();

    // 未入力なら処理を中断（空送信防止）
    if (!message) return;

    // Ajax通信開始
    $.ajax({
      // Laravel側の送信ルート
      url: sendUrl,
      // HTTPメソッド（送信なのでPOST）
      type: "POST",
      // 渡すデータ
      data: {
        _token: csrfToken,   // Laravelが要求するCSRFトークンを送信
        message: message     // 入力されたメッセージ本文
      },

      // 成功した時の処理
      success: function (res) {
        // 画面に自分のメッセージを追加
        appendMessage(res.message, true);
        // 入力欄をクリア
        $('#message-input').val('');
        // 自分の送信後は自動的に最下部までスクロール
        $('#dm-messages').scrollTop($('#dm-messages')[0].scrollHeight);
      },

      // 通信に失敗した場合（サーバーダウンやバリデーションエラーなど）
      error: function () {
        alert('メッセージ送信に失敗しました。');
      }
    });
  });

  // ======================
  // メッセージ取得処理（3秒ごとに実行）
  // ======================
  // 画面をリロードせずに新しいメッセージを取得する処理。
  // Laravelの fetch() メソッド（PairController）にGETリクエストを送る。
  function fetchMessages() {

    const $messageArea = $('#dm-messages');
    const scrollPos = $messageArea.scrollTop(); // 現在のスクロール位置を保持
    // 「ほぼ最下部にいるか」を判断（ブラウザ差の誤差を1px以内で吸収）
    const isAtBottom = Math.abs($messageArea[0].scrollHeight - $messageArea.scrollTop() - $messageArea.outerHeight()) < 1;

    $.ajax({
      url: fetchUrl,    // メッセージ一覧を取得するAPIのURL
      type: "GET",      // データ取得なのでGETメソッド
      success: function (res) {
        // 一度メッセージ欄を空にする（全削除）
        $messageArea.html('');

        // 各メッセージを1つずつHTMLに追加
        res.messages.forEach(function (msg) {
          // msg.user_id === authId → 自分のメッセージなら右寄せ表示
          appendMessage(msg, msg.user_id === authId);
        });

        // スクロール位置を保持または最下部に固定
        if (isAtBottom) {
          // 一番下まで自動スクロール（新着メッセージが常に見えるように）
          $messageArea.scrollTop($messageArea[0].scrollHeight);
        } else {
          // それ以外の場合はユーザーの閲覧位置を維持（画面が上下しない）
          $messageArea.scrollTop(scrollPos);
        }
      },
      error: function () {
        console.error('メッセージ取得に失敗しました。');
      }
    });
  }

  // ======================
  // メッセージをHTMLに追加（画面描画処理）
  // ======================
  // 1件のメッセージを受け取り、チャット欄にHTMLとして追加する。
  function appendMessage(msg, isMine) {
    // isMine が true のときは自分の発言、false のときは相手の発言としてクラスを切り替える。
    $('#dm-messages').append(`
      <div class="dm-message ${isMine ? 'mine' : 'other'}">
        <div class="dm-text">${msg.content}</div>
        <div class="dm-time">${msg.created_at}</div>
      </div>
    `);
  }

  // ======================
  // 3秒ごとにメッセージを取得する処理を繰り返す
  // ======================
  // setInterval は指定した関数を一定間隔で自動実行する。
  // ここでは3秒（3000ミリ秒）ごとに fetchMessages() を実行。
  setInterval(fetchMessages, 3000);
});
