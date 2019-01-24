<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="MTG Magic the Gathering Deck Builder. Build and share your MTG Standard Decks." />
        <link rel="icon" href="https://magicdb.us/images/onlycards.ico">
        <title>MTG: Deck Builder</title>
        <!-- Google AdSense -->
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({
                google_ad_client: "ca-pub-3996377264240146",
                enable_page_level_ads: true
            });
            </script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                        <a href="{{ url('/deck') }}">My Decks</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif
            
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 homepage-blog-img">
                    <!-- Here will be a random blog entry with img source -->
                        <img class="img-fluid" src="/images/{{$post->image_url}}" /> 
                        <div class="homepage-title m-b-md">
                            magicdb.us
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="homepage-links links">
                        <a class="hp-link" href="/card/">Build a Deck</a>
                        <a class="hp-link" href="/deck/dotw">Deck of The Week</a>
                        <a class="hp-link" href="/blog/all">Posts</a>
                        <a class="hp-link" href="/health">Life Counter</a>
                        <a class="hp-link" href="/contact/">Contact Us</a>                   
                    </div>
                </div>
            </div>
        </div>
    
    </body>
    </html>
