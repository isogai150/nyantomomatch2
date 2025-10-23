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

{{-- ======================================================== --}}

    <form action="{{ route('catpost.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

    {{-- タイトル --}}
    <label for="title">タイトル</label>
    <br>
    <textarea class="textbox-title" rows="3" cols="30" id="title" name="title" placeholder="タイトルを入力">{{ old('title') }}</textarea>
    @error('title')
      <div class="alert-danger">{{ $message }}</div>
    @enderror
    <br>
    <br>

{{-- ======================================================== --}}

      <div class="container-flex">
        <div class="flexblock">
          {{-- 年齢 --}}
          <label for="age">年齢</label>
            <br>
          <input type="number" class="textbox-age" min="0" max="30" id="age" name="age" placeholder="例：2（才）" value="{{ old('age') }}" />

          @error('age')
            <div class="alert-danger">{{ $message }}</div>
          @enderror

        </div>

{{-- ======================================================== --}}

        <div class="flexblock">
          {{-- 性別 --}}
          <label for="gender">性別</label>
          <br>
          <select name="gender" id="gender" class="textbox-gender">

              <option value="">選択してください</option>

              @foreach ([0 => '未入力', 1 => 'オス', 2 => 'メス'] as $key => $label)
                  <option value="{{ $key }}" {{ $key == old('gender') ? 'selected' : '' }}>
                      {{ $label }}
                  </option>
              @endforeach

          </select>

          @error('gender')
            <div class="alert-danger">{{ $message }}</div>
          @enderror

        </div>

      </div>

      <br>
      <br>

{{-- ======================================================== --}}

      <div class="container">
        <div class="flexblock">
        {{-- 品種 --}}
        <label>品種</label><br>
        <input type="text" class="textbox-kinds" id="breed" name="breed" placeholder="例：ミックス" value="{{ old('breed') }}" />
        </div>

        @error('breed')
          <div class="alert-danger">{{ $message }}</div>
        @enderror
        <br>
        <br>

{{-- ======================================================== --}}

          <div class="flexblock">
            {{-- 所在地 --}}
            <label>所在地</label><br>
            <input type="text" class="textbox-location" id="region" name="region" placeholder="都道府県を入力（例：東京都）" value="{{ old('region') }}" />
          </div>

          @error('region')
            <div class="alert-danger">{{ $message }}</div>
          @enderror

      </div>

      <br>
      <br>

{{-- ======================================================== --}}

      {{-- 投稿ステータス --}}
      <label>投稿ステータス</label><br>
      <select name="status" id="status" class="textbox-status">
        @foreach ([0 => '里親募集中', 1 => 'お見合い中', 2 => '譲渡成立'] as $key => $label)
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
      {{-- 掲載開始日・掲載終了日 --}}
        <div class="bbb">
          <label for="start_date">掲載開始日</label><br>
          <input type="date" min="2025-10-14" max="2029-12-31" name="start_date" class="textbox-start-date" value="{{ old('start_date') }}">

          @error('start_date')
            <div class="alert-danger">{{ $message }}</div>
          @enderror

        </div>

        <label class="wave">～</label>

        <div class="ccc">
          <label for="end_date">掲載終了日</label><br>
          <input type="date" min="2025-10-14" max="2029-12-31" name="end_date" class="textbox-end-date" value="{{ old('end_date') }}">

          @error('end_date')
            <div class="alert-danger">{{ $message }}</div>
          @enderror

        </div>
      </div>

    </div>

{{-- ======================================================== --}}

    <div class="background-photo-move">

      {{-- 写真・動画 --}}
      <label for="image">写真・動画</label>
      <p>猫の写真や動画を最大4件まで<br class="br-sp">追加できます。<br class="br-sp">1枚目は写真を選択してください。</p>

{{-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| --}}
{{-- 画像アップロード --}}
{{-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| --}}

      <p>画像（最大3枚まで）、<br class="br-sp">または動画（最大1本）</p><br>

      {{-- 画像選択部分 --}}
      <div class="file-input-wrapper">
        <input type="file" name="image[]" id="image" accept="image/*" multiple class="hidden-input">
        <button type="button" class="select-file-btn" onclick="document.getElementById('image').click()">
          📷 画像を選択
        </button>
        <span id="image-selected-info" class="selected-info">未選択</span>
      </div>

        @error('image')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      <br>
      <br>

      {{-- 動画選択部分 --}}
      <div class="file-input-wrapper">
        <input type="file" name="video" id="video" accept="video/*" class="hidden-input">
        <button type="button" class="select-file-btn" onclick="document.getElementById('video').click()">
          🎥 動画を選択
        </button>
        <span id="video-selected-info" class="selected-info">未選択</span>
      </div>

      @error('video')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      {{-- プレビュー表示領域 --}}
      <div id="preview-container" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;">

        {{-- セッションに保存された画像を表示 --}}
        @if(session('temp_images'))
          @foreach(session('temp_images') as $index => $imagePath)
            <div class="preview-item" data-temp-index="{{ $index }}">
              <img src="{{ asset('storage/' . $imagePath) }}" class="preview-image" alt="一時保存画像">
              <button type="button" class="remove-btn remove-temp-image" data-index="{{ $index }}">✕</button>
            </div>
          @endforeach
        @endif

        {{-- セッションに保存された動画を表示 --}}
        @if(session('temp_video'))
          <div class="preview-item temp-video">
            <video src="{{ asset('storage/' . session('temp_video')) }}" controls class="preview-video"></video>
            <button type="button" class="remove-btn remove-temp-video">✕</button>
          </div>
        @endif
      </div>

      {{-- 一時ファイルの情報を保持 --}}
      @if(session('temp_images'))
        <input type="hidden" name="temp_images" value="{{ json_encode(session('temp_images')) }}">
      @endif
      @if(session('temp_video'))
        <input type="hidden" name="temp_video" value="{{ session('temp_video') }}">
      @endif
    </div>

{{-- |||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||| --}}

{{-- ======================================================== --}}

    <div class="background-health">

      {{-- 健康状態 --}}
      <label>健康状態</label><br><br><br>
      <label>予防接種</label><br>
      <textarea class="textbox-vaccine" rows="3" cols="30" id="vaccination" name="vaccination" placeholder="予防接種関連について詳しく記述してください。">{{ old('vaccination') }}</textarea>

      @error('vaccination')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      <br>
      <br>

{{-- ======================================================== --}}

      <label>病歴</label><br>
      <textarea class="textbox-disease" rows="3" cols="30" id="medical_history" name="medical_history" placeholder="病歴等ございましたら詳しく記述してください。">{{ old('medical_history') }}</textarea>

      @error('medical_history')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

    </div>

{{-- ======================================================== --}}

    <div class="background-description">

      {{-- 詳細説明 --}}
      <label>詳細説明</label><br>
      <textarea rows="4" cols="30" id="description" name="description" class="textbox-description" placeholder="猫の性格や特徴などを詳しく書いてください。">{{ old('description') }}</textarea>

    </div>

{{-- ======================================================== --}}

    <div class="background-price">

      {{-- 費用 --}}
      <label>譲渡費用（総額、円表記）<br>※内訳につきましては<br class="br-sp">詳細説明入力欄へ<br class="br-sp">入力をお願いします。</label>
      <input type="text" data-type="number" class="textbox-price" id="cost" name="cost" placeholder="例：30,000（円）" value="{{ old('cost') }}" />

      @error('cost')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

    </div>

{{-- ======================================== --}}
{{-- 「投稿を作成」の上部に全てのバリデーションメッセージを表示させる --}}
    {{-- @if($errors->any())
      <div class="alert alert-danger">
        @foreach($errors->all() as $message)
          <p>{{ $message }}</p>
        @endforeach
      </div>
    @endif --}}
{{-- ======================================== --}}

    {{-- 投稿を作成ボタン --}}
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
<script src="{{ asset('js/authority/catpost/create.js') }}"></script>
@endsection
