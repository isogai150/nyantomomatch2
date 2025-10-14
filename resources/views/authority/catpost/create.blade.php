@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/authority/catpost/create.css') }}">
@endsection

@section('content')
<div class="backgroundcolor-position">

{{-- ここの中にコードを書く --}}
{{-- =============================================================================================== --}}

<h2>新しい投稿を作成</h2>
<p>猫の里親を募集する投稿を作成します。詳細な情報を入力してください。<p>

<p>基本情報</p>

<p>タイトル</p>
<input type="text" class="textbox" id="title" name="title" placeholder="タイトルを入力" value="{{ old('title') }}" />

<p>年齢</p>
<input type="number" class="textbox" id="age" name="age" placeholder="例：2歳" value="{{ old('age') }}" />


{{-- =============================================================================================== --}}
{{-- =============================================================================================== --}}
{{-- <p>性別</p>
<input type="text" placeholder="未入力・オス・メス"/> --}}

      <label for="gender">性別</label>
      <select name="gender" id="gender" class="form-control">

      @foreach(\App\Models\Post::STATUS as $key => $val)
      <option value="{{ $key }}" {{ $key==old('gender', $task->status) ? 'selected' : '' }}
        >
        {{ $val['label'] }}
      </option>
      @endforeach
      </select>
{{-- =============================================================================================== --}}
{{-- =============================================================================================== --}}


<p>品種</p>
<input type="text" class="textbox" id="kinds" name="kinds" placeholder="例：ミックス" value="{{ old('kinds') }}" />

<p>所在地</p>
<input type="text" class="textbox" id="location" name="location" placeholder="都道府県を入力" value="{{ old('location') }}" />

{{-- =============================================================================================== --}}
{{-- =============================================================================================== --}}
<p>投稿ステータス</p>
<input type="text" placeholder="募集中・トライアル中・譲渡済み"/>
{{-- =============================================================================================== --}}
{{-- =============================================================================================== --}}

<p>掲載開始日</p>
<p>掲載終了日</p>

<p>写真・動画</p>
<p>猫の写真や動画を最大4件まで追加できます。1枚目は写真を選択してください。</p>

<p>健康状態</p>
<p>予防接種</p>
<input type="text" class="textbox" id="location" name="location" placeholder="予防接種関連について詳しく記述してください。" value="{{ old('location') }}" />

<p>病歴</p>
<input type="text" class="textbox" id="location" name="location" placeholder="病歴等ございましたら詳しく記述してください。" value="{{ old('location') }}" />

<p>詳細説明</p>

<p>譲渡費用（総額）※内訳につきましては詳細説明入力欄へ入力をお願いします。</p>
<input type="text" data-type="number" class="textbox" id="price" name="price" placeholder="例：30,000円" value="{{ old('price') }}" />


{{-- =============================================================================================== --}}
{{-- bladeここまで --}}

</div>
@endsection

{{-- js使うときは書く使わないときは書かなくて良い --}}
@section('script')
<script>

/* data-type='number'のテキストボックスを取得 */
var NBR = document.querySelectorAll( "[data-type='number']" );

/* 入力時に実行する処理に checkInput を指定 */
for(var i=0;i<NBR.length;i++){ NBR[ i ].oninput = fmtInput }

/* 入力時に実行する処理 checkInput を作る */
function fmtInput( evt ){

// 入力が行われたテキストボックスを取得
var target = evt.target;

// 入力された値を取得
var data = target.value[ target.value.length-1 ];

// 入力された値が数値以外であれば受け付けない
if( ! data.match( /[0-9]/ ) ){
target.value = target.value.slice( 0, target.value.length-1 );
}

// テキストボックスの数値を３桁区切りに変換
target.value = target.value
.replace( /,/g, '' )
.replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,' );
}

</script>
@endsection
