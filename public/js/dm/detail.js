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

 const dmId = config.dmId;


  // 編集中判定フラグ
  // 編集中はfetchMessages()による再描画を停止して、入力内容を保持する。
  let isEditing = false;

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

    // 編集中は更新をスキップ（DOM再描画による入力破棄防止）
    if (isEditing) return;

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

        // 譲渡UIをリアルタイム更新する
        if (res.transfer_status !== undefined) {
          updateTransferUI(res.transfer_status, res.is_poster, res.agreed_user_id);
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
      <div class="dm-message ${isMine ? 'mine' : 'other'}" data-id="${msg.id}">
        <div class="dm-text">${msg.content}</div>
        <div class="dm-time">${msg.created_at}</div>
        <div class="dm-actions">
          ${isMine
        ? `
              <button class="edit-btn">編集</button>
              <button class="delete-btn">削除</button>
            `
        : `
              <form action="/dm/${msg.pair_id}/message/${msg.id}/report" method="POST" onsubmit="return confirm('このメッセージを通報しますか？');">
                <input type="hidden" name="_token" value="${csrfToken}">
                <button type="submit" class="report-btn">通報</button>
              </form>
            `
      }
        </div>
      </div>
    `);
  }

  // 新規追加：譲渡ステータスに応じてUIをリアルタイム更新する関数
  function updateTransferUI(status, isPoster, agreedUserId) {

    const area = $('.dm-transfer-area');
    area.html(''); // 一度初期化

    // ====== Blade のロジックに完全一致（あなたのコードを忠実に再現）=======

    if (isPoster && status === 'none') {
      area.append(`
        <form action="/dm/${dmId}/transfer/send" method="POST">
          <input type="hidden" name="_token" value="${csrfToken}">
          <button type="submit" class="btn-detail">資料を渡す</button>
        </form>
      `);
      return;
    }

    if (!isPoster && status === 'sent') {
      area.append(`
        <a href="/dm/${dmId}/document" class="btn-detail">資料を確認する</a>
      `);
      return;
    }

    if (status === 'submitted') {
      area.append(`
        <form action="/dm/${dmId}/transfer/agree" method="POST">
          <input type="hidden" name="_token" value="${csrfToken}">
          <button type="submit" class="btn-detail">合意する</button>
        </form>
      `);
      return;
    }

    if (status === 'agreed_wait') {

      if (agreedUserId == authId) {
        area.append(`<p class="dm-status-wait">相手の合意をお待ちください…</p>`);
      } else {
        area.append(`
          <form action="/dm/${dmId}/transfer/agree" method="POST">
            <input type="hidden" name="_token" value="${csrfToken}">
            <button type="submit" class="btn-detail">合意する</button>
          </form>
        `);
      }
      return;
    }

    if (status === 'agreed') {

      const postId = $('#dm-config').data('postId');

      if (!isPoster) {
        area.append(`<a href="/payment/cart/${postId}" class="btn-detail">決済へ進む</a>`);
      } else {
        area.append(`<p class="dm-status-wait">里親様の決済をお待ちください…</p>`);
      }
      return;
    }


    if (status === 'paid') {
      if (!isPoster) {
        area.append(`<p class="dm-status-done">決済が完了しました！</p>`);
      } else {
        area.append(`<p class="dm-status-wait">里親様による決済が完了しました！<br>里親様へ譲渡手続きのご連絡をお願いします</p>`);
      }
      return;
    }
  }

  // ======================
  // メッセージ編集（Ajax）
  // ======================
  // $(document)はjsで動的に生成されたものに対して使う
  $(document).on('click', '.edit-btn', function () {
    isEditing = true; // 編集中フラグON
    const $msgBox = $(this).closest('.dm-message');
    const msgId = $msgBox.data('id');
    const $text = $msgBox.find('.dm-text');
    const originalText = $text.text();

    // テキストをtextareaに変換
    $text.replaceWith(`<textarea class="edit-area">${originalText}</textarea>`);
    const $editArea = $msgBox.find('.edit-area').focus();

    $(this).parent().html(`
      <button class="save-edit-btn">保存</button>
      <button class="cancel-edit-btn">キャンセル</button>
    `);

    // 保存処理
    $msgBox.on('click', '.save-edit-btn', function () {
      const newText = $editArea.val().trim();
      if (!newText) return alert('内容を入力してください。');

      $.ajax({
        url: `/dm/message/${msgId}/update`,
        type: 'PUT',
        data: {
          _token: csrfToken,
          content: newText,
        },
        success: function (res) {
          $editArea.replaceWith(`<div class="dm-text">${res.message.content}</div>`);
          $msgBox.find('.dm-actions').html(`
            <button class="edit-btn">編集</button>
            <button class="delete-btn">削除</button>
          `);
          isEditing = false; // 編集完了
        },
        error: function () {
          alert('更新に失敗しました。');
          isEditing = false;
        }
      });
    });

    // キャンセル処理
    $msgBox.on('click', '.cancel-edit-btn', function () {
      $editArea.replaceWith(`<div class="dm-text">${originalText}</div>`);
      $msgBox.find('.dm-actions').html(`
        <button class="edit-btn">編集</button>
        <button class="delete-btn">削除</button>
      `);
      isEditing = false;
    });
  });

  // ======================
  // メッセージ削除（Ajax）
  // ======================
  $(document).on('click', '.delete-btn', function () {
    const $msgBox = $(this).closest('.dm-message');
    const msgId = $msgBox.data('id');
    if (!confirm('本当に削除しますか？')) return;

    $.ajax({
      url: `/dm/message/${msgId}/delete`,
      type: 'DELETE',
      data: { _token: csrfToken },
      success: function () {
        $msgBox.fadeOut(300, function () {
          $(this).remove();
        });
      },
      error: function () {
        alert('削除に失敗しました。');
      }
    });
  });

  // ======================
  // 3秒ごとにメッセージを取得する処理を繰り返す
  // ======================
  // setInterval は指定した関数を一定間隔で自動実行する。
  // ここでは3秒（3000ミリ秒）ごとに fetchMessages() を実行。
  setInterval(fetchMessages, 3000);
});
