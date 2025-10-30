@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dm/detail.css') }}">
@endsection

@section('content')
    <div class="dm-wrapper">

        {{-- ======= 戻るリンク・相手ユーザー情報 ======= --}}
        <div class="dm-header">
            <div class="back-page">
                <a href="{{ route('dm.index') }}">＜　戻る</a>
            </div>

            <div class="dm-user-info">
                <div class="dm-user-icon">
                    {{-- 投稿者のプロフィール画像 --}}
                    @if (!empty($partner->image_path))
                        <img src="{{ Storage::disk(config('filesystems.default'))->url('profile_images/' . $partner->image_path) }}" alt="{{ $partner->name }}" class="user-image">
                    @else
                        <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image"
                            class="user-image">
                    @endif
                </div>
                <div class="dm-user-name">{{ $partner->name ?? '相手のユーザー' }}さん</div>

                {{-- ============================= --}}
                {{-- ブロック／ブロック解除ボタン --}}
                {{-- ============================= --}}
                <div class="dm-block-buttons">
                    {{-- 自分が相手をブロックしている場合 --}}
                    @if ($isBlocking)
                        <form action="{{ route('block.destroy', $partner->id) }}" method="POST" class="unblock-form"
                            onsubmit="return confirm('このユーザーのブロックを解除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-unblock">ブロック解除</button>
                        </form>
                    {{-- 相手にブロックされていない && 自分もブロックしていない場合 --}}
                    @elseif (!$isBlockedBy)
                        <form action="{{ route('block.store', $partner->id) }}" method="POST" class="block-form"
                            onsubmit="return confirm('このユーザーをブロックしますか？');">
                            @csrf
                            <button type="submit" class="btn-block">ブロック</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- ======= 投稿情報（この猫について） ======= --}}
        @if ($post)
            <div class="dm-post-info">
                <div class="dm-post-img-area">
                    @php
                        $firstImage = optional($post->images->first())->image_path;
                        $imagePath = $firstImage ? str_replace('public/', '', $firstImage) : null;
                    @endphp

                    @if ($imagePath)
                        <img src="{{ Storage::disk(config('filesystems.default'))->url('post_images/' . $firstImage) }}"
                            alt="猫の写真" class="dm-post-img">
                    @else
                        <img src="{{ asset('images/noimage/213b3adcd557d334ff485302f0739a07.png') }}" alt="No Image"
                            class="dm-post-img">
                    @endif
                </div>

                <div class="dm-post-text-area">
                    <h3 class="dm-post-title">{{ $post->title }}</h3>
                    <p class="dm-post-desc">この投稿についてのやり取りです。</p>
                    <div class="dm-post-buttons">
                        <a href="{{ route('posts.detail', $post->id) }}" class="btn-detail">詳細を見る</a>
                    </div>
                </div>
            </div>
        @endif

{{-- ============================= --}}
{{-- 譲渡関連ボタン --}}
{{-- ============================= --}}
<div class="dm-transfer-area">
    @php
        $status = $dm->transfer_status;
        $isPoster = Auth::id() === $post->user_id;
    @endphp

    {{-- 資料を渡す（投稿者のみ / none） --}}
    @if($isPoster && $status === 'none')
        <form action="{{ route('transfer.send', $dm->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-detail">資料を渡す</button>
        </form>
    @endif

    {{-- 里親希望者のみ表示（資料確認ボタン） --}}
    @if(!$isPoster && $status === 'sent')
        <a href="{{ route('document.show', $dm->id) }}" class="btn-detail">資料を確認する</a>
    @endif

    {{-- 合意する（submitted / agreed_wait） --}}
    @if(in_array($status, ['submitted', 'agreed_wait']))
        <form action="{{ route('transfer.agree', $dm->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-detail">合意する</button>
        </form>

        @if($status === 'agreed_wait')
            <p class="dm-status-wait">相手の合意をお待ちください…</p>
        @endif
    @endif

    {{-- 決済フェーズ --}}
    @if($status === 'agreed')
        @if(!$isPoster)
            <a href="{{ route('payment.cart', $post->id) }}" class="btn-detail">決済へ進む</a>
        @else
            <p class="dm-status-wait">里親様の決済をお待ちください…</p>
        @endif
    @endif

    {{-- 完了 --}}
    @if($status === 'paid')
        <p class="dm-status-done">決済が完了しました！</p>
    @endif
</div>
{{-- ============================= --}}
{{-- 譲渡関連ボタンここまで --}}
{{-- ============================= --}}

        {{-- ======= メッセージ一覧 ======= --}}
        <div id="dm-messages" class="dm-messages">
            @foreach ($messages as $message)
                <div class="dm-message {{ $message->user_id === auth()->id() ? 'mine' : 'other' }}"
                    data-id="{{ $message->id }}">
                    <div class="dm-text">{{ $message->content }}</div>
                    <div class="dm-time">{{ $message->created_at->format('Y年n月j日 H:i') }}</div>
                </div>
            @endforeach
        </div>

{{-- ========================= --}}
{{-- メッセージ送信フォーム --}}
{{-- ========================= --}}
<div class="dm-send-area">

    {{-- 自分が相手をブロックしている場合 --}}
    @if ($isBlocking)
        <div class="block-message warning">
            あなたはこのユーザーをブロックしています。<br>
            メッセージを送信するにはブロックを解除してください。
        </div>


    {{-- 相手にブロックされている場合 --}}
    @elseif ($isBlockedBy)
        <div class="block-message danger">
            このユーザーによりブロックされています。<br>
            メッセージを送信することはできません。
        </div>

    {{-- 通常の送信フォーム --}}
    @else
        <form id="dm-form" class="dm-form" method="POST" autocomplete="off">
            @csrf
            <textarea id="message-input" name="message" placeholder="メッセージを入力..." required></textarea>
            <button type="submit" id="send-btn">送信</button>
        </form>
    @endif

</div>

@endsection

@section('script')
    <div id="dm-config" data-fetch-url="{{ route('dm.message.fetch', $dm->id) }}"
        data-send-url="{{ route('dm.message.send', $dm->id) }}" data-csrf-token="{{ csrf_token() }}"
        data-auth-id="{{ auth()->id() }}">
    </div>

    <script src="{{ asset('js/dm/detail.js') }}"></script>
@endsection
