'use strict';
/**
 * ==========================================
 *  Stripe Elements 決済処理用スクリプト
 * ==========================================
 * Stripe.jsを使ってクレジットカード情報を安全に送信し、
 * Laravel側でトークンを使って決済を完了させる仕組みを実装。
 *
 * 仕組みの概要：
 *  1. ページ読み込み時にStripeとElementsを初期化。
 *  2. Stripeが提供する「カード入力UI（Elements）」を生成し、HTMLの<div id="card-element">に埋め込む。
 *  3. ユーザーがフォーム送信時にカード情報をトークン化（token化）してStripeへ直接送信。
 *  4. Stripeから返されたトークンIDをフォームに追加してLaravelへ送信。
 *  5. Laravel側でstripe-phpを使って決済処理を実行。
 */

document.addEventListener("DOMContentLoaded", function () {
  // ===============================
  // ① Stripeの初期設定
  // ===============================

  // 環境変数（.env）に設定した公開キーをBladeから受け取る
  const stripe = Stripe(window.stripePublicKey);
  const elements = stripe.elements();

  // ===============================
  // ② Stripe Elements のUI設定
  // ===============================

  // Elementsの見た目をカスタマイズ
  const style = {
    base: {
      color: "#503322", // テキスト色
      fontSize: "16px",
      fontFamily: '"Noto Sans JP", sans-serif',
      "::placeholder": { color: "#B9A38F" }, // プレースホルダーの色
    },
    invalid: {
      color: "#E5424D", // 入力エラー時の色
    },
  };

  // カード入力要素を作成
  const card = elements.create("card", { style });

  // Bladeの <div id="card-element"> にマウント（UIを埋め込む）
  card.mount("#card-element");

  // ===============================
  // ③ カード入力エラーのリアルタイム表示
  // ===============================
  const errorDisplay = document.getElementById("card-errors");

  card.on("change", function (event) {
    if (event.error) {
      errorDisplay.textContent = event.error.message; // エラーメッセージ表示
      errorDisplay.style.color = "#E5424D";
    } else {
      errorDisplay.textContent = "";
    }
  });

  // ===============================
  // ④ フォーム送信時の処理（3Dセキュア対応）
  // ===============================

  const form = document.getElementById("payment-form");

  form.addEventListener("submit", async function (event) {
    event.preventDefault(); // 通常の送信をキャンセル（Stripe処理を先に行う）

    // LaravelのAPI（PaymentIntent作成）を呼び出す
    const response = await fetch("/api/checkout/process", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
      },
      body: JSON.stringify({
        post_id: document.querySelector('input[name="post_id"]').value,
        email: document.getElementById("email").value,
      }),
    });

    const data = await response.json();

    if (data.error) {
      errorDisplay.textContent = data.error;
      return;
    }

    // ===============================
    // ⑤ 3Dセキュア認証を含む支払い確定処理
    // ===============================
    const result = await stripe.confirmCardPayment(data.client_secret, {
      payment_method: {
        card: card,
        billing_details: {
          name: document.getElementById("name").value,
          email: document.getElementById("email").value,
        },
      },
    });

    if (result.error) {
      // エラー発生時（カード認証失敗など）
      errorDisplay.textContent = result.error.message;
      errorDisplay.style.color = "#E5424D";
    } else if (result.paymentIntent.status === "succeeded") {
      // 成功時に完了ページへ遷移
      window.location.href = "/checkout/success";
    }
  });
});
