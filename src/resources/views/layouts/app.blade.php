<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-market-app</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body>
    <header class="header">
        <h1 class="header-ttl">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.svg') }}" alt="logo">
            </a>
        </h1>

        @if (!isset($hideHeaderSearch) || !$hideHeaderSearch)
        <div class="header-search">
            @if(Request::is('mypage*'))
            <!-- マイページ用検索フォーム -->
            <form action="{{ route('mypage') }}" method="GET">
                <input class="header-search__text"
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="なにをお探しですか？">
                <input type="hidden" name="page" value="{{ request('page', 'sell') }}">
            </form>
            @else
            <!-- indexページ用検索フォーム -->
            <form action="{{ route('items.index') }}" method="GET">
                <input class="header-search__text"
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="なにをお探しですか？">
                <input type="hidden" name="tab" value="{{ $tab ?? 'recommend' }}">
            </form>
            @endif
        </div>
        @endif

        @if (!isset($hideHeaderNav) || !$hideHeaderNav)
        <nav class="header-nav">
            <ul class="header-nav-list">
                @if (Auth::check())
                <li class="header-nav-item">
                    <form class="header-nav__form" action="/logout" method="post">
                        @csrf
                        <button class="header-nav__button">ログアウト</button>
                    </form>
                </li>
                @else
                <li class="header-nav-item"><a href="/login">ログイン</a></li>
                @endif
                <li class="header-nav-item"><a href="/mypage">マイページ</a></li>
                <li class="header-nav-item"><a href="/sell" class="header-nav__button-link">出品</a></li>
            </ul>
        </nav>
        @endif
    </header>

    <main>
        @yield('content')
    </main>

    @yield('js')
</body>

</html>