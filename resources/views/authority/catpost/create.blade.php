@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/create.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">

{{-- ここの中にコードを書く --}}
{{-- =============================================================================================== --}}

<h2>新しい投稿を作成</h2>
<p>猫の里親を募集する投稿を作成します。詳細な情報を入力してください。</p>

<form action="{{ route('posts.create') }}" method="POST">
  @csrf

  <p>基本情報</p>

  {{-- タイトル --}}
  <label for="title">タイトル</label>
  <input type="text" class="textbox" id="title" name="title" placeholder="タイトルを入力" value="{{ old('title') }}" />

  {{-- 年齢 --}}
  <label for="age">年齢</label>
  <input type="number" class="textbox" id="age" name="age" placeholder="例：2歳" value="{{ old('age') }}" />

{{-- 性別 --}}
<label for="gender">性別</label>
<select name="gender" id="gender" class="textbox">
  @foreach (\App\Models\Post::GENDER as $key => $label)
      <option value="{{ $key }}" {{ $key==old('gender') ? 'selected' : '' }}>
          {{ $label }}
      </option>
  @endforeach
</select>

  {{-- 品種 --}}
  <label for="kinds">品種</label>
  <input type="text" class="textbox" id="kinds" name="kinds" placeholder="例：ミックス" value="{{ old('kinds') }}" />

  {{-- 所在地 --}}
  <label for="location">所在地</label>
  <input type="text" class="textbox" id="location" name="location" placeholder="都道府県を入力" value="{{ old('location') }}" />

{{-- 投稿ステータス --}}
<label for="status">投稿ステータス</label>
<select name="status" id="status" class="textbox">
  @foreach (\App\Models\Post::STATUS as $key => $label)
      <option value="{{ $key }}" {{ $key==old('status') ? 'selected' : '' }}>
          {{ $label }}
      </option>
  @endforeach
</select>

  {{-- 掲載開始日・終了日 --}}
  <p>掲載開始日</p>
  <input type="date" name="start_date" class="textbox" value="{{ old('start_date') }}">
  <p>掲載終了日</p>
  <input type="date" name="end_date" class="textbox" value="{{ old('end_date') }}">

  {{-- 写真・動画 --}}
  <p>写真・動画</p>
  <p>猫の写真や動画を最大4件まで追加できます。1枚目は写真を選択してください。</p>

  {{-- 健康状態 --}}
  <label for="vaccine">予防接種</label>
  <input type="text" class="textbox" id="vaccine" name="vaccine" placeholder="予防接種関連について詳しく記述してください。" value="{{ old('vaccine') }}" />

  <label for="disease">病歴</label>
  <input type="text" class="textbox" id="disease" name="disease" placeholder="病歴等ございましたら詳しく記述してください。" value="{{ old('disease') }}" />

  {{-- 詳細説明 --}}
  <label for="description">詳細説明</label>
  <textarea id="description" name="description" class="textbox" placeholder="猫の性格や特徴などを詳しく書いてください。">{{ old('description') }}</textarea>

  {{-- 費用 --}}
  <label for="price">譲渡費用（総額）※内訳につきましては詳細説明入力欄へ入力をお願いします。</label>
  <input type="text" data-type="number" class="textbox" id="price" name="price" placeholder="例：30,000円" value="{{ old('price') }}" />

  <br><br>
  <button type="submit" class="post-btn">投稿を作成</button>
</form>

{{-- =============================================================================================== --}}
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
