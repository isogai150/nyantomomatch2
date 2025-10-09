'use strict'; 
// "use strict" は厳格モードを有効化する記述。
// JavaScriptのバグを防ぎ、安全な書き方を強制する（例：未定義変数の使用禁止）

$(function() { 
  // jQueryの「DOM構築完了後に実行」構文。
  // ページ内のHTML要素（フォームやボタンなど）がすべて読み込まれてから中の処理を実行する。

  // ======================
  // LaravelからBlade経由で渡された設定値を取得
  // ======================
  // Bladeファイルの中で埋め込まれた dmConfig の値をここで変数に代入。
  // これらは Laravel 側で route() や csrf_token() を使って生成された安全な値。
  const fetchUrl  = window.dmConfig.fetchUrl;   // メッセージ一覧を取得するAPIのURL
  const sendUrl   = window.dmConfig.sendUrl;    // メッセージ送信APIのURL
  const csrfToken = window.dmConfig.csrfToken;  // CSRF対策トークン（POST通信時に必須）
  const authId    = window.dmConfig.authId;     // 現在ログインしているユーザーのID

  // ======================
  // Enterキー送信（Shift+Enterで改行）
  // ======================
  // テキストエリア内でキーが押された時にイベントを検知。
  // 「Enter」だけなら送信、「Shift + Enter」なら改行を許可する。
  $('#message-input').on('keypress', function(e) {
    // e.which は押されたキーのコード（13はEnter）
    if (e.which === 13 && !e.shiftKey) {
      e.preventDefault(); // 改行を無効化（デフォルト動作を止める）
      $('#dm-form').submit(); // フォーム送信イベントを発火させる
    }
  });

  // ======================
  // メッセージ送信処理（Ajax）
  // ======================
  // フォームの送信イベントを検知して、ページ遷移なしで非同期通信を行う。
  $('#dm-form').on('submit', function(e) {
    e.preventDefault(); // ページリロードを防ぐ
    const message = $('#message-input').val().trim(); // 入力欄の値を取得し、前後の空白を削除
    if (!message) return; // 未入力なら処理を中断（空送信防止）

    // Ajax通信開始
    $.ajax({
      url: sendUrl,   // Laravel側の送信ルート（例：/dm/{dm}/message/create）
      type: "POST",   // HTTPメソッド（送信なのでPOST）
      data: {
        _token: csrfToken,  // Laravelが要求するCSRFトークンを送信
        message: message    // 入力されたメッセージ本文
      },
      success: function(res) {
        // コントローラからJSONで返ってきたデータを受け取る
        // res.message の中には content, created_at などが入っている
        appendMessage(res.message, true); // 画面に自分のメッセージを即時追加
        $('#message-input').val(''); // 入力欄をクリア
      },
      error: function() {
        // 通信に失敗した場合（サーバーダウンやバリデーションエラーなど）
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
    $.ajax({
      url: fetchUrl,  // メッセージ一覧を取得するAPIのURL
      type: "GET",    // データ取得なのでGETメソッド
      success: function(res) {
        // コントローラから返ってきたJSON（res.messages）をもとに一覧を再描画する
        $('#dm-messages').html(''); // 一度メッセージ欄を空にする（全削除）
        res.messages.forEach(function(msg) {
          // 各メッセージを1つずつHTMLに追加
          // msg.user_id === authId → 自分のメッセージなら右寄せ表示
          appendMessage(msg, msg.user_id === authId);
        });
      },
      error: function() {
        // 取得に失敗したときのエラーハンドリング
        console.error('メッセージ取得に失敗しました。');
      }
    });
  }

  // ======================
  // メッセージをHTMLに追加（画面描画処理）
  // ======================
  // 1件のメッセージを受け取り、チャット欄にHTMLとして追加する。
  // isMine が true のときは自分の発言、false のときは相手の発言としてクラスを切り替える。
  function appendMessage(msg, isMine) {
    $('#dm-messages').append(`
      <div class="dm-message ${isMine ? 'mine' : 'other'}">
        <div class="dm-text">${msg.content}</div>
        <div class="dm-time">${msg.created_at}</div>
      </div>
    `);
    // 一番下まで自動スクロール（新着メッセージが常に見えるように）
    $('#dm-messages').scrollTop($('#dm-messages')[0].scrollHeight);
  }

  // ======================
  // 3秒ごとにメッセージを取得する処理を繰り返す
  // ======================
  // setInterval は指定した関数を一定間隔で自動実行する。
  // ここでは3秒（3000ミリ秒）ごとに fetchMessages() を実行。
  setInterval(fetchMessages, 3000);
});
