<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'P2Coin') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app_ext.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('./css/cryptocoins.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCGyEhcN3EHQtgq5aewE_elp6mQyrbuWyA&amp;language=en"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
     <!-- Scripts -->
 <script> 
    var rootURL = '<?php echo $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']; ?>';
    
</script>
    <style>
        @font-face {
            font-family: Roboto Regular;
            src: url({{ asset('fonts/roboto/Roboto-Regular.ttf') }});
        }
        @font-face {
            font-family: Roboto Boldd;
            src: url({{ asset('fonts/roboto/Roboto-Bold.ttf') }});
        }
        @font-face {
            font-family: Roboto Regular;
            src: url({{ asset('fonts/roboto/Roboto-Regular.ttf') }});
        }
        @font-face {
            font-family: Roboto Med;
            src: url({{ asset('fonts/roboto/Roboto-Medium.ttf') }});
        }
        @font-face {
            font-family: Roboto Light;
            src: url({{ asset('fonts/roboto/Roboto-Light.ttf') }});
        }
        @font-face {
            font-family: Roboto bla;
            src: url({{ asset('fonts/roboto/Roboto-Black.ttf') }});
        }
        body{
            font-family: Roboto Regular;
            padding-top:70px; background-color:white;
            background-image:url('./public/assets/images/bk-img.jpg');
            background:rgba(255,255,255,0.4);
        }
        .form-control {
            border: 1px solid #42a212;
            border-radius:0px!important;
        }
        .form-control:focus {
            border-color: #427212;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(152,203,232,.6);
        }

        label, h1,h2,h3,h4,h5,h6, .menu-caption {
            color: #028840!important;
            background-color: transparent;
        }
        .btn-green{
                color: #028840;
                font-weight: bold;
                background-color: rgba(37, 157, 109, 0.01);
                border: 2px solid #028840;
        }
        .btn-white{
                color: #fff;
                font-weight: bold;
                background-color: rgba(37, 157, 109, 0.01);
                border: 2px solid #fff;
        }

        .menu-a {
            color: #777777 !important;
            /*font-weight: bold;*/
            font-size: 15px;
        }
        .h-title{
            font-weight:bold;
        }
        .security-level-strong{
            color: #02a240!important;
        }
        .security-level-weak{
            color:red!important;
        }
        .navbar-fixed-bottom .navbar-collapse, .navbar-fixed-top .navbar-collapse {
            max-height: 640px;
        }
        .nav-span-rate{
            padding-top: 15px;
            padding-bottom: 15px;
            display: block;
            cursor: pointer;
        }
        .menu-currency{
            cursor:pointer;
            background-color:white;
            width:100%;
        }
        .menu-currency:hover{
            background-color:#f5f5f5;
        }
        .label-caption-title{
            text-align: left;
            font-style: italic;
            color: grey!important;
            font-weight: 400;
            float:left;
        }
        .currrency-green{
            border: 1px solid #02a240!important;
            border-radius:0px;
        }
        .title-border-bottom {
            border-bottom: lightgreen 1px solid;
        }
        .title-content-body {
            min-height: 240px;
        }

        .text-white { color: #e1ffff !important; }

        .stick { display: none !important; }

        .gradient-btn {
            background-color: #f4a90c !important;
            background: #f4a90c; /* For browsers that do not support gradients */
            background: -webkit-linear-gradient(#f4a90c, #f4870c); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(#f4a90c, #f4870c); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(#f4a90c, #f4870c); /* For Firefox 3.6 to 15 */
            background: linear-gradient(#f4a90c, #f4870c); /* Standard syntax (must be last) */
        }

        @media (min-width: 768px){
            .own-nav .dropdown-menu { right: 12px; left: auto; background-color: #2e353d; padding: 0; margin-top: 15px !important; width: 200px; }
            .dropdown-menu .divider {
                height: 1px;
                margin: 0;
                overflow: hidden;
                background-color: #23282e;
            }
            .menu-a-sel {
                color: #fff !important;

                background: #f4a90c; /* For browsers that do not support gradients */
                background: -webkit-linear-gradient(#f4a90c, #f4870c); /* For Safari 5.1 to 6.0 */
                background: -o-linear-gradient(#f4a90c, #f4870c); /* For Opera 11.1 to 12.0 */
                background: -moz-linear-gradient(#f4a90c, #f4870c); /* For Firefox 3.6 to 15 */
                background: linear-gradient(#f4a90c, #f4870c); /* Standard syntax (must be last) */
                border-radius: 5px;
            }
            .menu-a:hover {
                color: #fff !important; 

                background: #f4a90c!important; 
                border-radius: 5px;
            }
            .left-menu { margin-left: 50px; margin-top: 12px; }
            .navbar-nav>li>a {
                padding-top: 0px;
                padding-bottom: 0px;
                line-height: 30px;
            }
            .menu-border:hover { border-left: 2px solid #f4890c !important; color: #5a5a5a !important; }
            .menu-border { border-left: 2px solid #2e353d !important; }
            .menu-border a { color: #fff !important; line-height: 30px !important; }
            .menu-border a:hover { background: #4f5b69 !important; }
            .stick { display: block !important; }
            
            .up-arrow:before {
                content:"\A";
                border-bottom: 7px solid #2e353d;
                border-left: 7px solid transparent;
                border-right: 7px solid transparent;
                width: 0;
                height: 0;
                position: absolute;
                left: 175px;
                top: -7px;
            }
            .msg-content {
                word-wrap: break-word;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                padding-bottom: 3px;
            }
        }

    </style>
     <script src="{{ asset('js/app.js') }}"></script> 
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-fixed-top navbar-default">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand a-logo-brand" href="{{ url('/') }}">
                    <img src="{{ asset('./assets/images/logo.png') }}" style="width: auto; height: 100%;">
{{--                        {{ config('app.name', 'P2Coin') }}--}}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav left-menu">
                        <?php $tradeCls = ""; ?>
                        <?php ( (strpos(URL::current(), 'trades') === false) ) ? $tradeCls = "" : $tradeCls = "menu-a-sel"; ?>
                        <li><a class="menu-a {{ $tradeCls }}" href="{{ route('trades') }}" style="margin-right: 10px;">TRADE</a></li>
                        @if (!Auth::guest())
                        <?php ( strpos(URL::current(), 'managelistings') === false ) ? $tradeCls = "" : $tradeCls = "menu-a-sel"; ?>
                            <li><a class="menu-a {{ $tradeCls }}" href="{{ route('managelistings') }}">MANAGE LISTINGS</a></li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right right-menu" style="margin-top: 12px;">
                        <!-- Authentication Links -->
                        @if (!Auth::guest())
                        <li>
                            <li class="dropdown user-panel-dd">
                                <a class="dropdown-toggle menu-a" data-toggle="dropdown" href="#user_dropdown" aria-expanded="false">
                                    CURRENCY<b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-panel-dd"><div class="menu-currency" id="btc"><i class="cc BTC"></i>&nbsp;BTC</div></li>
                                    <li class="user-panel-dd"><div class="menu-currency" id="eth"><i class="cc ETH"></i>&nbsp;ETH</div></li>
                                </ul>
                            </li> 
                        </li>
                        <li style="margin-right: 20px;">
                            <div id="div_btc" style="padding-top:5px;">
                                <i class="cc BTC" style="color: #717975; margin-top:7px;"></i>&nbsp;
                                <!-- <label id="label_btc_amount" >{{ session()->get('btc_amount') }}</label> -->
                                <label id="label_btc_amount" >BTC</label>
                            </div>
                            <div id="div_eth" style="display:none;padding-top:5px;">
                                <i class="cc ETH" style="color: #717975; margin-top:7px;"></i>&nbsp;
                                <!-- <label id="label_eth_amount" >{{ session()->get('eth_amount') }}</label> -->
                                <label id="label_eth_amount" >ETH</label>
                            </div>
                        </li>
                        @endif
                        @if (Auth::guest())
                            <li><a class="menu-a" href="{{ route('login') }}">Log In</a></li>
                            <li><a class="menu-a" href="{{ route('register') }}">SignUp</a></li>
                        @else
                            <li class="dropdown user-panel-dd stick">
                                <a href="#">|</a>
                            </li>                    
                            <li class="s">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#user_dropdown">
                                    <i class="fa fa-comment" aria-hidden="true" style = "line-height: 30px;"></i>
                                </a>
                                <ul class="dropdown-menu" style="width: 400px; margin-top: 10px;" id="msg_list">
                                </ul>
                            </li>
                            <li class="dropdown user-panel-dd stick">
                                <a href="#">|</a>
                            </li>                    
                            <li class="dropdown user-panel-dd own-nav">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="" aria-expanded="false">
                                    <i class="fa fa-navicon fa-fw" style = "margin-top: 8px;"></i>
                                </a>

                                <ul class="dropdown-menu up-arrow">
                                    <!-- <li class="user-panel-dd menu-border"><a href="{{ route('wallet') }}"><i class="fa fa-btc fa-fw"></i>&nbsp;Wallet</a></li>
                                    <li class="divider"></li>   -->
                                    <li class="menu-border"><a href="{{ route('profile') }}"><i class="fa fa-edit fa-fw"></i>&nbsp;Profile</a></li>
                                    <li class="divider"></li>  
                                    <li class="user-panel-dd menu-border"><a href=" {{ route("opentrade") }}"><i class="fa fa-truck fa-fw"></i>&nbsp;Open Trades</a></li>
                                    <li class="divider"></li>
                                    <li class="user-panel-dd menu-border"><a href="{{ route('chart') }}"><i class="fa fa-bar-chart fa-fw"></i>&nbsp;Charts</a></li>
                                    <li class="divider"></li>
                                    <li class="user-panel-dd menu-border"><a href="/settings"><i class="fa fa-sliders fa-fw"></i>&nbsp;Settings</a></li>                                  
                                    <li class="divider"></li>
                                    <li class="menu-border">
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out fa-fw"></i>&nbsp;Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li> 
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
       
        @yield('content')
        <div style="height: 50px;"></div>
    </div>

    <script src="{{ asset('js/global.js') }}"></script>
</body>
</html>

<!-- <script src="{{ asset('js/home/index.js') }}"></script>  -->
      
