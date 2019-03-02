<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="msapplication-TileColor" content="#2d88ef">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="36x36" href="/android-chrome-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/android-chrome-48x48.png">
    <link rel="icon" type="image/png" sizes="72x72" href="/android-chrome-72x72.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/android-chrome-96x96.png">
    <link rel="icon" type="image/png" sizes="128x128" href="/android-chrome-128x128.png">
    <link rel="icon" type="image/png" sizes="144x144" href="/android-chrome-144x144.png">
    <link rel="icon" type="image/png" sizes="152x152" href="/android-chrome-152x152.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="256x256" href="/android-chrome-256x256.png">
    <link rel="icon" type="image/png" sizes="384x384" href="/android-chrome-384x384.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="36x36" href="/icon-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/icon-48x48.png">
    <link rel="icon" type="image/png" sizes="72x72" href="/icon-72x72.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/icon-96x96.png">
    <link rel="icon" type="image/png" sizes="128x128" href="/icon-128x128.png">
    <link rel="icon" type="image/png" sizes="144x144" href="/icon-144x144.png">
    <link rel="icon" type="image/png" sizes="152x152" href="/icon-152x152.png">
    <link rel="icon" type="image/png" sizes="160x160" href="/icon-160x160.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="196x196" href="/icon-196x196.png">
    <link rel="icon" type="image/png" sizes="256x256" href="/icon-256x256.png">
    <link rel="icon" type="image/png" sizes="384x384" href="/icon-384x384.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/icon-512x512.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon-16x16.png">
    <link rel="icon" type="image/png" sizes="24x24" href="/icon-24x24.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon-32x32.png">
    <link rel="manifest" href="/manifest.json">

    <title>@yield('title')（{{ config('app.name', 'WELCAMO') }}）</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/welcamo.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <font class="font-weight-bold">{{ config('app.name', 'WELCAMO') }}</font>　－　@yield('title')
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ route('schedules') }}">
                                        {{ __('menu.schedules') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('entries') }}">
                                        {{ __('menu.entries') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('histories') }}">
                                        {{ __('menu.histories') }}
                                    </a>

                                    @can('admin')
                                        <a class="dropdown-item" href="{{ route('approvals') }}">
                                            {{ __('menu.approvals') }}
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="{{ route('users') }}">
                                            {{ __('menu.user') }}
                                        </a>

                                        <a class="dropdown-item" href="{{ route('purposes') }}">
                                            {{ __('menu.purpose') }}
                                        </a>

                                        <a class="dropdown-item" href="{{ route('admissions') }}">
                                            {{ __('menu.admission') }}
                                        </a>
                                    @endcan

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('change_password') }}">
                                        {{ __('menu.change_password') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('menu.logout') }}
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

        <main class="py-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
