<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>にゃん×とも×まっち</title>

  <link rel="icon" href="{{ asset('/favicons/favicon.ico') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  @yield('styles')
  <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
</head>

<body>
  <header>
    <div class="header-content">
      <div class="header-left flex">
        <a href="{{ route('posts.index') }}">
          <img class="header-logo" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_logo_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="ヘッダーロゴ">
        </a>
        <a href="{{ route('posts.index') }}">
          <img class="header-text" src="{{ asset('images/logo/20250922_1357_にゃんともマッチ_text_simple_compose_01k5qwesjqenp97krbkk8gfe7m.png') }}" alt="ヘッダーテキスト">
        </a>
      </div>

      <div class="header-raight">
        <div class="hamburger-menu">
          <button id="hamburger-btn" class="hamburger">
            <span></span><span></span><span></span>
          </button>
          <nav>
            <ul>
              @if(Auth::check())
                @php $userRole = Auth::user()->role ?? null; @endphp

                @if($userRole === 0)
                  <li><a href="{{ route('mypage.index') }}">マイページ</a></li>
                  <li><a href="{{ route('dm.index') }}">DM一覧</a></li>
                  <li><a href="{{ route('favorites.index') }}">お気に入り</a></li>
                  <li><a href="javascript:void(0)" id="logout-btn">ログアウト</a></li>
                @elseif($userRole === 1)
                  <li><a href="{{ route('mypage.index') }}">マイページ</a></li>
                  <li><a href="{{ route('mycatpost.index') }}">自分の投稿</a></li>
                  <li><a href="{{ route('posts.create') }}">投稿の作成</a></li>
                  <li><a href="{{ route('dm.index') }}">DM一覧</a></li>
                  <li><a href="{{ route('favorites.index') }}">お気に入り</a></li>
                  <li><a href="javascript:void(0)" id="logout-btn">ログアウト</a></li>
                @endif

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                  @csrf
                </form>
              @else
                <li><a href="{{ route('register') }}">新規登録</a></li>
                <li><a href="{{ route('login') }}">ログイン</a></li>
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
      <li><a href="{{ route('posts.index') }}">トップページ</a></li>
      <li><a href="#" class="footer-modal-open" data-target="termsModal">利用規約</a></li>
      <li><a href="#" class="footer-modal-open" data-target="privacyModal">プライバシーポリシー</a></li>
    </ul>
  </nav>
</div>
      </div>
      <div class="footer-bottm">
        <p>Copyright&copy;2024 にゃん×とも×まっち.</p>
      </div>
    </div>
  </footer>

{{-- ===== モーダル部分（footer外） ===== --}}
<div id="termsModal" class="footer-modal">
  <div class="footer-modal-content">
    <span class="footer-close">&times;</span>
    <h2>利用規約</h2>
    <p>
      第１章 総則<br>
      <strong>第1条（目的）</strong><br>
      本利用規約（以下「本規約」といいます。）は、にゃん×とも×まっち（以下「当サービス」といいます。）が提供する本サービスの利用条件を定めるものです。ユーザーの皆様（以下「ユーザー」といいます。）には、本規約に従って本サービスをご利用いただきます。<br><br>

      <strong>第2条（規約への同意）</strong><br>
      ユーザーが本サービスを利用した時点で、本規約のすべてに同意したものとみなします。<br><br>

      <strong>第3条（利用登録）</strong><br>
      ユーザーは、当サービスが定める方法により登録手続きを行うものとします。登録情報に虚偽、誤記、漏れがある場合、当サービスは登録を拒否または抹消できるものとします。<br><br>

      <strong>第4条（禁止事項）</strong><br>
      ユーザーは以下の行為を行ってはなりません。<br>
      ・法令または公序良俗に反する行為<br>
      ・他者への誹謗中傷・嫌がらせ行為<br>
      ・虚偽の情報の投稿<br>
      ・運営を妨害する行為<br><br>

      <strong>第5条（投稿・チャット・譲渡に関するルール）</strong><br>
      ユーザーは、チャット機能、譲渡希望および投稿機能を利用する際、誠実かつ責任を持って行動するものとします。当サービスは、ユーザー間で生じたトラブルについて一切の責任を負いません。<br><br>

      <strong>第6条（サービス変更・中断・終了）</strong><br>
      当サービスは、予告なく内容を変更・中断・終了する場合があります。これによりユーザーに損害が生じても、当サービスは責任を負いません。<br><br>

      <strong>第7条（免責事項）</strong><br>
      当サービスは、サービスの完全性や有用性、安全性を保証するものではありません。本サービスの利用により発生した損害について、一切の責任を負いません。<br><br>

      <strong>第8条（準拠法および管轄）</strong><br>
      本規約は日本法に準拠します。本サービスに関して紛争が生じた場合は、○○地方裁判所を専属的管轄裁判所とします。<br><br>

      附則：本規約は2025年11月4日より施行します。
    </p>
  </div>
</div>

<div id="privacyModal" class="footer-modal">
  <div class="footer-modal-content">
    <span class="footer-close">&times;</span>
    <h2>プライバシーポリシー</h2>
    <p>
      にゃん×とも×まっち（以下「当サービス」といいます。）は、ユーザーの個人情報を適切に取り扱い、安心してご利用いただけるよう努めます。<br><br>

      <strong>第1条（個人情報の定義）</strong><br>
      本ポリシーにおける「個人情報」とは、個人情報保護法に基づく識別可能な情報をいいます。<br><br>

      <strong>第2条（収集する情報）</strong><br>
      当サービスは、登録情報（氏名・メールアドレス・プロフィール情報など）、チャット・譲渡履歴、Cookie・アクセスログ等を収集する場合があります。<br><br>

      <strong>第3条（利用目的）</strong><br>
      収集した個人情報は以下の目的で利用します。<br>
      ・本サービスの提供、運営、改善<br>
      ・本人確認および不正利用防止<br>
      ・お問い合わせ対応<br>
      ・法令への対応<br><br>

      <strong>第4条（第三者提供）</strong><br>
      当サービスは、次の場合を除き、ユーザーの同意なく個人情報を第三者に提供しません。<br>
      ・法令に基づく場合<br>
      ・公共の利益のために必要な場合<br>
      ・事業承継などで必要な場合<br><br>

      <strong>第5条（安全管理）</strong><br>
      個人情報の漏えい・紛失等を防止するため、アクセス制限や暗号化などの安全管理措置を講じます。<br><br>

      <strong>第6条（開示・訂正・削除）</strong><br>
      ユーザーは、当サービスに対して自己の個人情報の開示・訂正・削除を請求できます。<br><br>

      <strong>第7条（Cookie等の利用）</strong><br>
      本サイトは、利便性向上・アクセス解析のためにCookie等を使用します。ブラウザ設定で無効化することも可能です。<br><br>

      <strong>第8条（ポリシーの変更）</strong><br>
      当サービスは、必要に応じて本ポリシーを改定することがあります。改定後はウェブサイト上で告知し、効力を発生します。<br><br>

      附則：本ポリシーは2025年11月4日より施行します。
    </p>
  </div>
</div>


  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('js/layouts/app.js') }}"></script>
  @yield('script')
</body>
</html>
