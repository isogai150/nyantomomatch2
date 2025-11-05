@extends('layouts.app')

@section('content')
<div class="chat-container" style="text-align:center; margin-top:100px;">
    <h1>🐾 Geminiチャット</h1>
    <form id="chat-form">
        @csrf
        <textarea id="question" name="question" placeholder="猫の飼い方を聞いてみよう…" style="width:70%;height:120px;"></textarea><br>
        <button type="submit" class="btn" style="margin-top:10px;">送信</button>
    </form>

    <div id="answer" style="margin-top:30px; font-size:18px; color:#503322;"></div>
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
    document.getElementById('answer').innerHTML = "🐱 <b>AI:</b> " + data.answer;
});
</script>
@endsection
