@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/chat/index.css') }}">
@endsection

@section('content')
<div class="chat-wrapper">

    {{-- ===== 左上の「topページへ」リンク ===== --}}
    <a href="{{ route('posts.index') }}" class="back-link">topページへ</a>

    {{-- ===== タイトル ===== --}}
    <h1 class="chat-title">🐾 Geminiチャット</h1>

    {{-- ===== 質問フォーム ===== --}}
    <form id="chat-form" class="chat-form">
        @csrf
        {{-- ユーザーが質問を入力する欄 --}}
        <textarea id="question" name="question" class="chat-input" placeholder="猫の飼い方を聞いてみよう…"></textarea>
        {{-- 送信ボタン --}}
        <button type="submit" class="chat-btn">送信</button>
    </form>

    {{-- ===== AIが回答を考えている間に表示するスピナー ===== --}}
    <div id="loading" class="chat-loading" style="display:none;">
        <div class="spinner"></div>
        <p>AIが考えています...</p>
    </div>

    {{-- ===== AIからの返答を表示する領域 ===== --}}
    <div id="answer" class="chat-answer"></div>
</div>

{{-- ===== JS部分（外部ファイルが動作しなかったため直接記述） ===== --}}
<script>
// ページが完全に読み込まれてから処理を開始
document.addEventListener('DOMContentLoaded', () => {

    // ===== 各HTML要素を取得 =====
    const form = document.getElementById('chat-form');      // フォーム全体
    const textarea = document.getElementById('question');   // ユーザーの入力欄
    const loading = document.getElementById('loading');     // 「AIが考えています…」の表示
    const answer = document.getElementById('answer');       // AIからの返答を表示する部分
    const askUrl = "{{ route('ask.gemini') }}";             // コントローラーのルート（送信先URL）
    const csrfToken = "{{ csrf_token() }}";                 // LaravelのCSRF対策用トークン

    // ===== Enterで送信、Shift+Enterで改行 =====
    textarea.addEventListener('keydown', (e) => {
        // Enterのみ押されたとき送信（Shift+Enterは改行にする）
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();        // デフォルトの改行動作を止める
            form.requestSubmit();      // 安全なフォーム送信（Laravel推奨）
        }
    });

    // ===== フォーム送信処理 =====
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // ページのリロードを防止

        // 入力された質問を取得
        const question = textarea.value.trim();

        // 空欄なら送信しない
        if (!question) return;

        // 入力欄をクリアしてローディングを表示
        textarea.value = '';
        loading.style.display = 'block'; // 「AIが考えています…」を表示
        answer.style.opacity = 0;        // 回答部分を一時的に非表示
        answer.innerHTML = '';           // 前回の回答をクリア

        try {
            // ===== fetchでサーバーへ質問を送信 =====
            const response = await fetch(askUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // セキュリティ対策
                },
                body: JSON.stringify({ question }), // 質問をJSON形式で送信
            });

            // ===== サーバーからの返答を取得 =====
            const data = await response.json();

            // ローディングを非表示にする
            loading.style.display = 'none';

            // ===== AIの回答を画面に表示（フェードイン効果付き） =====
            answer.innerHTML = `<div class="ai-bubble">🐱 <b>AI:</b> ${data.answer}</div>`;
            answer.style.transition = 'opacity 0.5s ease';
            requestAnimationFrame(() => {
                answer.style.opacity = 1; // フェードイン
            });

        } catch (error) {
            // ===== 通信エラー発生時 =====
            console.error('通信エラー:', error);
            loading.style.display = 'none';
            answer.innerHTML = `<p style="color:red;">通信エラーが発生しました。</p>`;
            answer.style.opacity = 1;
        }
    });
});
</script>
@endsection
