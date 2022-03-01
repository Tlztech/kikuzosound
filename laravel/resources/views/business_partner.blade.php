@extends('layouts.app')

@section('title', 'Business Partner')

<?php
	$locale = Session::get('lang');
	App::setLocale($locale);
?>

@section('breadcrumb')
{!! Breadcrumbs::render('business_partner') !!}
@endsection

@section('content')

<head>

    <style type="text/css">
    body {
        background-color: #FFF;
    }

    .site {
        font-size: 16px;
        font-family: "Open Sans", Helvetica, Arial, sans-serif;
        width: auto;
        max-width: 900px;
        background-color: #FFF;
        margin-bottom: 10px;
    }

    .site p {
        line-height: 2.3;
    }
    .site-content {
        margin: 23px;
    }
    .entry-content,
    .content-width {
        width: auto;
        max-width: 800px;
    }
    .entry-content strong span{
        font-size:30px;
    }

    }
    .site {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    }
    @media(max-width:740px){
      .site p {
        text-align:left;
        }
      .entry-content strong span{
        font-size:20px;
        }
      .wp-image-303.alignleft{
        margin-left: 15%;
      }
    }
    @media(max-width:320px){
        .entry-content strong span{
            font-size:16px;
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
                        $(this).html(
                            '<div class="absolute"><div class="content-width">' +
                            $(this).html() + '</div></div>');
                        $(this).find('.absolute').css('backgroundColor', $(this)
                            .find('.content-width').children().eq(0).css(
                                'backgroundColor'));
                        $(this).find('.absolute').css('backgroundImage', $(this)
                            .find('.content-width').children().eq(0).css(
                                'backgroundImage'));
                        $(this).find('.absolute').css('backgroundPosition', $(this)
                            .find('.content-width').children().eq(0).css(
                                'backgroundPosition'));
                        $(this).find('.absolute').css('backgroundRepeat', $(this)
                            .find('.content-width').children().eq(0).css(
                                'backgroundRepeat'));
                        if ($(this).find('.content-width').children().eq(0)
                            .hasClass('tilt')) {
                            $(this).find('.absolute').addClass('tilt');
                        }
                    }
                    $(this).find('.absolute').width($(window).width());
                    $(this).find('.content-width').children().eq(0).css(
                        'backgroundColor', '');
                    $(this).find('.content-width').children().eq(0).css(
                        'backgroundImage', '');
                    $(this).find('.content-width').children().eq(0).css(
                        'backgroundPosition', '');
                    $(this).find('.content-width').children().eq(0).css(
                        'backgroundRepeat', '');
                    $(this).find('.content-width').children().eq(0).removeClass(
                        'tilt');
                });

                $('.full-width-image').each(function() {
                    if (!$(this).find('.absolute')[0]) {
                        $(this).html('<div class="absolute">' + $(this).html() +
                            '</div>');
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
                        $(this).html('<div class="absolute">' + $(this).html() +
                            '</div>');
                    }
                    $(this).find('.absolute').width($(window).width());
                });

                $('.full-width-youtube').each(function() {
                    $(this).height($(this).find('.bg-youtube-content').height());
                    if (!$(this).find('.absolute')[0]) {
                        $(this).html(
                            '<div class="absolute"><div class="absolute-inner">' +
                            $(this).html() + '</div></div>');
                    }
                    $(this).find('.absolute').width($(window).width());
                    $(this).find('.absolute-inner').width($(window).width());
                    $(this).find('iframe').height($(this).find('.absolute')
                        .width() * 36 / 64);

                    if ($(this).find('.bg-youtube-content').height() > $(this).find(
                            'iframe').height()) {
                        $(this).find('iframe').height($(this).find(
                            '.bg-youtube-content').height());
                        $(this).find('.absolute-inner').width($(this).find('iframe')
                            .height() * 64 / 36);
                    }

                    $(this).find('.absolute').height($(this).find(
                        '.bg-youtube-content').height());
                    var absoluteLeft = $(this).find('.absolute').width() - $(this)
                        .find('.absolute-inner').width();
                    var absoluteTop = $(this).find('.absolute').height() - $(this)
                        .find('iframe').height();
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
                                    if (iframe.data('show') &&
                                        played >= iframe.data(
                                            'show')) {
                                        box.next(
                                                '.youtube-text')
                                            .show();
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
                            var videoid = videourl.substr(videourl
                                .indexOf('v=') + 2);
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
<div id="container">
    <div class="container_inner clearfix">
        <div class="contents">
                <div id="page" class="hfeed site" style="margin-top:20px;">
                    <header id="masthead" class="site-header" role="banner">
                    </header><!-- #masthead -->

                    <div id="main" class="wrapper">
                        <div id="primary" class="site-content">

                            <div id="content" role="main">


                                <article id="post-2278" class="post-2278 page type-page status-publish">
                                    <header class="entry-header">
                                        <!-- <h1 class="entry-title">About Kikuzo partners</h1> -->
                                    </header>

                                    <div class="entry-content">
                                        <p><strong><span style="">@lang('business_partner.title')</span></strong></p>
                                        <br>
                                        <p>@lang('business_partner.p1')</p>
                                        <p>&nbsp;</p>
                                        <p><img class="wp-image-303 alignleft" src="img/japan-e-learning.jpg" alt=""
                                                width="214" height="164" sizes="(max-width: 214px) 100vw, 214px" /></p>
                                        <br>
                                        <p>@lang('business_partner.point1')<br />
                                        @lang('business_partner.point2')</p>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                        <p>@lang('business_partner.contact')</p>
                                        <p>@lang('business_partner.contact1')</p>
                                        <p>@lang('business_partner.contact2')</P>
                                        <p>&nbsp;</p>
                                    </div><!-- .entry-content -->
                                </article><!-- #post -->

                            </div><!-- #content -->
                        </div><!-- #primary -->
                    </div><!-- #main .wrapper -->
                </div><!-- #page -->
        </div>

        <div class="side_column">
            <!-- Aboutリンクー -->
            @include('layouts.about_menus')
            <!-- 聴診専用スピーカとは？ -->
            @include('layouts.whatspeaker')
        </div>
    </div>
</div>
@endsection