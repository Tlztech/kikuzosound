<?php
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
          $str_href = "'../member_login'";
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
      $strBtnLogin = '<button' . $display_none . ' type = "button" name = "btn_login" value = "btn_login" class = "button_head" onclick = "location.href=' . $str_href . '"><span>' . $str_label . '</span></button>';
  }
  if ($Debug) {
      $session_lifetime = Config::get('session.lifetime');       //★テスト有効時間を取得
      logger($strDebug);
  }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="format-detection" content="telephone=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="On the kikuzosound.com (auscultation portal site), many sounds such as heart sounds and lung sounds are released. If you listen with an appropriate device (Kikuzo-speaker with your own stethoscope), you can hear a realistic auscultation sound that is almost the same as the actual case." />
        <meta property="og:description" content="On the kikuzosound.com (auscultation portal site), many sounds such as heart sounds and lung sounds are released. If you listen with an appropriate device (Kikuzo-speaker with your own stethoscope), you can hear a realistic auscultation sound that is almost the same as the actual case." />
        <title>@lang('app.title') 3S TELEMEDICA | @yield('title')</title>
        <meta name="google" content="notranslate" />
        <meta name="google-site-verification" content="DmS5heKbLF8_t3npR-41a3c8lSzIVpC0XljRq56q8tQ" />

        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/common.js?v=1.2.0.20190125')}}"></script>
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="stylesheet" href="{{asset('css/common.css?v=1.2.0.20180301')}}">
        <link rel="stylesheet" href="{{asset('css/style.css?v=1.2.2.20190523')}}">
        <link rel="stylesheet" href="{{asset('css/style2.css?v=1.2.0.20190610')}}">
        <link rel="stylesheet" href="{{asset('css/quiz.css?v=1.2.0.20170710')}}">
        <link rel="stylesheet" href="{{asset('js/audiojs/audio.css')}}">
        <link rel="stylesheet" href="{{asset('css/respons.css?v=1.2.0.20190125')}}">
        <!-- bxSlider Javascript file -->
        <script src="{{asset('js/bxslider/jquery.bxslider.js')}}"></script>
        <!-- bxSlider CSS file -->
        <link href="{{asset('js/bxslider/jquery.bxslider.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
        <!-- thema s -->
        <link rel="stylesheet" href="{{asset('css/jquery-ui.structure.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/jquery-ui.theme.min.css')}}">
        <!-- thema e -->
        <script src="{{asset('js/jquery-ui.js')}}"></script>
        <script src="{{asset('js/audiojs/audio.min.js?v=1.1.7.20190125')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.logger.js?v=1.1.7.20190125')}}"></script>
        <script src="{{asset('/js/magnific-popup/jquery.magnific-popup.js')}}"></script>
        <link href="{{asset('/js/magnific-popup/magnific-popup.css')}}" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/add.css?v=1.2.3.20190610')}}"><!-- 20170531 hyono@cori.com ログイン画面用に追加 -->
        <script src="{{asset('js/cookie/jquery.cookie.js')}}"></script>
        <script src="{{asset('js/sweetalert.min.js')}}"></script>
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
    @endif

    @if(env('APP_ENV') === 'development' || env('APP_ENV') === 'production')
        <!--Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-172122605-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-172122605-1');
        </script>
    @endif

    @if (env('APP_ENV') === 'production')
        <script>
            gtag('set', {'user_id': 'USER_ID'});
        </script>
    @endif
        <script>

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
$(window).on('load',function () {
    $('.bxslider').bxSlider({
        // 要素に対してbxsliderの準備が整うと呼ばれる
        onSliderLoad: function (currentIndex) {
            // 0件の場合スライド機能をOFFにする
            if (this.getSlideCount() == 1) {
                // jquery.bxslider.js によって生成された操作UI要素を削除する
                this.find('.bx-prev').remove();
                this.find('.bx-next').remove();
                this.closest('.bx-wrapper').find('.bx-pager').remove();
                // Bxsliderの下の余白を削減する
                this.closest('.bx-wrapper').css('margin-bottom', '30px');
            }
        },
        /**
         * ※引数$slideElement：スライドするjQuery要素
         * ※引数oldIndex：前のスライドの要素のインデックス（遷移前）
         * ※引数newIndex：先のスライドの要素インデックス（遷移後）
         */
        onSlideAfter: function ($slideElement, oldIndex, newIndex) {
            // 先のスライドのタイトルを取得する
            var image_title = $slideElement.find("#sl_image_title").text();
            var titleElement = $slideElement.closest(".img_slide").find("#image_title");
            titleElement.text(image_title);
            if (image_title) {
                titleElement.show();
                $("#image_title").text(image_title);
            } else {
                titleElement.hide();
            }
        }
    });
});
        </script>

<?php 
    use Jenssegers\Agent\Agent;
    $agent = new Agent();
    $isMobile = 0;
    if($agent->isMobile()) {
        ?>
        <style>
            @media only screen (max-width: 1280px) and (min-width: 400px) {
                html {
                    /* Rotate the content container */
                    transform: rotate(-90deg);
                    transform-origin: left top;
                    /* Set content width to viewport height */
                    width: 100vh;
                    /* Set content height to viewport width */
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    overflow-x: hidden;
                    position: absolute;
                    top: 100%;
                    left: 0;
                }
            }

            @viewport {  
                orientation: portrait;  
            }
        </style>
        <?php
    }
?>

    </head>
    <body>
      <!-- Google Tag Manager (noscript) -->
@if(env('APP_ENV') === 'production')
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TSP2X67"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
<!-- End Google Tag Manager (noscript) -->

{!! $strDebug !!}

        @include('_partials.ga')
        <!--*********************************** #header ***********************************-->
        <div id="header">
            <!-- .header_inner -->
            <div class="header_inner">
                <!-- .title_h1 -->
                <h1 class="header_explanation sp_none"><img src="../img/icon.png" alt="">@lang('app.header_explanation')</h1>
                <!-- /.title_h1 -->
                <!-- .headlogo -->
                <div class="headlogo">
                <a href="/"><img src="../img/stet_img.png" style="height: 40px;width: auto;" alt="" class="logo"></a>
<?php if ($login_status == 0 && $route_name !== 'member_login') { ?>
                    <!-- <a href="{{ route('contact_form') }}">
                        <div class="estimate">@lang('app.quote_request')</div>
                    </a>
                    <a href="{{ route('appli_form') }}">
                        <div class="applitop">@lang('app.purchase')</div>
                    </a> -->
<?php } ?>
                    <div class="lang_switcher">
                        <img src="../img/globe.png" alt="" height="18px">
                        <select onchange="location = this.value;" >
                            @if (env('lang_en'))
                                <option value="{{ route('en') }}" <?php if (config('app.locale')=='en' || !config('app.locale')) { echo "selected";} ?>><a>EN</a></option>
                            @endif
                            @if (env('lang_ja'))
                                <option value="{{ route('ja') }}" <?php if (config('app.locale')=='ja') { echo "selected";} ?>><a>日本語</a></option>
                            @endif
                        </select>
                        <img class="down_arrow_png chrome" src="../../img/down_arrow.png" alt="" height="6px">
                    </div>
                    <!-- .btn_login -->
                    <?php echo $strBtnLogin ?>


                    <!--<button type="button" name="btn_login" value="btn_login" class="button_head" onclick="location.href = 'login'">-->
                    <!-- /.btn_login -->
                </div>
                <!-- /.headlogo -->
                <!-- .gnavi_pc -->
                <div class="gnavi_pc">
                    <!-- .menu -->
                    <ul class="menu">
                        <?php $route_name = \Request::route()->getName() ?>
                        <li class="@if( in_array($route_name ,['home','']) ) active @endif"><a href="{{ route('home')      }}">@lang('app.home')</a></li>
                        <li class="@if( $route_name=='contents'            ) active @endif"><a href="{{ route('contents')  }}">@lang('app.contents')</a></li>
                        <li class="@if( $route_name=='quizpacks'           ) active @endif"><a href="{{ route('quizpacks') }}">@lang('app.quizpacks')</a></li>
                        <!-- <li class="@if( $route_name=='video'             ) active @endif"><a href="{{ route('video')   }}">@lang('app.video')</a></li> -->
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
                            <li class="@if( in_array($route_name ,['home','']) ) active @endif"><a href="{{ route('home')      }}">@lang('app.home')</a></li>
                            <li class="@if( $route_name=='contents'            ) active @endif"><a href="{{ route('contents')  }}">@lang('app.contents')</a></li>
                            <li class="@if( $route_name=='quizpacks'           ) active @endif"><a href="{{ route('quizpacks') }}">@lang('app.quizpacks')</a></li>
                            <li class="@if( $route_name=='video'             ) active @endif"><a href="{{ route('video')   }}">@lang('app.video')</a></li>
                        </ul>
                        <!-- /.menu_ -->
                        <!-- .submenu -->
                        <ul class="submenu">
                            <li class="@if( $route_name=='about'   ) active @endif"><a href="{{route('about')}}">@lang('app.about')</a></li>
                            <li class="@if( $route_name=='terms'   ) active @endif"><a href="{{route('terms')}}">@lang('app.terms')</a></li>
                            <li class="@if( $route_name=='privacy' ) active @endif"><a href="{{route('privacy')}}">@lang('app.privacy')</a></li>
                            <li class="@if( $route_name=='news'    ) active @endif"><a href="{{route('news')}}">@lang('app.news')</a></li>
                            <li class="@if( $route_name=='faq'     ) active @endif"><a href="{{route('faq')}}">@lang('app.faq')</a></li>
                            <li class="@if( $route_name=='contact' ) active @endif"><a href="{{route('contact')}}">@lang('app.contact')</a></li>
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
                    <a href="https://twitter.com/share?url=https%3A%2F%2Fkikuzosound.com&hashtags=聴診%2C聴診音%2C心音%2C肺音%2C腸音%2Cフィジカルアセスメント」&text=「聴診音が学べる！ポータルサイト" class="twitter-share-button" target="_blank"><img src="../img/snsicon_t@2.png" alt="twitter" width="43"></a>
                    {{-- Twitterのpopup 表示 --}}
                    {{--
            <a href="https://twitter.com/share?url=https%3A%2F%2F3sportal.telemedica.co.jp&hashtags=聴診%2C聴診音%2C心音%2C肺音%2C腸音%2Cフィジカルアセスメント」&text=「聴診音が学べる！ポータルサイト" class="twitter-share-button" target="_blank" onclick="window.open(this.href, 'tweetwindow', 'width = 550, height = 450, personalbar = 0, toolbar = 0, scrollbars = 1, resizable = 1'); return false;"><img src="../img/snsicon_t@2.png" alt="twitter" width="43"></a>
             --}}

                    {{-- Facebook --}}
                    {{-- Facebookのweb page 表示 --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://kikuzosound.com&s=「聴診音が学べる！ポータルサイト」" target="_blank"><img src="../img/snsicon_f@2.png" alt="facebook" width="43"></a>
                    {{-- Facebookのpopup 表示 --}}
                    {{--
            <a href="http://www.facebook.com/share.php?u=https://3sportal.telemedica.co.jp" onclick="window.open(this.href, 'FBwindow', 'width = 650, height = 450, menubar = no, toolbar = no, scrollbars = yes'); return false;"><img src="../img/snsicon_f@2.png" alt="facebook" width="43"></a>
             --}}

                    {{-- Line --}}
                    <!-- 「聴診音が学べる！ポータルサイト https://3sportal.telemedica.co.jp」 -->
                    <a href="https://timeline.line.me/social-plugin/share?url=https%3A%2F%2Fkikuzosound.com" target="_blank"><img src="../img/snsicon_l@2.png" width="43" height="43"/></a>

                    {{-- Line dynamic取得今のurl( popup表示 ) --}}
                    {{--
            <div class="line-it-button" style="display: none;" data-type="share-a" data-lang="ja"></div>
            <script src="//scdn.line-apps.com/n/line_it/thirdparty/loader.min.js" async="async" defer="defer"></script>
              --}}
                </div>
                <!-- /.sns_link -->
                <!-- .copyright -->
                <p class="copyright">Copyright ©2020 Telemedica Inc. All rights reserved</p>
                <!-- /.copyright -->
            </div>
            <!-- /.footer_inner -->
        </div>
        <!--*********************************** /#footer ***********************************-->

        <div id="overlay"></div>
        <!-- /#overlay -->
    </body>
</html>
