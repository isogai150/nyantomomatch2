@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/edit.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">
  <div class="main-content">
    <h2>投稿を編集</h2>
    <h3>猫の里親を募集する<br class="br-sp">投稿を編集します。</h3>

    <div class="background-form">
      <h3>基本情報</h3>


      <form action="{{ route('catpost.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- タイトル --}}
        <label for="title">タイトル</label>
        <br>
        <textarea class="textbox-title" rows="3" cols="30" id="title" name="title" placeholder="タイトルを入力">{{ old('title', $post->title) }}</textarea>
        @error('title')
          <div class="alert-danger">{{ $message }}</div>
        @enderror
        <br>
        <br>

        {{-- 年齢・性別 --}}
        <div class="container-flex">
          <div class="flexblock">
            <label for="age">年齢</label>
            <br>
            <input type="number" class="textbox-age" min="0" max="30" id="age" name="age" placeholder="例：2（才）" value="{{ old('age', $post->age) }}" />

            @error('age')
              <div class="alert-danger">{{ $message }}</div>
            @enderror

          </div>

          <div class="flexblock">
            <label for="gender">性別</label>
            <br>
            <select name="gender" id="gender" class="textbox-gender">
              <option value="">選択してください</option>

              @foreach ([0 => '未入力', 1 => 'オス', 2 => 'メス'] as $key => $label)
                <option value="{{ $key }}" {{ $key == old('gender', $post->gender) ? 'selected' : '' }}>
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

        {{-- 品種 --}}
        <div class="container">
          <div class="flexblock">
            <label>品種</label><br>
            <input type="text" class="textbox-kinds" id="breed" name="breed" placeholder="例：ミックス" value="{{ old('breed', $post->breed) }}" />
          </div>

          @error('breed')
            <div class="alert-danger">{{ $message }}</div>
          @enderror
          <br>
          <br>

          {{-- 所在地 --}}
          <div class="flexblock">
            <label>所在地</label><br>
            <input type="text" class="textbox-location" id="region" name="region" placeholder="都道府県を入力（例：東京都）" value="{{ old('region', $post->region) }}" />
          </div>

          @error('region')
            <div class="alert-danger">{{ $message }}</div>
          @enderror

        </div>

        <br>
        <br>

        {{-- 投稿ステータス --}}
        <label>投稿ステータス</label><br>
        <select name="status" id="status" class="textbox-status">
          @foreach ([0 => '里親募集中', 1 => 'お見合い中', 2 => '譲渡成立'] as $key => $label)
            <option value="{{ $key }}" {{ $key==old('status', $post->status) ? 'selected' : '' }}>
              {{ $label }}
            </option>
          @endforeach
        </select>

        <br>
        <br>
        <br>

        {{-- 掲載期間 --}}
        <div class="container-flex date-range">
          <div class="bbb">
            <label for="start_date">掲載開始日</label><br>
            <input type="date"
              min="{{ isset($post) && $post->start_date < date('Y-m-d') ? $post->start_date : date('Y-m-d') }}"
              name="start_date"
              class="textbox-start-date"
              value="{{ old('start_date', $post->start_date ? \Carbon\Carbon::parse($post->start_date)->format('Y-m-d') : '') }}">

            @error('start_date')
              <div class="alert-danger">{{ $message }}</div>
            @enderror

          </div>

          <label class="wave">～</label>

          <div class="ccc">
            <label for="end_date">掲載終了日</label><br>
            <input type="date"
              min="{{ isset($post) && $post->end_date < date('Y-m-d') ? $post->end_date : date('Y-m-d') }}"
              name="end_date"
              class="textbox-end-date"
              value="{{ old('end_date', $post->end_date ? \Carbon\Carbon::parse($post->end_date)->format('Y-m-d') : '') }}">
            {{-- <input type="date" 
              min="{{ date('Y-m-d') }}" name="end_date" class="textbox-end-date" 
              value="{{ old('end_date', $post->end_date ? \Carbon\Carbon::parse($post->end_date)->format('Y-m-d') : '') }}"> --}}

            @error('end_date')
              <div class="alert-danger">{{ $message }}</div>
            @enderror

          </div>
        </div>

    </div>

    {{-- 写真・動画 --}}
    <div class="background-photo-move">

      <label for="image">写真・動画</label>
      <br><br><br>
      <p>猫の写真を最大3枚、動画を1本まで追加できます。</p><br>

      {{-- 既存メディアのプレビュー --}}
      <div id="media-container" class="media-preview-grid">
        {{-- 既存画像 --}}
        @foreach($post->images as $image)
          <div class="preview-item">
            <img src="{{ asset($image->image_path) }}" class="preview-image" alt="猫の画像">
            <button type="button" class="remove-btn" data-type="image" data-id="{{ $image->id }}">×</button>
          </div>
        @endforeach

        {{-- 既存動画 --}}
        @foreach($post->videos as $video)
          <div class="preview-item" style="width: 150px; height: 150px;">
            <video controls class="preview-video" preload="metadata" style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; display: block;">
              <source src="{{ asset($video->video_path) }}" type="video/mp4">
              <p>お使いのブラウザは動画再生に対応していません。</p>
            </video>
            <button type="button" class="remove-btn" data-type="video" data-id="{{ $video->id }}">×</button>
          </div>
        @endforeach
      </div>

      <br>

      {{-- 選択ボタン --}}
      <button type="button" id="selectImageBtn" class="select-media-btn">画像を追加</button>
      <button type="button" id="selectVideoBtn" class="select-media-btn">動画を追加</button>
      <br><br>

      {{-- 新規画像アップロード --}}
      <input type="file" name="images[]" id="imageInput" accept="image/*" multiple style="display:none;">
      @error('images')
        <div class="alert-danger">{{ $message }}</div>
      @enderror
      @error('images.*')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      {{-- 新規動画アップロード --}}
      <input type="file" name="video" id="videoInput" accept="video/*" style="display:none;">
      @error('video')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      {{-- プレビュー表示領域 --}}
      <div id="preview-container" class="preview-grid"></div>

      {{-- 動画プレビュー専用領域（追加） --}}
      <div id="video-preview-container" class="preview-grid"></div>
    </div>


    {{-- 健康状態 --}}
    <div class="background-health">

      <label>健康状態</label><br><br><br>
      <label>予防接種</label><br>
      <textarea class="textbox-vaccine" rows="3" cols="30" id="vaccination" name="vaccination" placeholder="予防接種関連について詳しく記述してください。">{{ old('vaccination', $post->vaccination) }}</textarea>

      @error('vaccination')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

      <br>
      <br>

      <label>病歴</label><br>
      <textarea class="textbox-disease" rows="3" cols="30" id="medical_history" name="medical_history" placeholder="病歴等ございましたら詳しく記述してください。">{{ old('medical_history', $post->medical_history) }}</textarea>

      @error('medical_history')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

    </div>

    {{-- 詳細説明 --}}
    <div class="background-description">

      <label>詳細説明</label><br>
      <textarea rows="4" cols="30" id="description" name="description" class="textbox-description" placeholder="猫の性格や特徴などを詳しく書いてください。">{{ old('description', $post->description) }}</textarea>

    </div>

    {{-- 譲渡費用 --}}
    <div class="background-price">

      <label>譲渡費用（総額、円表記）<br>※内訳につきましては<br class="br-sp">詳細説明入力欄へ<br class="br-sp">入力をお願いします。</label>
      <input type="text" data-type="number" class="textbox-price" id="cost" name="cost" placeholder="例：30,000（円）" value="{{ old('cost', $post->cost) }}" />

      @error('cost')
        <div class="alert-danger">{{ $message }}</div>
      @enderror

    </div>

    {{-- 投稿の編集を確定ボタン --}}
    <div class="btn">
      <br><br>
      <button type="submit" class="botten">投稿の編集を確定</button>
    </div>

      </form>
  </div>
</div>
@endsection

@section('script')
{{-- jQueryとedit.jsの読み込み --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/authority/catpost/edit.js') }}"></script>
@endsection