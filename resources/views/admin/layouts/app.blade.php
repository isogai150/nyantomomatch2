<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>にゃん×とも×まっち</title>
  {{-- ファピコン --}}
  <link rel="icon" href="public/images/favicon/favicon24x24.ico">

  <link rel="icon" href="{{ asset('images/favicon/favicon24x24.ico') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  @yield('styles')
  <link rel="stylesheet" href="{{  asset('css/admin/layouts/app.css') }}">
</head>
<body>
  <header>
    <div class="header-content">
      <div class="header-logo">
        <img class="logo" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_logo_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="ヘッダーロゴ">
      </div>
      <div class="header-nav">
        <nav>
          <ul>
            <li><a href="#">ダッシュボード</a></li>
            <li><a href="#">マイページ</a></li>
            <li><a href="#">DM一覧</a></li>
            <li><a href="#">投稿一覧</a></li>
            <li><a href="#">DM通報一覧</a></li>
            <li><a href="#">投稿通報一覧</a></li>
            <li><a href="#">ユーザー一覧</a></li>
            <li><a href="#">投稿権限一覧</a></li>
            <li><a href="#">譲渡成立一覧</a></li>
            <li><a href="#" class="logout">ログアウト</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  @yield('script')
</body>
</html>