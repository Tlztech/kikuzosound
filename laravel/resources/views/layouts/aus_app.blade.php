<?php
  use App\OnetimeKey;

  $strDebug = '';
  if (env('APP_DEBUG')) {
      //if (App::environment('local')) {
      //if (App::environment('local', 'staging')) {
      $Debug = true;
  } else {
      $Debug = false;
  }

  //define('USER_DS_SP', 'daiichisankyo.co.jp-1001');     //自動ログイン用の第一三共特別アカウント
  $login_user = Session::get('MEMBER_3SP_USER');
  $login_at = Session::get('MEMBER_3SP_LOGIN_AT');
  $user_id=Session::get('MEMBER_3SP_ACCOUNT_ID'); //user id
  //$route_name = 'login';
  $route_name = \Request::route()->getName();
  if ($route_name === 'member_login') {
      $strBtnLogin = '';   //何も表示しない
  } else {
      $display_none = '';
      // telemedica 20170901
      //    if ($login_user == '') {
      if ($login_status == 0) {
          $str_label = trans('app.login');
          $str_href = "'member_login'";
      } else {
          $str_label = trans('app.logout');
          //if ($login_user === 'daiichisankyo.co.jp-1001') {       //★この値は.envへ
          if ($login_user === env('MEMBER_3SP_USER_DS_SP')) {       //DS特別アカウントなら
              if ($Debug) {       //★デバッグ中はログアウトボタンを表示
                  $str_label .= '★';   //★テスト用ログアウト
              } else {        //★デバッグ中でなければボタンは表示しない
                  $display_none = ' style="display:none;"';
              }
          }
          $str_href = "'member_logout'";
      }
      //ボタン
      $strBtnLogin = '<button' . $display_none . ' type = "button" name = "btn_login" value = "btn_login" class = "button_head" onclick = "location.href=' . $str_href . '">' . $str_label . '</button>';
  }
  if ($Debug) {
      $session_lifetime = Config::get('session.lifetime');       //★テスト有効時間を取得
      logger($strDebug);
  }
?>
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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>
        kikuzosound.com | @yield('title')   
    </title>

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/common_test.js?v=1.2.0.20190125')}}"></script>
    {{-- icons --}}
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <meta name="google-site-verification" content="DmS5heKbLF8_t3npR-41a3c8lSzIVpC0XljRq56q8tQ" />

    <link rel="stylesheet" href="{{asset('css/common.css?v=1.2.0.20180301')}}">
    <link rel="stylesheet" href="{{asset('css/style.css?v=1.2.2.20190523')}}">
    <link rel="stylesheet" href="{{asset('css/style2.css?v=1.2.0.20190610')}}">
    <link rel="stylesheet" href="{{asset('css/quiz.css?v=1.2.0.20170710')}}">
    <link rel="stylesheet" href="{{asset('js/audiojs/audio.css')}}">
    <link rel="stylesheet" href="{{asset('css/respons.css?v=1.2.0.20190125')}}">
    <link rel="stylesheet" href="{{asset('css/bodymap.css')}}">
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
    <script src="{{asset('js/audiojs/audio.js?v=1.1.7.20190125')}}"></script>
    <script type="text/javascript" src="{{asset('/js/jquery.logger.js?v=1.1.7.20190125')}}"></script>
    <script src="{{asset('/js/magnific-popup/jquery.magnific-popup.js')}}"></script>
    <link href="{{asset('/js/magnific-popup/magnific-popup.css')}}" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/add.css?v=1.2.3.20190610')}}"><!-- 20170531 hyono@cori.com 11 ログイン画面用に追加 -->
    <script src="{{asset('js/cookie/jquery.cookie.js')}}"></script>
    <script src="{{asset('js/sweetalert.min.js')}}"></script>

    <script src="{{asset('js/jquery-ui-slider-pips.js')}}"></script>

    @if(env('APP_ENV') === 'production')

        <!--Google Tag Manager-->
        <script> (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '& l =' + l : '';
        j.async = true;
        j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-TSP2X67'); </script>
    <!--End Google Tag Manager-->

    @elseif(env('APP_ENV') === 'development' )
        <!-- Google Tag Manager -->
        @if($user_id==null)
        <!-- <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TP4SZQT');
            ga('create', 'UA-187118647-1', 'auto'); 
            ga('set', 'dimension1', 'Guest'); 
            ga('send', 'pageview');
        </script> -->
        <script> 
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), 
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) 
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga'); 
            ga('create', 'UA-187118647-1', 'auto'); 
            ga('set', 'dimension1', 'Guest'); 
            ga('send', 'pageview'); 
        </script> 
        @else
        <!-- <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TP4SZQT');
            ga('create', 'UA-187118647-1', 'auto'); 
            ga('set', 'userId', '{{$user_id}}'); 
            ga('set', 'dimension1', 'Member'); 
            ga('send', 'pageview');
        </script> -->
        <script> 
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), 
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) 
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga'); 
            ga('create', 'UA-187118647-1', 'auto'); 
            ga('set', 'userId', '{{$user_id}}'); 
            ga('set', 'dimension1', 'Member'); 
            ga('send', 'pageview'); 
        </script> 
        @endif
        <!-- End Google Tag Manager -->
    @endif

    @if(env('APP_ENV') === 'production')
        <!--Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172122605-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-172122605-1');
        </script>
    @elseif(env('APP_ENV') === 'development' )
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-187118647-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-187118647-1');
        </script>
    @endif
    
    @if (env('APP_ENV') === 'production')
        <script>
            gtag('set', {'user_id': 'USER_ID'});
        </script>
    @endif
    <script >

  /* 試聴音登録制に変更の為、home,contentsと重複していたので削除 */
  /*
  audiojs.events.ready(function () {
      var audios = document.getElementsByTagName('audio');
      if (audios.length > 0) {
          var a1 = audiojs.create(audios, {
              css: false,
              createPlayer: {
                  markup: false,
                  playPauseClass: 'play-pauseZ',
                  scrubberClass: 'scrubberZ',
                  progressClass: 'progressZ',
                  loaderClass: 'loadedZ',
                  timeClass: 'timeZ',
                  durationClass: 'durationZ',
                  playedClass: 'playedZ',
                  errorMessageClass: 'error-messageZ',
                  playingClass: 'playingZ',
                  loadingClass: 'loadingZ',
                  errorClass: 'errorZ'
              }
          });
      }
  });
  */

  //$(document).ready(function () {
  $(window).on('load', function() {
    $('.bxslider').bxSlider({
        onSliderLoad: function(currentIndex) {
            if (this.getSlideCount() == 0) {
                $(this).closest(".img_slide").find($(".bx-prev")).hide();
                $(this).closest(".img_slide").find($(".bx-next")).hide();
            
            }
        }
    });
  });
  </script>
</head>

<body>
@if(env('APP_ENV') === 'production')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TSP2X67"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@elseif(env('APP_ENV') === 'development' )
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TP4SZQT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif

    {!! $strDebug !!}

    @include('_partials.ga')
    <!--*********************************** #header ***********************************-->
    <div id="header">
        <!-- .header_inner -->
        <div class="header_inner">
            <!-- .title_h1 -->
            <h1 class="header_explanation sp_none"><img src="img/icon.png" alt="">@lang('app.header_explanation')</h1>
            <!-- /.title_h1 -->
            <!-- .headlogo -->
            <div class="headlogo">
                <a href="{{ route('home')      }}"><img src="img/stet_img.png" style="height: 40px;width: auto;" alt="" class="logo"></a>
                <?php if ($login_status == 0 && $route_name !== 'member_login') { ?>
                <!-- <a href="{{ route('contact_form') }}">
                        <div class="estimate">@lang('app.quote_request')</div>
                    </a>
                    <a href="{{ route('appli_form') }}">
                        <div class="applitop">@lang('app.purchase')</div>
                    </a> -->
                <?php } ?>
                <div class="lang_switcher">
                    <img src="img/globe.png" alt="" height="18px" class="globe">
                    <select onchange="location = this.value;" >
                        @if (env('lang_en'))
                            <option value="{{ route('en') }}" <?php if (config('app.locale')=='en' || !config('app.locale')) {
    echo "selected";
} ?>><a>‎‎‏‏‎  ‎EN</a></option>
                        @endif
                        @if (env('lang_ja'))
                            <option value="{{ route('ja') }}" <?php if (config('app.locale')=='ja') {
    echo "selected";
} ?>><a>日本語</a></option>
                        @endif
                    </select>
                    <img class="down_arrow_png chrome" src="../../img/down_arrow.png" alt="" height="6px">
                </div>
                <!-- .btn_login -->
                @if($user_id!=null)
                <button type="button" name="btn_mypage" value="btn_login" class="button_head btn_mypage" onclick="location.href = 'mypage'">@lang('app.mypage')</button>
                @endif
                <?php echo $strBtnLogin ?>


                <!--<button type="button" name="btn_login" value="btn_login" class="button_head" onclick="location.href = 'login'">-->
                <!-- /.btn_login -->
            </div>
            <!-- /.headlogo -->
            <!-- .gnavi_pc -->
            <div class="gnavi_pc">
                <!-- .menu -->
                <ul class="menu 405">
                    <?php $route_name = \Request::route()->getName() ?>
                    <li class="@if( in_array($route_name ,['home','']) ) active @endif"><a
                            href="{{ route('home')      }}">@lang('app.home')</a></li>
                    <li class="@if( $route_name=='contents'            ) active @endif"><a
                            href="{{ route('contents')  }}" >@lang('app.contents')</a></li>
                    <li class="@if( $route_name=='aus'           ) active @endif"><a
                        href="{{ route('aus') }}">@lang('app.aus')</a></li>
                    <li class="@if( $route_name=='quizpacks'           ) active @endif"><a
                            href="{{ route('quizpacks') }}">@lang('app.quizpacks')</a></li>
                    <?php
                        $loggedUserId = Session::get('MEMBER_3SP_ACCOUNT_ID');

                        if ($loggedUserId) {
                            $hasExamAccess = DB::table("onetime_keys")->where("customer_id", $loggedUserId)->first()->is_exam_group;
                            if ($hasExamAccess == 1) {
                                ?>
                                    <li class="@if( $route_name=='exams'           ) active @endif"><a
                                        href="{{ route('exams') }}" >@lang('app.exam')</a></li>
                                <?php
                            }
                        }
                    ?>
                </ul>
                <!-- /.menu -->
            </div>
            <!-- /.gnavi_pc -->

            <!-- .gnavi_sp -->
            <div id="gnavi_sp">
                <p id="gnavi_sp_btn"><img src="img/sp_ginavi_btn.png" height="50" width="50" alt="MENU"></p>
                <div class="menu_box">
                    <!-- .menu_ -->
                    <ul class="menu">
                        <li class="@if( in_array($route_name ,['home','']) ) active @endif"><a
                                href="{{ route('home')      }}">@lang('app.home')</a></li>
                        <li class="@if( $route_name=='contents'            ) active @endif"><a
                                href="{{ route('contents')  }}">@lang('app.contents')</a></li>
                        <li class="@if($route_name=='aus') active @endif"><a
                                href="{{ route('aus') }}">@lang('app.aus')</a></li>
                        <li class="@if( $route_name=='quizpacks'           ) active @endif"><a
                                href="{{ route('quizpacks') }}">@lang('app.quizpacks')</a></li>
                                <?php
                        $loggedUserId = Session::get('MEMBER_3SP_ACCOUNT_ID');

                        if ($loggedUserId) {
                            $hasExamAccess = DB::table("onetime_keys")->where("customer_id", $loggedUserId)->first()->is_exam_group;
                            if ($hasExamAccess == 1) {
                                ?>
                                    <li class="@if( $route_name=='exams'           ) active @endif"><a
                                        href="{{ route('exams') }}">@lang('app.exam')</a></li>
                                <?php
                            }
                        }
                        
                    ?>   
                    </ul>
                    <!-- /.menu_ -->
                    <!-- .submenu -->
                    <ul class="submenu 22">
                        <li class="@if( $route_name=='about'   ) active @endif"><a
                                href="{{route('about')}}">@lang('app.about')</a></li>
                        <li class="@if( $route_name=='terms'   ) active @endif"><a
                                href="{{route('terms')}}">@lang('app.terms')</a></li>
                        <li class="@if( $route_name=='privacy' ) active @endif"><a
                                href="{{route('privacy')}}">@lang('app.privacy')</a></li>
                        <li class="@if( $route_name=='business_partner' ) active @endif"><a
                                href="{{ route('business_partner') }}">@lang('app.business_partner')</a></li>
                        <li class="@if( $route_name=='faq'     ) active @endif"><a
                                href="{{route('faq')}}">@lang('app.faq')</a></li>
                        <li class="@if( $route_name=='news'    ) active @endif"><a
                                href="{{route('news')}}">@lang('app.news')</a></li>
                        <li class="@if( $route_name=='contact' ) active @endif"><a
                                href="{{route('contact')}}">@lang('app.contact')</a></li>
                    </ul>
                    <!-- /.submenu -->
                </div>
                <!-- /.menu_box -->
            </div>
            <!-- /.gnavi_sp -->

        </div>
        <!-- /.header_inner -->
    </div>

    <!-- パンくずリスト -->
    <div>
        @yield('breadcrumb')
    </div>

    <div class="container page-wrapper">
        @yield('content')
    </div>

    <!--*********************************** #footer ***********************************-->
    <div id="footer">
        <!-- .footer_inner -->
        <div class="footer_inner">
            <!-- .footer_navi -->
            <div class="footer_navi clearfix">
                <ul class="footer_link">
                    <li><a href="{{route('about')}}">@lang('app.about')</a></li>

                </ul>
                <ul class="footer_link">
                    <li><a href="{{route('terms')}}">@lang('app.terms')</a></li>
                </ul>
                <ul class="footer_link">
                    <li><a href="{{route('privacy')}}">@lang('app.privacy')</a></li>
                </ul>
                <!-- <ul class="footer_link">
                        <li><a href="{{route('news')}}">@lang('app.info')</a></li>]
                    </ul>    -->
                <ul class="footer_link">
                    <li><a href="{{route('business_partner')}}">@lang('app.business_partner')</a></li>
                </ul>
                <ul class="footer_link">
                    <li><a href="{{route('faq')}}">@lang('app.faq')</a></li>
                </ul>
                <ul class="footer_link">
                    <li><a href="{{route('contact')}}">@lang('app.contact')</a></li>
                </ul>
            </div>
            <!-- /.footer_navi -->
            <!-- .sns_link -->
            <div class="sns_link">
                {{-- Twitter --}}
                {{-- Twitterのweb page 表示 --}}
                <a href="https://twitter.com/share?url=https%3A%2F%2Fkikuzosound.com&hashtags=Auscultation%2CAuscultation sounds%2CHeart sounds%2CLung sounds%2CIntestinal sounds%2CPhysical assessment」&text=「You can learn auscultation sounds! Portal site"
                    class="twitter-share-button" target="_blank"><img src="img/snsicon_t@2.png" alt="twitter"
                        width="43"></a>
                {{-- Twitterのpopup 表示 --}}
                {{--
            <a href="https://twitter.com/share?url=https%3A%2F%2F3sportal.telemedica.co.jp&hashtags=聴診%2C聴診音%2C心音%2C肺音%2C腸音%2Cフィジカルアセスメント」&text=「聴診音が学べる！ポータルサイト" class="twitter-share-button" target="_blank" onclick="window.open(this.href, 'tweetwindow', 'width = 550, height = 450, personalbar = 0, toolbar = 0, scrollbars = 1, resizable = 1'); return false;"><img src="img/snsicon_t@2.png" alt="twitter" width="43"></a>
             --}}

                {{-- Facebook --}}
                {{-- Facebookのweb page 表示 --}}
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://kikuzosound.com&s=「聴診音が学べる！ポータルサイト」"
                    target="_blank"><img src="img/snsicon_f@2.png" alt="facebook" width="43"></a>
                {{-- Facebookのpopup 表示 --}}
                {{--
            <a href="http://www.facebook.com/share.php?u=https://3sportal.telemedica.co.jp" onclick="window.open(this.href, 'FBwindow', 'width = 650, height = 450, menubar = no, toolbar = no, scrollbars = yes'); return false;"><img src="img/snsicon_f@2.png" alt="facebook" width="43"></a>
             --}}

                {{-- Line --}}
                <!-- 「聴診音が学べる！ポータルサイト https://3sportal.telemedica.co.jp」 -->
                <a href="https://timeline.line.me/social-plugin/share?url=https%3A%2F%2Fkikuzosound.com"
                    target="_blank"><img src="img/snsicon_l@2.png" width="43" height="43" /></a>

                {{-- Line dynamic取得今のurl( popup表示 ) --}}
                {{--
            <div class="line-it-button" style="display: none;" data-type="share-a" data-lang="ja"></div>
            <script src="//scdn.line-apps.com/n/line_it/thirdparty/loader.min.js" async="async" defer="defer"></script>
              --}}
            </div>
            <!-- /.sns_link -->
            <!-- .copyright -->
            <p class="copyright">Copyright ©2020 TELEMEDICA Inc.. All Rights Reserved.</p>
            <!-- /.copyright -->
        </div>
        <!-- /.footer_inner -->
    </div>
    <!--*********************************** /#footer ***********************************-->

    <div id="overlay"></div>
    <!-- /#overlay -->

    <?php

            //set browser
            $browser = '';
            if(empty($_SERVER['HTTP_USER_AGENT'])){
                $browser = 'other';
            }else{
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
                    $browser = 'ie';
                } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
                    $browser = 'ie';
                } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) {
                    $browser = 'firefox';
                } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
                    $browser = 'chrome';
                } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false) {
                    $browser = "opera";
                } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false) {
                    $browser = "opera";
                } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false) {
                    $browser = "safari";
                } else {
                    $browser = 'other';
                }
            }
            

            // set session for the browser
            session(['browser' => $browser]);



            //check device for the opened browser
            $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);

            //set device for the opened browser
            $device = $isMobile == 0 ? 'pc' : 'mobile-phone';

            // set session for the device
            session(['device' => $device]);

            //set the user agent of the user
            $terminal = $_SERVER['HTTP_USER_AGENT'];

            // set session for the terminal
            session(['terminal' => $terminal]);






            //check if the bwtk exists in db
            //if not then logout

            //check if there is a cookie stored in local
            $local_bwtk = isset($_COOKIE["bwtk"]) ? $_COOKIE["bwtk"] : "";

            //check if there is current logged in account id
            if (Session::has("MEMBER_3SP_ACCOUNT_ID")) {
                ?>
                    <script>
                        $("button[name='btn_login']").click(function(){
                            sessionStorage.removeItem('access_log');
                        });
                        if(!sessionStorage.getItem('access_log')) {
                            sessionStorage.setItem('access_log', true);
                            $.ajax({
                                url : 'access_log',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data : null,
                                type : 'POST',
                                contentType : 'application/json',
                                success: function(res){
                                    //if(res == 1) console.log("logged success");
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                // alert( "error : " + XMLHttpRequest );
                                },
                            });
                        }
                    </script>
                <?php
                //get current account id
                $current_account_id = Session::has("MEMBER_3SP_ACCOUNT_ID")? session("MEMBER_3SP_ACCOUNT_ID") : "";

                if (Session::get('MEMBER_3SP_USER') != 'test_gs_002') { // exempt test account
                    //check if the cookie stored exists in db
                    $isExistsBwtk = OnetimeKey::select("bwtk", "status")
                        ->where("customer_id", $current_account_id)
                        ->where("bwtk", $local_bwtk)
                        ->get();

                    $logout_route = env('APP_URL')."member_logout";

                    //if the local bwtk is not equal to the db bwtk
                    if (count($isExistsBwtk) <= 0 || $isExistsBwtk[0]->status == 3) {
                        echo "<script>window.location='$logout_route'</script>";
                    }
                }
            }

        ?>
        <!-- disable right click in images -->
        <script>
            $(document).ready(function() {

                disableImageSaving();
                disableVideoSaving();

                function disableImageSaving() {
                    $('img').bind('contextmenu', function(e){
                        return false;
                    });
                }
                function disableVideoSaving() {
                    $('video').bind('contextmenu', function(e){
                        return false;
                    });
                }

                $('img').click(function(){
                    disableImageSaving();
                });

            });
        </script>
</body>

</html>
 