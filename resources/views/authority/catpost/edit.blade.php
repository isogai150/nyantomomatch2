{{-- 投稿編集ページ --}}
@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/edit.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">
  {{-- =================================================================================================== --}}
  <div class="main-content">
    <h2>投稿を編集</h2>
    <h3>猫の里親を募集する<br class="br-sp">投稿を編集します。</h3>

    <div class="background-form">
      <h3>基本情報</h3>

      {{-- 投稿編集フォーム --}}
      <form action="{{ route('catpost.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ====== タイトル ====== --}}
        <label for="title">タイトル</label><br>
        <textarea class="textbox-title" rows="3" id="title" name="title">{{ old('title', $post->title) }}</textarea>
        @error('title') <div class="alert-danger">{{ $message }}</div> @enderror
        <br><br>

        {{-- ====== 年齢・性別 ====== --}}
        <div class="container-flex">
          <div class="flexblock">
            <label for="age">年齢</label><br>
            <input type="number" class="textbox-age" min="0" max="30" id="age" name="age"
              value="{{ old('age', $post->age) }}" />
            @error('age') <div class="alert-danger">{{ $message }}</div> @enderror
          </div>

          <div class="flexblock">
            <label for="gender">性別</label><br>
            <select name="gender" id="gender" class="textbox-gender">
              <option value="">選択してください</option>
              @foreach ([0 => '未入力', 1 => 'オス', 2 => 'メス'] as $key => $label)
              <option value="{{ $key }}" {{ $key == old('gender', $post->gender) ? 'selected' : '' }}>
                {{ $label }}
              </option>
              @endforeach
            </select>
            @error('gender') <div class="alert-danger">{{ $message }}</div> @enderror
          </div>
        </div>

        <br><br>

        {{-- ====== 品種・所在地 ====== --}}
        <div class="container">
          <div class="flexblock">
            <label>品種</label><br>
            <input type="text" class="textbox-kinds" name="breed" value="{{ old('breed', $post->breed) }}" />
          </div>
          @error('breed') <div class="alert-danger">{{ $message }}</div> @enderror
          <br><br>

          <div class="flexblock">
            <label>所在地</label><br>
            <input type="text" class="textbox-location" name="region" value="{{ old('region', $post->region) }}" />
          </div>
          @error('region') <div class="alert-danger">{{ $message }}</div> @enderror
        </div>

        <br><br>

        {{-- ====== 投稿ステータス ====== --}}
        <label>投稿ステータス</label><br>
        <select name="status" class="textbox-status">
          @foreach ([0 => '里親募集中', 1 => 'お見合い中', 2 => '譲渡成立'] as $key => $label)
          <option value="{{ $key }}" {{ $key==old('status', $post->status) ? 'selected' : '' }}>
            {{ $label }}
          </option>
          @endforeach
        </select>
        <br><br><br>

        {{-- ====== 掲載期間 ====== --}}
        <div class="container-flex date-range">
          <div class="bbb">
            <label for="start_date">掲載開始日</label><br>
            <input type="date" name="start_date"
              value="{{ old('start_date', $post->start_date ? \Carbon\Carbon::parse($post->start_date)->format('Y-m-d') : '') }}">
          </div>

          <label class="wave">～</label>

          <div class="ccc">
            <label for="end_date">掲載終了日</label><br>
            <input type="date" name="end_date"
              value="{{ old('end_date', $post->end_date ? \Carbon\Carbon::parse($post->end_date)->format('Y-m-d') : '') }}">
          </div>
        </div>

    </div>

    {{-- ======================================================== --}}
    {{-- 写真・動画 --}}
    <div class="background-photo-move">
      <label>写真・動画</label><br><br>
      <p>猫の写真を最大3枚、動画を1本まで追加できます。</p><br>

      {{-- 既存メディア --}}
      <div id="media-container" class="media-preview-grid">
        {{-- 既存画像 --}}
        @foreach($post->images as $image)
        <div class="preview-item">
          <img src="{{ Storage::disk(config('filesystems.default'))->url('post_images/' . $image->image_path) }}"
            class="preview-image" alt="猫の画像">
          <button type="button" class="remove-btn" data-type="image" data-id="{{ $image->id }}">×</button>
        </div>
        @endforeach

        {{-- 既存動画 --}}
        @foreach($post->videos as $video)
        <div class="preview-item" style="width:150px; height:150px;">
          <video controls preload="metadata" class="preview-video"
            style="width:150px; height:150px; object-fit:cover; border-radius:10px; display:block;">
            <source src="{{ Storage::disk(config('filesystems.default'))->url('post_videos/' . $video->video_path) }}"
              type="video/mp4">
            お使いのブラウザは動画再生に対応していません。
          </video>
          <button type="button" class="remove-btn" data-type="video" data-id="{{ $video->id }}">×</button>
        </div>
        @endforeach
      </div>

      <br>
      {{-- 追加ボタン --}}
      <button type="button" id="selectImageBtn" class="select-media-btn">画像を追加</button>
      <button type="button" id="selectVideoBtn" class="select-media-btn">動画を追加</button>
      <br><br>

      {{-- 新規アップロード --}}
      <input type="file" name="images[]" id="imageInput" accept="image/*" multiple style="display:none;">
      <input type="file" name="video" id="videoInput" accept="video/*" style="display:none;">
      <div id="preview-container" class="preview-grid"></div>
      <div id="video-preview-container" class="preview-grid"></div>
    </div>

    {{-- ======================================================== --}}
    {{-- 健康状態 --}}
    <div class="background-health">
      <label>健康状態</label><br><br>
      <label>予防接種</label><br>
      <textarea name="vaccination" class="textbox-vaccine" rows="3">{{ old('vaccination', $post->vaccination) }}</textarea>
      @error('vaccination') <div class="alert-danger">{{ $message }}</div> @enderror
      <br><br>

      <label>病歴</label><br>
      <textarea name="medical_history" class="textbox-disease" rows="3">{{ old('medical_history', $post->medical_history) }}</textarea>
      @error('medical_history') <div class="alert-danger">{{ $message }}</div> @enderror
    </div>

    {{-- 詳細説明 --}}
    <div class="background-description">
      <label>詳細説明</label><br>
      <textarea name="description" class="textbox-description" rows="4">{{ old('description', $post->description) }}</textarea>
    </div>

    {{-- 譲渡費用 --}}
    <div class="background-price">
      <label>譲渡費用（総額）</label>
      <input type="text" name="cost" class="textbox-price" value="{{ old('cost', $post->cost) }}">
      @error('cost') <div class="alert-danger">{{ $message }}</div> @enderror
    </div>

    {{-- 送信ボタン --}}
    <div class="btn">
      <button type="submit" class="botten">投稿の編集を確定</button>
    </div>

    </form>
  </div>
</div>
@endsection

@section('script')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="{{ asset('js/authority/catpost/edit.js') }}"></script>
@endsection
