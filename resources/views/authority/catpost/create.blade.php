@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/create.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">
  <div class="main-content">
    <h2>新しい投稿aを作成</h2>
    <h3>猫の里親を募集する<br class="br-sp">投稿を作成してください。</h3>

    <div class="background-form">
      <h3>基本情報</h3><br>

      <form action="{{ route('catpost.store') }}" method="POST" enctype="multipart/form-data" id="catpostForm">
        @csrf

        {{-- タイトル --}}
        <label for="title">タイトル</label><br>
        <textarea class="textbox-title" rows="3" id="title" name="title" placeholder="タイトルを入力">{{ old('title') }}</textarea>
        @error('title')
          <div class="alert-danger">{{ $message }}</div>
        @enderror
        <br><br>

        <div class="container-flex">
          <div class="flexblock">
            {{-- 年齢 --}}
            <label for="age">年齢</label><br>
            <input type="number" class="textbox-age" min="0" max="30" id="age" name="age" placeholder="例：2（才）" value="{{ old('age') }}">
            @error('age')
              <div class="alert-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="flexblock">
            {{-- 性別 --}}
            <label for="gender">性別</label><br>
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

        <br><br>

        <div class="container">
          <div class="flexblock">
            {{-- 品種 --}}
            <label>品種</label><br>
            <input type="text" class="textbox-kinds" id="breed" name="breed" placeholder="例：ミックス" value="{{ old('breed') }}">
          </div>
          @error('breed')
            <div class="alert-danger">{{ $message }}</div>
          @enderror
          <br><br>

          <div class="flexblock">
            {{-- 所在地 --}}
            <label>所在地</label><br>
            <input type="text" class="textbox-location" id="region" name="region" placeholder="都道府県を入力（例：東京都）" value="{{ old('region') }}">
          </div>
          @error('region')
            <div class="alert-danger">{{ $message }}</div>
          @enderror
        </div>

        <br><br>

        {{-- 投稿ステータス --}}
        <label>投稿ステータス</label><br>
        <select name="status" id="status" class="textbox-status">
          @foreach ([0 => '里親募集中', 1 => 'お見合い中', 2 => '譲渡成立'] as $key => $label)
            <option value="{{ $key }}" {{ $key == old('status') ? 'selected' : '' }}>
              {{ $label }}
            </option>
          @endforeach
        </select>

        <br><br><br>

        <div class="container-flex date-range">
          <div class="bbb">
            <label for="start_date">掲載開始日</label><br>
            <input type="date" min="{{ date('Y-m-d') }}" name="start_date" class="textbox-start-date" value="{{ old('start_date') }}">
            @error('start_date')
              <div class="alert-danger">{{ $message }}</div>
            @enderror
          </div>

          <label class="wave">～</label>

          <div class="ccc">
            <label for="end_date">掲載終了日</label><br>
            <input type="date" min="{{ date('Y-m-d') }}" name="end_date" class="textbox-end-date" value="{{ old('end_date') }}">
            @error('end_date')
              <div class="alert-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    <div class="background-photo-move">
      <label for="image">写真・動画</label><br><br><br>
      <p>猫の写真を最大3枚、動画を1本まで追加できます。<br>
      <span style="color: red; font-weight: bold;">※最低1枚の画像を選択してください。</span></p><br>

      <button type="button" id="selectImageBtn" class="select-media-btn">画像を選択</button>
      <button type="button" id="selectVideoBtn" class="select-media-btn">動画を選択</button>
      <br><br>

      {{-- 非表示input --}}
      <input type="file" name="image[]" id="imageInput" accept="image/*" multiple style="display:none;">
      @error('image')
        <div class="alert-danger">{{ $message }}</div>
      @enderror
      @error('image.*')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      <input type="file" name="video" id="videoInput" accept="video/*" style="display:none;">
      @error('video')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      <div id="preview-container" class="preview-grid"></div>
      <div id="video-preview-container" class="preview-grid"></div>

      <p id="remaining-count" style="margin-top: 15px; font-weight: bold; color: #503322;">
        残り画像の追加可能枚数: <span id="remaining-number">3</span>枚
      </p>
    </div>

    {{-- ======================================================== --}}
    <div class="background-health">
      <label>健康状態</label><br><br><br>
      <label>予防接種</label><br>
      <textarea class="textbox-vaccine" id="vaccination" name="vaccination" placeholder="予防接種関連について">{{ old('vaccination') }}</textarea>
      @error('vaccination')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      <br><br>
      <label>病歴</label><br>
      <textarea class="textbox-disease" id="medical_history" name="medical_history" placeholder="病歴など">{{ old('medical_history') }}</textarea>
      @error('medical_history')
        <div class="alert-danger">{{ $message }}</div>
      @enderror
    </div>

    <div class="background-description">
      <label>詳細説明</label><br>
      <textarea id="description" name="description" class="textbox-description" placeholder="猫の性格や特徴など">{{ old('description') }}</textarea>
    </div>

    <div class="background-price">
      <label>譲渡費用（円）</label><br>
      <input type="text" class="textbox-price" id="cost" name="cost" placeholder="例：30,000" value="{{ old('cost') }}">
      @error('cost')
        <div class="alert-danger">{{ $message }}</div>
      @enderror
    </div>

    {{-- 投稿を作成ボタン --}}
    <div class="btn">
      <br><br>
      <button type="submit" class="botten">投稿を作成</button>
    </div>
    </form>
  </div>
</div>
@endsection

@section('script')
<script>
    // バリデーションエラーがある場合のみtrue
    window.keepStorage = {{ $errors->any() ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/authority/catpost/create.js') }}"></script>
@endsection
