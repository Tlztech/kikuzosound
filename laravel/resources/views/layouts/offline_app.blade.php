
<!DOCTYPE html>
<html lang="ja" class="notranslate" translate="no">

<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="On the kikuzosound.com (auscultation portal site), many sounds such as heart sounds and lung sounds are released. If you listen with an appropriate device (Kikuzo-speaker with your own stethoscope), you can hear a realistic auscultation sound that is almost the same as the actual case." />
    <meta property="og:description" content="On the kikuzosound.com (auscultation portal site), many sounds such as heart sounds and lung sounds are released. If you listen with an appropriate device (Kikuzo-speaker with your own stethoscope), you can hear a realistic auscultation sound that is almost the same as the actual case." />
    <meta name="google" content="notranslate" />
    <title>
        kikuzosound.com | @yield('title')   
    </title>

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/common.js?v=').$version}}"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <meta name="google-site-verification" content="DmS5heKbLF8_t3npR-41a3c8lSzIVpC0XljRq56q8tQ" />

    <link rel="stylesheet" href="{{asset('css/common.css?v=').$version}}">
    <link rel="stylesheet" href="{{asset('css/style.css?v=').$version}}">
    <link rel="stylesheet" href="{{asset('css/style2.css?v=').$version}}">
    <link rel="stylesheet" href="{{asset('css/respons.css?v=').$version}}">
    <link rel="stylesheet" href="{{asset('css/bodymap.css?v=').$version}}">
    <!-- bxSlider Javascript file -->
    <script src="{{asset('js/bxslider/jquery.bxslider.js')}}"></script>
    <!-- bxSlider CSS file -->
    <link href="{{asset('js/bxslider/jquery.bxslider.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
    <!-- thema s -->
    <link rel="stylesheet" href="{{asset('css/jquery-ui.structure.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-ui.theme.min.css')}}">
    <!-- Aus Fullscreen Css -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/full-screen-helper.css')}}">
    <!-- thema e -->
    <!-- Jquery Range Slider -->
    <link rel="stylesheet" href="{{asset('css/jquery-ui-slider-pips.css')}}">
    <!-- Aus Fullscreen JS -->
    <script type="text/javascript" src="{{asset('js/full-screen-helper.min.js')}}"></script>
    <script src="{{asset('js/jquery-ui.js')}}"></script>
    <script src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>
    <script src="{{asset('js/jquery-ui-slider-pips.js')}}"></script>

    <!-- bodymapJS/CSS -->
	<!-- <script type="text/javascript" src="body-js/lib/howler.core.js"></script> -->
    <script type="text/javascript" src="{{asset('body-js/data.js?v=').$version}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('body-js/config.js?v=').$version}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('body-js/common.js?v=').$version}}" charset="UTF-8"></script>
    <script type="text/javascript" src="{{asset('body-js/points_config.js?v=').$version}}" charset="UTF-8"></script>
    <script src="{{asset('body-contents/heart/scripts/contents_config.js?v=').$version}}"></script>
    <script src="{{asset('body-js/apps.js?v=').$version}}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('body-style/style.css?v=').$version}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('body-style/bodymap.css?v=').$version}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('body-style/style_responsive.css?v=').$version}}"/>
    <style>
        body{
            background:unset !important;
        }

        .bodyFrame{
            width:100%;
            height:450px;
        }
        #data-offline{
            height: 100vh;
            text-align: center;
        }
        .aus-body_img.offline{
            height:auto!important;    
        }
        .container {
            margin-top: 120px;
        }
        .bodymap-frame {
            margin: 0 auto;
        }

    </style>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
</body>

</html>
 