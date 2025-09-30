<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>にゃん×とも×まっち</title>
  <link rel="icon" href="public/images/favicon/favicon24x24.ico">

  <link rel="icon" href="{{ asset('/images/favicon/favicon24x24.ico') }}">
  
  @yield('styles')
  <link rel="stylesheet" href="public/css/layouts/app.css">
</head>
<body>
  <header>
    <div class="header-raight">
      <img src="public\images\logo\20250922_1357_にゃんともマッチ_logo_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png" alt="ヘッダーロゴ">
      <img src="public\images\logo\20250922_1357_にゃんともマッチ_text_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png" alt="ヘッダーテキスト">
    </div>
    <div class="header-left">
      <div class="hamburger-menu">
        <nav>
          <ul>
            {{-- @if(Auth::check()->role === 1) --}}
            @if(Auth::check())
            <li><a href="#">マイページ</a></li>
            <li><a href="#">DM一覧</a></li>
            <li><a href="#">お気に入り</a></li>
            <li><a href="3">ログアウト</a></li>
            @elseif ()
            <li><a href="#">マイページ</a></li>
            <li><a href="#">自分の投稿</a></li>
            <li><a href="#"></a>投稿の作成</li>
            <li><a href="#">DM一覧</a></li>
            <li><a href="#">お気に入り</a></li>
            <li><a href="3">ログアウト</a></li>
            @else
            <li><a href="#">新規登録</a></li>
            <li><a href="#">ログイン</a></li>
            @endif
          </ul>
        </nav>
      </div>
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <footer>
    <div class="footer-raight">
      <img src="public\images\logo\20250922_1357_にゃんともマッチ_logo_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png" alt="ヘッダーロゴ">
      <img src="public\images\logo\20250922_1357_にゃんともマッチ_text_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png" alt="ヘッダーテキスト">
    </div>
    <div class="footer-left">
      <nav>
        <ul>
          <li><a href="#">トップページ</a></li>
          <li><a href="#">お問い合わせ</a></li>
          <li><a href="#">利用規約</a></li>
          <li><a href="#">プライバシーポリシー</a></li>
        </ul>
      </nav>
    </div>
    <div class="footer-bottm">
      <p>Copyright&copy2024 にゃん×とも×まっち.</p>
    </div>
  </footer>

    @yield('javescript')
</body>
</html>