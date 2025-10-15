@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/create.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">

{{-- ここの中にコードを書く --}}
{{-- =================================================================================================== --}}

<div class="main-content">

<h2>新しい投稿を作成</h2>
<h3>猫の里親を募集する投稿を作成してください。</h3>

<form action="{{ route('posts.create') }}" method="POST">
  @csrf

{{-- ======================================================== --}}

<div class="background-form">
  {{-- <div class="subtitle"> --}}
    <h3>基本情報</h3>
  {{-- </div> --}}

  {{-- タイトル --}}
  <label>タイトル</label>
    <br>
  <input type="text" class="textbox-title" id="title" name="title" placeholder="タイトルを入力" value="{{ old('title') }}" />

  <br>

{{-- ======================================================== --}}

  <div class="container-flex">
    <div class="aaa">
      {{-- 年齢 --}}
      <label>年齢</label>
        <br>
      <input type="number" class="textbox-age" id="age" name="age" placeholder="例：2歳" value="{{ old('age') }}" />

    </div>

    <div class="aaa">
      {{-- 性別 --}}
      <label>性別</label>
        <br>
      <select name="gender" id="gender" class="textbox-gender">
        @foreach (\App\Models\Post::GENDER as $key => $label)
            <option value="{{ $key }}" {{ $key==old('gender') ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
      </select>

    </div>

  </div>

    <br>

{{-- ======================================================== --}}

  <div class="container">
    <div class="aaa">
    {{-- 品種 --}}
    <label>品種</label><br>
    <input type="text" class="textbox-kinds" id="kinds" name="kinds" placeholder="例：ミックス" value="{{ old('kinds') }}" />
    </div>

    <div class="aaa">
    {{-- 所在地 --}}
    <label>所在地</label><br>
    <input type="text" class="textbox-location" id="location" name="location" placeholder="都道府県を入力" value="{{ old('location') }}" />
    </div>

  </div>


    <br>

{{-- ======================================================== --}}

  {{-- 投稿ステータス --}}
  <label>投稿ステータス</label><br>
  <select name="status" id="status" class="textbox-status">
    @foreach (\App\Models\Post::STATUS as $key => $label)
        <option value="{{ $key }}" {{ $key==old('status') ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
  </select>

    <br>
    <br>
    <br>

{{-- ======================================================== --}}

  <div class="container">

    {{-- 掲載開始日・終了日 --}}
    <div class="aaa">
    <label>掲載開始日</label><br>
    <input type="date" name="start_date" class="textbox-date" value="{{ old('start_date') }}">
    </div>


    {{-- <label>　～　</label> --}}


    <div class="aaa">
    <label>掲載終了日</label><br>
    <input type="date" name="end_date" class="textbox-date" value="{{ old('end_date') }}">
    </div>
  </div>
</div>

{{-- ======================================================== --}}

{{-- =================================================================================================== --}}

<div class="background-photo-move">

  {{-- 写真・動画 --}}
  <p>写真・動画</p>
  <p>猫の写真や動画を最大4件まで追加できます。1枚目は写真を選択してください。</p>

</div>

{{-- =================================================================================================== --}}

<div class="background-health">

  {{-- 健康状態 --}}
  <p>健康状態</p><br>
  <p>予防接種</p><br>
  <input type="text" class="textbox" id="vaccine" name="vaccine" placeholder="予防接種関連について詳しく記述してください。" value="{{ old('vaccine') }}" />
  <br>
  <p>病歴</p>
  <input type="text" class="textbox" id="disease" name="disease" placeholder="病歴等ございましたら詳しく記述してください。" value="{{ old('disease') }}" />

</div>

{{-- =================================================================================================== --}}

<div class="background-description">

  {{-- 詳細説明 --}}
  <p>詳細説明</p>
  <textarea id="description" name="description" class="textbox" placeholder="猫の性格や特徴などを詳しく書いてください。">{{ old('description') }}</textarea>

</div>

{{-- =================================================================================================== --}}

<div class="background-price">

  {{-- 費用 --}}
  <p>譲渡費用（総額）※内訳につきましては詳細説明入力欄へ入力をお願いします。</p>
  <input type="text" data-type="number" class="textbox" id="price" name="price" placeholder="例：30,000円" value="{{ old('price') }}" />

</div>

{{-- =================================================================================================== --}}

<div class="btn">
  <br><br>
  <button type="submit" class="botten">投稿を作成</button>
</div>
</form>

</div>
{{-- =================================================================================================== --}}
{{-- bladeここまで --}}

</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script>

/* data-type='number'のテキストボックスを取得 */
var NBR = document.querySelectorAll("[data-type='number']");

/* 入力時に実行する処理に checkInput を指定 */
for (var i = 0; i < NBR.length; i++) {
  NBR[i].oninput = fmtInput;
}

/* 入力時に実行する処理 checkInput を作る */
function fmtInput(evt) {
  var target = evt.target;
  var data = target.value[target.value.length - 1];
  if (!data.match(/[0-9]/)) {
    target.value = target.value.slice(0, target.value.length - 1);
  }
  target.value = target.value
    .replace(/,/g, '')
    .replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
}

</script>
@endsection
