@extends('layouts.app')

<?php
    $locale = Session::get('lang');
    App::setLocale($locale);
?>

@section('breadcrumb')
{!! Breadcrumbs::render('quizpacks') !!}
@endsection

@section('content')

<head>
    <style type="text/css">
    html {
        overflow-y: auto;
    }
    body {
        position: relative;
    }

    .site {
        font-size: 16px;
        font-family: "Open Sans", Helvetica, Arial, sans-serif;
        width: auto;
        max-width: 900px;
        background-color: #FFF;
    }

    .site p {
        line-height: 2.3;
        font-weight: bold;
    }

    .entry-content,
    .content-width {
        width: auto;
        max-width: 800px;
        margin: 0 auto;
    }

    .wp-image-2453 {
        height: 100%;
        width: 100%;
    }

    #page {
        margin: 0 auto;
    }

    .bg-youtube-content {
        box-sizing: border-box;
        margin: 0 auto;
        max-width: 800px;
    }

    .bg-youtube .sp-bgimage {
        display: none;
    }

    ol li {
        margin: 0px 0 0 24px !important;
        font-weight: bold;
        padding-top: 40px;
        color: #333333;
        font-size: 18pt;
        font-family: arial black, sans-serif;
    }

    .site {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    }

    @media only screen and (max-width: 899px) {
        .head-image {
            background-size: cover !important;
        }

        #primary {
            margin: auto 24px;
        }
    }
    @media (min-width:740px){
        .hfeed.site{
            margin-top:20px;
        }
    }
    @media(max-width:740px){
        ol li{
            font-size:14px;
        }
        p span{
            font-size:12px !important;
        }
    }
    @media(min-width:768px) and (max-width:1024px){
        ol li{
            padding-left: 10px;
        }
    }
    </style>

    <!-- フォーム入力設定
    ================================================== -->
    <script type="text/javascript">
    $(function() {
        $('.formbox').each(function() {
            $(this).val($(this).attr('title')).css('color', '#969696');
        });

        $('.formbox').focus(function() {
            $(this).val('').css('color', '#000');
        });

        $('.formbox').blur(function() {
            $(this).val($(this).attr('title')).css('color', '#969696');
        });

        $('.focus').focus(function() {
            if (this.value == "メールアドレスを入力") {
                $(this).val("").css("color", "#000000");
            } else if (this.value == "名前を入力") {
                $(this).val("").css("color", "#000000");
            }
        });

        $('.focus').blur(function() {
            if (this.value == "") {
                if (this.name == "d[0]") {
                    $(this).val("メールアドレスを入力").css("color", "#969696");
                } else {
                    $(this).val("名前を入力").css("color", "#969696");
                }
            }
        });

        $(window).bind('load resize', function() {
            if ($(window).width() > 900) {
                $('.full-width').each(function() {
                    $(this).height($(this).height());
                    if (!$(this).find('.absolute')[0]) {
                        $(this).html('<div class="absolute"><div class="content-width">' + $(
                            this).html() + '</div></div>');
                        $(this).find('.absolute').css('backgroundColor', $(this).find(
                            '.content-width').children().eq(0).css('backgroundColor'));
                        $(this).find('.absolute').css('backgroundImage', $(this).find(
                            '.content-width').children().eq(0).css('backgroundImage'));
                        $(this).find('.absolute').css('backgroundPosition', $(this).find(
                            '.content-width').children().eq(0).css(
                            'backgroundPosition'));
                        $(this).find('.absolute').css('backgroundRepeat', $(this).find(
                            '.content-width').children().eq(0).css('backgroundRepeat'));
                        if ($(this).find('.content-width').children().eq(0).hasClass('tilt')) {
                            $(this).find('.absolute').addClass('tilt');
                        }
                    }
                    $(this).find('.absolute').width($(window).width());
                    $(this).find('.content-width').children().eq(0).css('backgroundColor', '');
                    $(this).find('.content-width').children().eq(0).css('backgroundImage', '');
                    $(this).find('.content-width').children().eq(0).css('backgroundPosition',
                        '');
                    $(this).find('.content-width').children().eq(0).css('backgroundRepeat', '');
                    $(this).find('.content-width').children().eq(0).removeClass('tilt');
                });

                $('.full-width-image').each(function() {
                    if (!$(this).find('.absolute')[0]) {
                        $(this).html('<div class="absolute">' + $(this).html() + '</div>');
                    }
                    $(this).find('.absolute').width($(window).width());
                    if ($(this).find('img').height() > 0) {
                        $(this).height($(this).find('img').height());
                    } else {
                        $(this).height($(this).find('img').attr('height'));
                    }
                });

                $('.full-width-video').each(function() {
                    $(this).height($(this).find('video').height());
                    if (!$(this).find('.absolute')[0]) {
                        $(this).html('<div class="absolute">' + $(this).html() + '</div>');
                    }
                    $(this).find('.absolute').width($(window).width());
                });

                $('.full-width-youtube').each(function() {
                    $(this).height($(this).find('.bg-youtube-content').height());
                    if (!$(this).find('.absolute')[0]) {
                        $(this).html('<div class="absolute"><div class="absolute-inner">' + $(
                            this).html() + '</div></div>');
                    }
                    $(this).find('.absolute').width($(window).width());
                    $(this).find('.absolute-inner').width($(window).width());
                    $(this).find('iframe').height($(this).find('.absolute').width() * 36 / 64);

                    if ($(this).find('.bg-youtube-content').height() > $(this).find('iframe')
                        .height()) {
                        $(this).find('iframe').height($(this).find('.bg-youtube-content')
                            .height());
                        $(this).find('.absolute-inner').width($(this).find('iframe').height() *
                            64 / 36);
                    }

                    $(this).find('.absolute').height($(this).find('.bg-youtube-content')
                        .height());
                    var absoluteLeft = $(this).find('.absolute').width() - $(this).find(
                        '.absolute-inner').width();
                    var absoluteTop = $(this).find('.absolute').height() - $(this).find(
                        'iframe').height();
                    $(this).find('.absolute-inner').css('left', absoluteLeft / 2);
                    $(this).find('iframe').css('top', absoluteTop / 2);
                });
            }

            $('.bg-video').each(function() {
                $(this).height($(this).find('video').height());
            });
        });

        $(window).bind('load', function() {
            $('.youtube').each(function() {
                var timerID;
                var played = 0;
                var box = $(this);
                var iframe = box.find('iframe').eq(0);
                var playerID = box.find('iframe').eq(0).attr('id');
                var player = new YT.Player(playerID, {
                    events: {
                        'onStateChange': function(event) {
                            if (event.data == YT.PlayerState.ENDED) {
                                box.next('.youtube-text').show();
                            }

                            if (event.data == YT.PlayerState.PLAYING) {
                                timerID = setInterval(function() {
                                    played++;
                                    if (iframe.data('show') && played >=
                                        iframe.data('show')) {
                                        box.next('.youtube-text').show();
                                    }
                                }, 1000);
                            } else {
                                clearInterval(timerID);
                            }
                        }
                    }
                });
            });

            $('.bg-youtube').each(function() {
                var box = $(this);
                var iframe = box.find('iframe').eq(0);
                var playerID = box.find('iframe').eq(0).attr('id');
                var player = new YT.Player(playerID, {
                    events: {
                        'onReady': function(event) {
                            //event.target.playVideo();
                            var videourl = event.target.getVideoUrl();
                            var videoid = videourl.substr(videourl.indexOf('v=') +
                                2);
                            event.target.loadPlaylist(videoid);
                            event.target.setLoop(true);
                            event.target.mute();
                        }
                    }
                });
            });
        });

        $('#header iframe').bind('load', function() {
            var content = this.contentWindow.document.documentElement;
            var frameHeight = 100;
            if (document.all) {
                frameHeight = content.scrollHeight;
            } else {
                frameHeight = content.offsetHeight;
            }
            this.style.height = frameHeight + 'px';
        });

        $('#footer iframe').bind('load', function() {
            var content = this.contentWindow.document.documentElement;
            var frameHeight = 100;
            if (document.all) {
                frameHeight = content.scrollHeight;
            } else {
                frameHeight = content.offsetHeight;
            }
            this.style.height = frameHeight + 'px';
        });

    });
    </script>

    <script type="text/javascript">
    $(function() {
        $('img.wide').each(function() {
            $(this).parent().height($(this).height()).css('display', 'block');
            $(this).bind('load', function() {
                $(this).parent().height($(this).height());
            });
        });
        var h = $('.head-image').height();
        $(window).bind('load resize', function() {
            if ($('.head-image').width() < (900 + 100)) {
                $('.head-image').height(h * $('.head-image').width() / (900 + 100));
            }
        });
    });
    </script>

</head>

<div id="page" class="hfeed site">
    <header id="masthead" class="site-header" role="banner">
    </header><!-- #masthead -->
    <div id="main" class="wrapper">
        <div id="primary" class="site-content">
            <div id="content" role="main">
                <article id="post-2402" class="post-2402 page type-page status-publish">
                    <header class="entry-header">
                        <!-- <h1 class="entry-title">kikuzosound.com-lp01</h1> -->
                    </header>

                    <div class="entry-content">
                        <ol>
                            <!-- <li>Heart sound and Lung sound Quizzes</li> -->
                            <li>@lang('quizzes-lp.li1')</li>
                        </ol>
                        <p style="padding-left: 40px;"><span
                                style="font-family: arial black, sans-serif; font-size: 12pt;">@lang('quizzes-lp.p1')</span></p>
                        <p><iframe title="Kikuzosound.com QUIZ Heart sounds" width="625" height="352"
                                src="https://www.youtube.com/embed/d-lMz5WJwEU?feature=oembed" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="" data-gtm-yt-inspected-31282268_5="true"></iframe></p>
                        <p style="text-align:center;font-weight:normal">@lang('quizzes-lp.p4')</p>
                        <p>&nbsp;</p>
                        <p><iframe title="Kikuzosound.com QUIZ Lung sounds" width="625" height="352"
                                src="https://www.youtube.com/embed/nETcQ-qGbCE?feature=oembed" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="" data-gtm-yt-inspected-31282268_5="true"></iframe></p>
                        <p style="text-align:center;font-weight:normal">@lang('quizzes-lp.p4')</p>
                        <p>&nbsp;</p>
                        <ol start="2">
                            <li>@lang('quizzes-lp.li2')</li>
                        </ol>
                        <p><img class="aligncenter size-full wp-image-2453"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/terminal.jpg"
                                alt="" width="1280" height="720"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/terminal.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/terminal-300x169.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/terminal-768x432.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/terminal-1024x576.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/terminal-624x351.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px"></p>
                        <p>&nbsp;</p>

                        <p>&nbsp;</p>
                        <p style="text-align: center;padding-bottom: 44px">
                            <a class="submit_btn" href="/member_login" style="cursor:pointer;width: 240px;">@lang('quizzes-lp.a1')</a>
                        </p>
                    </div><!-- .entry-content -->
                </article><!-- #post -->

            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main .wrapper -->
</div><!-- #page -->

@endsection