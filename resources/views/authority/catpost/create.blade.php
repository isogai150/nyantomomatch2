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
    <h3>猫の里親を募集する<br class="br-sp">投稿を作成してください。</h3>

{{-- ======================================================== --}}

  <div class="background-form">
    <h3>基本情報</h3><br>

{{-- ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～ --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}
    @endif
{{-- ～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～～ --}}
    <form action="{{ route('posts.create') }}" method="POST" enctype="multipart/form-data">
      @csrf

  {{-- タイトル --}}
  <label for="title">タイトル</label>
  <br>
  <textarea class="textbox-title" rows="3" cols="30" id="title" name="title" placeholder="タイトルを入力" value="{{ old('title') }}" ></textarea>
  {{-- @error('title')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror --}}
  <br>
{{-- ======================================================== --}}

  <div class="container-flex">
    <div class="flexblock">
      {{-- 年齢 --}}
      <label for="age">年齢</label>
        <br>
      <input type="number" class="textbox-age" min="0" max="30" id="age" name="age" placeholder="例：2（才）" value="{{ old('age') }}" />
      {{-- @error('age')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror --}}
    </div>

    <div class="flexblock">
      {{-- 性別 --}}
      <label for="gender">性別</label>
        <br>
      <select name="gender" id="gender" class="textbox-gender">
        @foreach (\App\Models\Post::GENDER as $key => $label)
            <option value="{{ $key }}" {{ $key==old('gender') ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
      </select>

      {{-- @error('gender')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror --}}

    </div>

  </div>

    <br>

{{-- ======================================================== --}}

  <div class="container">
    <div class="flexblock">
    {{-- 品種 --}}
    <label>品種</label><br>
    <input type="text" class="textbox-kinds" id="kinds" name="kinds" placeholder="例：ミックス" value="{{ old('kinds') }}" />
    </div>

    <div class="flexblock">
    {{-- 所在地 --}}
    <label>所在地</label><br>
    <input type="text" class="textbox-location" id="location" name="location" placeholder="都道府県を入力（例：東京都）" value="{{ old('location') }}" />
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


<div class="container-flex date-range">

  <div class="bbb">
    <label for="start_date">掲載開始日</label><br>
    <input type="date" min="2025-10-14" max="2029-12-31" name="start_date" class="textbox-start-date" value="{{ old('start_date') }}">
    {{-- @error('start_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror --}}
  </div>

  <label class="wave">～</label>

  <div class="ccc">
    <label for="end_date">掲載終了日</label><br>
    <input type="date" min="2025-10-14" max="2029-12-31" name="end_date" class="textbox-end-date" value="{{ old('end_date') }}">
    {{-- @error('end_date')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror --}}
  </div>
</div>

  </div>

{{-- ======================================================== --}}

{{-- =================================================================================================== --}}

<div class="background-photo-move">

  {{-- 写真・動画 --}}
  <p>写真・動画</p>
  <p>猫の写真や動画を最大4件まで追加できます。1枚目は写真を選択してください。</p>


{{-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| --}}
{{-- 画像アップロード --}}
{{-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| --}}
<form method="POST" action="{{ route('posts.create.image') }}" enctype="multipart/form-data">
  @csrf
  <input type="file" name="image">


<img src="{{ asset('storage/{img}') }}"/>
<button>アップロード</button>
</form>
{{-- @foreach($users as $user)

<img src="{{ asset($user->image_path) }}" >

@endforeach --}}

{{-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| --}}

</div>

{{-- =================================================================================================== --}}

<div class="background-health">

  {{-- 健康状態 --}}
  <label>健康状態</label><br><br><br>
  <label>予防接種</label><br>
  <textarea class="textbox-vaccine" rows="3" cols="30" id="vaccine" name="vaccine" placeholder="予防接種関連について詳しく記述してください。" value="{{ old('vaccine') }}" ></textarea>
  <br>
  <label>病歴</label><br>
  <textarea class="textbox-disease" rows="3" cols="30" id="disease" name="disease" placeholder="病歴等ございましたら詳しく記述してください。" value="{{ old('disease') }}" ></textarea>

</div>

{{-- =================================================================================================== --}}

<div class="background-description">

  {{-- 詳細説明 --}}
  <label>詳細説明</label><br>
  <textarea rows="4" cols="30" id="description" name="description" class="textbox-description" placeholder="猫の性格や特徴などを詳しく書いてください。">{{ old('description') }}</textarea>

</div>

{{-- =================================================================================================== --}}

<div class="background-price">

  {{-- 費用 --}}
  <label>譲渡費用（総額）<br>※内訳につきましては<br class="br-sp">詳細説明入力欄へ<br class="br-sp">入力をお願いします。</label>
  <input type="text" data-type="number" class="textbox-price" id="price" name="price" placeholder="例：30,000（円）" value="{{ old('price') }}" />

</div>

{{-- =================================================================================================== --}}

{{-- ======================================== --}}
{{-- バリデーションメッセージを表示するためのもの --}}
{{-- @if($errors->has('name'))
  <div class="alert-danger">
@foreach($errors->has('name') as $message)
  <p>{{ $message }}</p>
@endforeach
  </div>
@endif --}}
            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $message)
                  <p>{{ $message }}</p>
                @endforeach
              </div>
            @endif
{{-- ======================================== --}}

{{-- 投稿を作成ボタン --}}
<div class="btn">
  <br><br>
  <button type="submit" class="botten">投稿を作成</button>
</div>
</form>
</div>

</div>
{{-- =================================================================================================== --}}
{{-- bladeここまで --}}

</div>
@endsection
