'use strict';
/**
 * ==========================================
 *  Stripe Elements 決済処理用スクリプト（分割版）
 * ==========================================
 * カード番号・有効期限・CVC を別々のフィールドに分け、
 * 郵便番号フィールドを非表示にしたバージョン。
 * Laravel側のコントローラーは変更不要。
 */

document.addEventListener("DOMContentLoaded", function () {
  // ===============================
  // ① Stripeの初期設定
  // ===============================
  const stripe = Stripe(window.stripePublicKey);
  const elements = stripe.elements({
    locale: 'ja',
    appearance: { theme: 'stripe' }
  });

  // ===============================
  // ② カスタムスタイル設定
  // ===============================
  const style = {
    base: {
      color: "#503322",
      fontSize: "16px",
      fontFamily: '"Noto Sans JP", sans-serif',
      "::placeholder": { color: "#B9A38F" },
    },
    invalid: {
      color: "#E5424D",
    },
  };

  // ===============================
  // ③ 各要素（カード番号・有効期限・CVC）を個別生成
  // ===============================
  const cardNumber = elements.create("cardNumber", { style });
  const cardExpiry = elements.create("cardExpiry", { style });
  const cardCvc = elements.create("cardCvc", { style });

  // DOMへマウント
  cardNumber.mount("#card-number");
  cardExpiry.mount("#card-expiry");
  cardCvc.mount("#card-cvc");

  // ===============================
  // ④ エラー表示処理
  // ===============================
  const errorDisplay = document.getElementById("card-errors");

  const showError = (event) => {
    if (event.error) {
      errorDisplay.textContent = event.error.message;
      errorDisplay.style.color = "#E5424D";
    } else {
      errorDisplay.textContent = "";
    }
  };

  cardNumber.on("change", showError);
  cardExpiry.on("change", showError);
  cardCvc.on("change", showError);

  // ===============================
  // ⑤ フォーム送信時処理（3Dセキュア対応）
  // ===============================
  const form = document.getElementById("payment-form");

  form.addEventListener("submit", async function (event) {
    event.preventDefault(); // 通常送信をキャンセル

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
        user_id: document.getElementById("user_id").value,
      }),
    });

    const data = await response.json();

    if (data.error) {
      errorDisplay.textContent = data.error;
      return;
    }

    // ===============================
    // ⑥ 3Dセキュア認証を含む支払い確定処理
    // ===============================
    const result = await stripe.confirmCardPayment(data.client_secret, {
      payment_method: {
        card: cardNumber, // 分割型の場合は cardNumber を指定
        billing_details: {
          name: document.getElementById("name").value,
          email: document.getElementById("email").value,
        },
      },
    });

    if (result.error) {
      errorDisplay.textContent = result.error.message;
      errorDisplay.style.color = "#E5424D";
    } else if (result.paymentIntent.status === "succeeded") {
      const intentId = result.paymentIntent.id;
      window.location.href = `/checkout/success?payment_intent_id=${intentId}`;
    }
  });
});
