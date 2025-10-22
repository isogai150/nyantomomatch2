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
        <div class="form-group">
          <label for="title">タイトル</label>
          <textarea class="textbox-title" id="title" name="title" rows="3" placeholder="タイトルを入力">{{ old('title', $post->title) }}</textarea>
          @error('title')<div class="alert-danger">{{ $message }}</div>@enderror
        </div>

        {{-- 年齢・性別 --}}
        <div class="container-flex">
          <div class="flexblock form-group">
            <label for="age">年齢</label>
            <input type="number" class="textbox-age" id="age" name="age" min="0" max="30" placeholder="例：2（才）" value="{{ old('age', $post->age) }}">
            @error('age')<div class="alert-danger">{{ $message }}</div>@enderror
          </div>

          <div class="flexblock form-group">
            <label for="gender">性別</label>
            <select class="textbox-gender" id="gender" name="gender">
              <option value="">選択してください</option>
              @foreach([0 => '未入力', 1 => 'オス', 2 => 'メス'] as $key => $label)
                <option value="{{ $key }}" {{ $key == old('gender', $post->gender) ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            @error('gender')<div class="alert-danger">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- 品種・所在地 --}}
        <div class="container-flex">
          <div class="flexblock form-group">
            <label for="breed">品種</label>
            <input type="text" class="textbox-kinds" id="breed" name="breed" placeholder="例：ミックス" value="{{ old('breed', $post->breed) }}">
            @error('breed')<div class="alert-danger">{{ $message }}</div>@enderror
          </div>

          <div class="flexblock form-group">
            <label for="region">所在地</label>
            <input type="text" class="textbox-location" id="region" name="region" placeholder="都道府県を入力（例：東京都）" value="{{ old('region', $post->region) }}">
            @error('region')<div class="alert-danger">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- 投稿ステータス --}}
        <div class="form-group">
          <label for="status">投稿ステータス</label>
          <select class="textbox-status" id="status" name="status">
            @foreach([0 => '里親募集中', 1 => 'お見合い中', 2 => '譲渡成立'] as $key => $label)
              <option value="{{ $key }}" {{ $key == old('status', $post->status) ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        {{-- 掲載期間 --}}
        <div class="container-flex date-range form-group">
          <div class="flexblock">
            <label for="start_date">掲載開始日</label>
            <input type="date" class="textbox-start-date" id="start_date" name="start_date" min="2025-10-14" max="2029-12-31" value="{{ old('start_date', $post->start_date ? \Carbon\Carbon::parse($post->start_date)->format('Y-m-d') : '') }}">
            @error('start_date')<div class="alert-danger">{{ $message }}</div>@enderror
          </div>

          <div class="flexblock">
            <label for="end_date">掲載終了日</label>
            <input type="date" class="textbox-end-date" id="end_date" name="end_date" min="2025-10-14" max="2029-12-31" value="{{ old('end_date', $post->end_date ? \Carbon\Carbon::parse($post->end_date)->format('Y-m-d') : '') }}">
            @error('end_date')<div class="alert-danger">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- 写真・動画 --}}
        <div class="background-photo-move form-group">
            <label>写真・動画</label>
            <p>猫の写真を最大3枚、動画を1本まで追加できます。</p>

            <div id="media-container" style="display:flex; flex-wrap:wrap; gap:10px;">
                {{-- 既存画像 --}}
                @foreach($post->images as $image)
                    <div class="preview-item">
                        <img src="{{ asset($image->image_path) }}" class="preview-image">
                        <button type="button" class="remove-btn" data-type="image" data-id="{{ $image->id }}">×</button>
                    </div>
                @endforeach

                {{-- 既存動画 --}}
                @foreach($post->videos as $video)
                    <div class="preview-item">
                        <video width="150" controls>
                            <source src="{{ asset($video->video_path) }}" type="video/mp4">
                        </video>
                        <button type="button" class="remove-btn" data-type="video" data-id="{{ $video->id }}">×</button>
                    </div>
                @endforeach
            </div>

            {{-- 新規メディアアップロード --}}
            @if($remainingImageSlots > 0)
                <label for="imageInput">新しい画像を追加（あと <span id="remaining-count">{{ $remainingImageSlots }}</span> 枚）</label>
                <input type="file" name="images[]" id="imageInput" accept="image/*" multiple>
            @endif

            @if($post->videos->count() === 0)
                <label for="videoInput">新しい動画を追加（最大1本）</label>
                <input type="file" name="video" id="videoInput" accept="video/*">
                <div id="video-preview-container" style="display:flex; gap:10px; margin-top:10px;"></div>
            @endif

            <div id="preview-container" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;"></div>
        </div>

        {{-- 健康状態 --}}
        <div class="background-health form-group">
          <label for="vaccination">予防接種</label>
          <textarea id="vaccination" name="vaccination" class="textbox-vaccine" rows="3">{{ old('vaccination', $post->vaccination) }}</textarea>
          @error('vaccination')<div class="alert-danger">{{ $message }}</div>@enderror

          <label for="medical_history">病歴</label>
          <textarea id="medical_history" name="medical_history" class="textbox-disease" rows="3">{{ old('medical_history', $post->medical_history) }}</textarea>
          @error('medical_history')<div class="alert-danger">{{ $message }}</div>@enderror
        </div>

        {{-- 詳細説明 --}}
        <div class="background-description form-group">
          <label for="description">詳細説明</label>
          <textarea id="description" name="description" class="textbox-description" rows="4">{{ old('description', $post->description) }}</textarea>
        </div>

        {{-- 譲渡費用 --}}
        <div class="background-price form-group">
          <label for="cost">譲渡費用（総額、円表記）</label>
          <input type="text" class="textbox-price" id="cost" name="cost" placeholder="例：30,000（円）" value="{{ old('cost', $post->cost) }}">
          @error('cost')<div class="alert-danger">{{ $message }}</div>@enderror
        </div>

        {{-- 送信ボタン --}}
        <div class="btn">
          <button type="submit" class="botten">投稿の編集を確定</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
{{-- jQueryとedit.jsの読み込み --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/authority/catpost/edit.js') }}"></script>
@endsection