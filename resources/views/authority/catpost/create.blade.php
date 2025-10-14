@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/index.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">

{{-- ここの中にコードを書く --}}
{{-- =============================================================================================== --}}

<h2>新しい投稿を作成</h2>
<p>猫の里親を募集する投稿を作成します。詳細な情報を入力してください。<p>

<p>基本情報</p>

<p>タイトル</p>
<input type="text" class="textbox" id="name" name="name" placeholder="氏名を入力してください" value="{{ old('name') }}" />
<p>年齢</p>
<input type="text" class="textbox" id="name" name="name" placeholder="氏名を入力してください" value="{{ old('name') }}" />
<p>性別</p>

<p>品種</p>
<input type="text" class="textbox" id="name" name="name" placeholder="氏名を入力してください" value="{{ old('name') }}" />
<p>所在地</p>
<input type="text" class="textbox" id="name" name="name" placeholder="氏名を入力してください" value="{{ old('name') }}" />
<p>投稿ステータス</p>

<p>掲載開始日</p>
<p>掲載終了日</p>

<p>写真・動画</p>
<p>猫の写真や動画を最大4件まで追加できます。1枚目は写真を選択してください。</p>

<p>健康状態</p>
<p>予防接種</p>
<p>病歴</p>

<p>詳細説明</p>

<p>譲渡費用（総額）※内訳につきましては詳細説明入力欄へ入力をお願いします。</p>

{{-- =============================================================================================== --}}

</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script src="{{ asset('ここにファイルパスの記述') }}"></script>
@endsection
