<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (isset($content) && isset($meta_title) && isset($meta_description))
    <meta name="title" content="{{$meta_title}}" />
    <meta name="description" content="{{$meta_description}}" />
    <meta property="og:image" content="/images/{{$image_url}}"/>
    <!--blog content meta title and description -->
    @else
    <meta name="description" content="MTG Magic the Gathering Deck Builder. Build and share your MTG Standard Decks." />
    <meta name="title" content="MTG Deck Builder, share your Magic the Gathering Standard Decks." />
    @endif
    <link rel="icon" href="https://magicdb.us/images/onlycards.ico">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MTG Deck Builder') }}</title>

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <!-- Include the Quill library -->
    <!-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> -->

    <!-- google adsense -->
    <!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-3996377264240146",
            enable_page_level_ads: true
        });
    </script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="https://magicdb.us/images/onlycards.png" alt="magicdb.us">
                    <!-- {{ config('app.name', 'magicdb.us') }} -->
                    <span>magicdb.us</span>
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
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else
                        @if(Auth::user()->type == 'admin')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('admin') }}">
                                        {{ __('Admin') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('editor') }}">
                                    {{ __('Editor Panel') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('alldecks') }}">
                                        {{ __('All Decks') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('decks') }}">
                                        {{ __('My Decks') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('catalog') }}">
                                        {{ __('My Catalog') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('cards') }}">
                                        {{ __('Build a Deck') }}
                                    </a>
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
                        @elseif(Auth::user()->type == 'editor')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('editor') }}">
                                        {{ __('Editor Panel') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('alldecks') }}">
                                        {{ __('All Decks') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('decks') }}">
                                        {{ __('My Decks') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('cards') }}">
                                        {{ __('Build a Deck') }}
                                    </a>
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
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('alldecks') }}">
                                        {{ __('All Decks') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('decks') }}">
                                        {{ __('My Decks') }}
                                    </a>
                                <a class="dropdown-item" href="{{ route('cards') }}">
                                        {{ __('Build a Deck') }}
                                    </a>
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
                        @endif
                        @endguest     
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
        <!-- TCGPlayer.com Ad -->
        <div align="center">            
            <script id="tcg-a2"
                data-affcode="MAGICDB"
                data-width="728"
                data-height="90"
                data-category-id="1"
                data-campaign="affiliate"
                data-source="MAGICDB"
                data-medium="MAGICDB"
                src="https://content.tcg20life.com/tcgafa.js" async>
            </script>
        </div>
            @yield('content')
        </main>
    </div>
    <footer class="footer">
      <div class="container">
        <span class="text-muted">Magic the Gathering, Magic the Gathering Arena and related logos are registered trademarks, trademarks and/or copyright of Wizards of the Coast, 
        Inc, a subsidiary of Hasbro, Inc. All rights reserved. All art is property of their respective artists and/or Wizards of the Coast Inc. 
        This website is not produced, endorsed, supported, or affiliated with Wizards of the Coast Inc. Articles and comments are user-submitted and do not represent 
        official endorsements of this site.</span>        
        <p class="text-center"><span class="text-muted">© 2018-2019 MagicDB.us All Rights Reserved.</span></p>
      </div>
    </footer>
</body>

</html>
