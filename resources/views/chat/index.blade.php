@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/chat/index.css') }}">
@endsection

@section('content')
<div class="chat-wrapper">
    <h1 class="chat-title">🐾 Geminiチャット</h1>

    <form id="chat-form" class="chat-form">
        @csrf
        <textarea id="question" name="question" placeholder="猫の飼い方を聞いてみよう…" class="chat-input"></textarea>
        <button type="submit" class="chat-btn">送信</button>
    </form>

    <div id="answer" class="chat-answer"></div>
</div>

<script>
document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const question = document.getElementById('question').value;
    const response = await fetch("{{ route('ask.gemini') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ question })
    });

    const data = await response.json();
    document.getElementById('answer').innerHTML = "🐱 <b>AI：</b> " + data.answer;
});
</script>
@endsection
