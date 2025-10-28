@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/transfer/index.css') }}">
@endsection

@section('content')
    <div class="transfer-wrapper">

        {{-- 戻るボタン --}}
        <div class="back-area">
            <a href="{{ route('dm.show', $pair->id) }}" class="back-link">＜ 戻る</a>
        </div>

        {{-- ページタイトル --}}
        <h2 class="page-title">譲渡資料の確認</h2>

        {{-- タブボタン --}}
        <div class="tab-menu" role="tablist" aria-label="譲渡タブ">
            <button class="tab-btn active" data-target="tab-conditions" role="tab" aria-selected="true">譲渡資料</button>
            <button class="tab-btn" data-target="tab-contract" role="tab" aria-selected="false">譲渡契約書</button>
        </div>

        {{-- =============================
        譲渡条件資料
    ============================== --}}
        <div id="tab-conditions" class="tab-content active" role="tabpanel">
            <div class="box">

                <div class="firstitem">
                    <h3>重要事項</h3>
                    <ul class="list">
                        <li>終生飼育を確約の方にのみ譲渡可能</li>
                        <li>定期的な健康診断と予防接種を実施</li>
                        <li>完全室内飼育にて脱走防止対策を徹底</li>
                        <li>飼育困難時は必ず譲渡者に相談</li>
                    </ul>
                </div>

                <div class="flexitems">
                    <div class="flexitem">
                        <h3>猫情報</h3>
                        <ul class="list">
                            <li>年齢：{{ $pair->post->age }}歳</li>
                            <li>性別：{{ $pair->post->gender === 'male' ? 'オス' : 'メス' }}</li>
                            <li>品種：{{ $pair->post->breed }}</li>
                        </ul>
                    </div>

                    <div class="flexitem">
                        <h3>基本条件</h3>
                        <ul class="list">
                            <li>年齢：20歳以上65歳以下</li>
                            <li>住居：ペット飼育可能な住宅</li>
                            <li>家族：全員が猫の飼育に同意</li>
                            <li>経験：猫の飼育経験があること</li>
                        </ul>
                    </div>

                    <div class="flexitem">
                        <h3>飼育環境</h3>
                        <ul class="list">
                            <li>完全室内飼い</li>
                            <li>脱走防止対策の実施</li>
                            <li>適切な温度管理</li>
                            <li>十分な運動スペースの確保</li>
                        </ul>
                    </div>


                    <div class="flexitem">
                        <h3>医療・健康管理</h3>
                        <ul class="list">
                            <li>年1回以上の健康診断</li>
                            <li>混合ワクチンの接種</li>
                            <li>フィラリア予防の実施</li>
                            <li>病気・怪我時の適切な治療</li>
                            <li>不妊・去勢手術の実施</li>
                        </ul>
                    </div>

                </div>

                <h3>譲渡費用</h3>
                <p>{{ number_format($pair->post->cost) }}円（別途手数料と消費税）</p>

            </div>
        </div>

        {{-- =============================
        譲渡契約書
    ============================== --}}
        <div id="tab-contract" class="tab-content" role="tabpanel" aria-hidden="true">
            <div class="box">

                <h3>猫譲渡契約書</h3>
                <p>譲渡者（甲）と譲受者（乙）は以下内容に合意します</p>

                <div class="contract-box">
                    <p>【譲渡対象猫】</p>
                    <p>年齢：{{ $pair->post->age }}歳</p>
                    <p>性別：{{ $pair->post->gender === 'male' ? 'オス' : 'メス' }}</p>
                    <p>品種：{{ $pair->post->breed }}</p>
                    <p>譲渡金額：{{ number_format($pair->post->cost) }}円</p>
                </div>

                {{-- 署名フォーム --}}
                @if (Auth::id() !== $pair->post->user_id)
                    @php
                        $dm = $pair->id;
                    @endphp
                    <form action="{{ route('transfer.submit', $pair->id) }}" method="POST" class="contract-form">
                        @csrf

                        <div class="form-items">
                        <div class="form-item">
                            <label for="buyer_signature">譲受者署名</label>
                            <input id="buyer_signature" type="text" name="buyer_signature" class="input-form" required>
                        </div>

                        <div class="form-item">
                            <label for="signed_date">署名日</label>
                            <input id="signed_date" type="date" name="signed_date" class="input-form" required>
                        </div>
                        </div>



                        <button type="submit" class="btn-primary">提出する</button>
                    </form>
                @else
                    <p>譲受者の署名をお待ちください</p>
                @endif

            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ asset('js/transfer/tab.js') }}"></script>
@endsection
