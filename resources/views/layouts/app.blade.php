<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- ↓ Bootstrap(app.js) -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <!-- ↓ Bootstrap(app.css) -->
    <!--Laravelではasset関数を使ってCSSファイルを読み込む方法が推奨されています。-->
    <!--asset関数を使うことによりpublicディレクトリが返ってきます。-->
    <!--そのためpublicディレクトリに続くパスを書くと狙ったファイルを読み込むことができます。-->
    <!--公式：asset関数は、現在のリクエストのスキーマ(HTTPかHTTPS)を使い、アセットへのURLを生成します。-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/utility.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    <!--@section('css')が埋め込まれる-->
    @yield('css')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm">
            <!--containerを追加することによってカラムの幅が制限されて左右に余白ができます。-->
            <div class="container">
                <!--公式：url関数は指定したパスへの完全なURLを生成します。-->
                <!--　　　$url = url('user/profile');-->
                <!--　　　$url = url('user/profile', [1]);-->
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class='navbar-logo' src="{{ asset('images/logo.png') }}">
                    <!--config/app.phpのnameがあれば.envのAPP_NAMEを表示し、設定されていなければLaravelと表示しなさいという意味-->
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                            <li class="nav-item">
                                <!--公式：route関数は指定された名前付きルートへのURLを生成します。-->
                                <!--　　　$url = route('routeName');-->
                                <!-- create で/routes/web.phpの Route::get('/review', 'ReviewController@create')->name('create'); に繋がり、-->
                                <!--ReviewControllerのcreate アクションが実行。return view('review');で、review.blade.phpが読み込まれる-->
                                <a href="{{ route('create') }}" class='nav-link'>レビューを書く</a>
                            </li>
                        @guest
                            <li class="nav-item">
                                <!--公式：__関数は、指定した翻訳文字列か翻訳キーをローカリゼーションファイルを使用し、翻訳します。-->
                                <!--　　　echo __('Welcome to our application');-->
                                <!--　　　echo __('messages.welcome');-->
                                <!--　　　指定した翻訳文字列や翻訳キーが存在しない場合、__関数は指定した値をそのまま返します。-->
                                <!--　　　たとえば、上記の場合に翻訳キーが存在しなければ、__関数はmessages.welcomeを返します。-->
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <!--公式：auth関数は、authenticatorのインスタンスを返します。利便のため、代わりにAuthファサードを使用することもできます。-->
                                    <!--　　　$user = auth()->user();-->
                                    <!--ここではユーザー名が返っているかと-->
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="main">
            <!-- フラッシュメッセージ -->
            <!--公式：session関数はセッションへ値を設定、もしくは取得するために使用します。-->
            <!--　　　$value = session('key');-->
            <!--flash_message は ReviewControllerのstore関数の返り値-->
            <!--セッションとは簡単に書くと各ユーザーの状態を一時的にサーバーに保存しておく仕組みです。-->
            <!--このセッションを一時的に使い、レビューを投稿してトップページに戻ってきた時だけメッセージを表示するのがフラッシュメッセージです。-->
            @if (session('flash_message'))
                <div class="flash_message bg-success text-center py-3 my-0 mb30">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--@section('content')が埋め込まれる部分-->
            @yield('content')
        </main>
        
        <!--p20は便利クラス（ユーティリティークラス）/public/css/utility.css -->
        <footer class='footer p20'>
            <small class='copyright'>Laravel Book Reviews 2019 copyright</small>
        </footer>
        
    </div>
</body>
</html>

<!--公式 blade詳細　https://readouble.com/laravel/6.x/ja/blade.html-->

<!--公式　asset関数 色々書いてある！ https://readouble.com/laravel/5.5/ja/helpers.html#method-asset-->

<!--Cookieとセッションの違い　https://qiita.com/7968/items/ce03feb17c8eaa6e4672-->