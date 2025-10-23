<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
{{-- ================================================================ --}}
{{-- 1022 猫の投稿の編集 追加分 --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
{{-- ================================================================ --}}
  <title>にゃん×とも×まっち</title>
  {{-- ファピコン --}}
  <link rel="icon" href="public/images/favicon/favicon24x24.ico">

  <link rel="icon" href="{{ asset('images/favicon/favicon24x24.ico') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  @yield('styles')
  <link rel="stylesheet" href="{{  asset('css/layouts/app.css') }}">
</head>
<body>
  <header>
    <div class="header-content">
      <div class="header-left flex">
        {{-- ロゴクリックしたらtopページへ戻る --}}
        <a href="#"><img class="header-logo" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_logo_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="ヘッダーロゴ"></a>
        <a href="#"><img class="header-text" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_text_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="ヘッダーテキスト"></a>
      </div>
      <div class="header-raight">
        <div class="hamburger-menu">
          <button id="hamburger-btn" class="hamburger">
            <span></span><span></span><span></span>
          </button>
          <nav>
            <ul>
              {{-- ▼エラーになるためにやっていないが、一般ユーザーと投稿権限ユーザーの機能完成後以下のコードにする --}}
              {{-- @if(Auth::check()->role === 0)
              <li><a href="#">マイページ</a></li>
              <li><a href="#">DM一覧</a></li>
              <li><a href="#">お気に入り</a></li>
              <li><a href="3">ログアウト</a></li>
              @elseif(Auth::check()->role === 1)
              <li><a href="#">マイページ</a></li>
              <li><a href="#">自分の投稿</a></li>
              <li><a href="#"></a>投稿の作成</li>
              <li><a href="#">DM一覧</a></li>
              <li><a href="#">お気に入り</a></li>
              <li><a href="3">ログアウト</a></li>
              @else
              <li><a href="#">新規登録</a></li>
              <li><a href="#">ログイン</a></li>
              @endif --}}

              {{-- 機能できたらこちらは削除する --}}
              @if(Auth::check())
              <li><a href="#">マイページ</a></li>
              <li><a href="#">DM一覧</a></li>
              <li><a href="#">お気に入り</a></li>
              <li><a href="#" id="logout-btn">ログアウト</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
                </form>

              @else
              <li><a href="#">新規登録</a></li>
              <li><a href="#">ログイン</a></li>
              @endif
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <footer>
    <div class="footer-content">
      <div class="footer-top flex">
        <div class="footer-left flex">
          <img class="footer-logo" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_logo_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="フッターロゴ">
          <img class="footer-text" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_text_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="フッターテキスト">
        </div>
        <div class="footer-raight">
          <nav>
            <ul>
              <li><a href="#">トップページ</a></li>
              <li><a href="#">お問い合わせ</a></li>
              <li><a href="#">利用規約</a></li>
              <li><a href="#">プライバシーポリシー</a></li>
            </ul>
          </nav>
        </div>
      </div>
      <div class="footer-bottm">
        <p>Copyright&copy2024 にゃん×とも×まっち.</p>
      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('js/layouts/app.js') }}"></script>
  @yield('script')
</body>
</html>