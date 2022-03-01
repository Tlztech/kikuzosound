@extends('layouts.app')

@section('title','kikuzo')

@section('breadcrumb')
{!! Breadcrumbs::render('kk01') !!}
@endsection


<html class="page" lang="ja">
<!--<![endif]-->
<!-- ＝＝＝＝＝＝＝　ここからhead　＝＝＝＝＝＝＝ -->

<head>
    <style type="text/css">
    .recentcomments a {
        display: inline !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    body {
        background-color: #a3daed;
    }

    .site {
        font-size: 16px;
        font-family: "Open Sans", Helvetica, Arial, sans-serif;
        width: auto;
        max-width: 900px;
        background-color: #FFF;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    }

    .alignleft {
        float: left;
    }

    .site p {
        line-height: 2.3;
    }
    .entry-content,
    .content-width {
        width: auto;
        max-width: 800px;
        margin: 0 auto;
    }

    .entry-content p {
        line-height: 2.3;
    }

    .wp-image-1674 {
        width: 100%;
        height: auto;
    }

    .bg-youtube-content {
        box-sizing: border-box;
        margin: 0 auto;
        max-width: 800px;
    }
    .bg-youtube .sp-bgimage {
        display: none;
    }
    @media (max-width:740px){
        p.title span strong span{
            font-size:26px !important;
        }
        p.title{
            text-align:center !important;
        }
        p.p-subtitle span{
            font-size:14px !important;
        }
    }
    @media (max-width:320px){
        p.title span strong span{
            font-size:24px !important;
        }
        p.p-subtitle span{
            font-size:13px !important;
        }

    }
   
    </style>

    <meta name="google" content="notranslate" />
</head>
@section('content')
<!-- ＝＝＝＝＝＝＝　headここまで　＝＝＝＝＝＝＝ -->
<div id="content" role="main" class="kk01">
    <article id="post-118" class="post-118 page type-page status-publish">
        <header class="entry-header">
            <!-- <h1 class="entry-title">kikuzo01</h1> -->
        </header>

        <div class="entry-content">
            <p style="text-align: left;" class="title"><span style="color: #3b79bf; margin-top: 50px;"><strong><span
                            style="font-size: 24pt; font-family: 'ヒラギノ丸ゴ Pro W4', 'ヒラギノ丸ゴ Pro', 'Hiragino Maru Gothic Pro', 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'HG丸ｺﾞｼｯｸM-PRO', 'HGMaruGothicMPRO';">@lang('kk01.title1')</span></strong></span>
            </p>
            <p style="text-align: center;"><span style="color: #3b79bf;"><strong><span
                            style="font-size: 24pt; font-family: 'ヒラギノ丸ゴ Pro W4', 'ヒラギノ丸ゴ Pro', 'Hiragino Maru Gothic Pro', 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'HG丸ｺﾞｼｯｸM-PRO', 'HGMaruGothicMPRO';"><span
                                style="font-family: 'メイリオ', Meiryo, 'ヒラギノ角ゴ Pro W3', 'Hiragino Kaku Gothic Pro', 'ＭＳ Ｐゴシック', sans-serif; font-size: 36pt;">@lang('kk01.title2')</span></span></strong></span>
            </p>
            @lang('kk01.title3')
            <p class="img-wrapper"><img class="aligncenter wp-image-170 "
                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/FD652B2A-17FC-4273-A7F8-5632838F17B4-e1565579085325.jpg"
                    alt="" width="791" height="487"
                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/FD652B2A-17FC-4273-A7F8-5632838F17B4-e1565579085325.jpg 2457w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/FD652B2A-17FC-4273-A7F8-5632838F17B4-e1565579085325-300x184.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/FD652B2A-17FC-4273-A7F8-5632838F17B4-e1565579085325-768x472.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/FD652B2A-17FC-4273-A7F8-5632838F17B4-e1565579085325-1024x630.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/FD652B2A-17FC-4273-A7F8-5632838F17B4-e1565579085325-624x384.jpg 624w"
                    sizes="(max-width: 791px) 100vw, 791px"></p>
            <p>&nbsp;</p>
            @lang('kk01.features')
            <p>&nbsp;</p>
            <p><iframe title="聴診スピーカ聴くゾウ" width="625" height="352"
                    src="https://www.youtube.com/embed/skFCKkUcraQ?feature=oembed" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen=""></iframe></p>
            <p>.<br>
                <span style="font-size: 18pt;">@lang('kk01.feature1.title')</span></p>
            <div>
                <img class="alignleft wp-image-1674 size-full" src="/img/stethospeakers_en.png" alt="" width="1280"
                    height="572"
                    srcset="/img/stethospeakers_en.png 1280w, /img/stethospeakers_en.png 300w, /img/stethospeakers_en.png 768w, /img/stethospeakers_en.png 1024w, /img/stethospeakers_en.png 624w"
                    sizes="(max-width: 1280px) 100vw, 1280px">
            </div>
            @lang('kk01.feature1.description')
            <p>&nbsp;</p>
            <p><span style="font-size: 18pt;"><strong>@lang('kk01.feature2.title')</strong></span></p>
            <p  class="img-wrapper">
                @if(config('app.locale') == 'en')
                <img class="aligncenter wp-image-1673 size-full" src="/img/frequency_graph_en.png" alt="" width="861"
                    height="324"
                    srcset="/img/frequency_graph_en.png 861w, /img/frequency_graph_en.png 300w, /img/frequency_graph_en.png 768w, /img/frequency_graph_en.png 624w"
                    sizes="(max-width: 861px) 100vw, 861px">
                @else
                <img class="aligncenter wp-image-1673 size-full"
                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/周波数-e1571292601129.png"
                    alt="" width="861" height="324"
                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/周波数-e1571292601129.png 861w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/周波数-e1571292601129-300x113.png 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/周波数-e1571292601129-768x289.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/周波数-e1571292601129-624x235.png 624w"
                    sizes="(max-width: 861px) 100vw, 861px">
                @endif
            </p>
            @lang('kk01.feature2.description')
            <p>&nbsp;</p>
            <p><span style="font-size: 18pt;"><strong>@lang('kk01.feature3.title')</strong></span></p>
            <p>
                <img class="wp-image-1677 alignleft feature-img" src="/img/kkz003-1.png" alt="" width="234" height="233">
                <span>@lang('kk01.feature3.description')</span>   
            </p>
            <p class="d-none">&nbsp;</p>
            <br class="d-none"><br class="d-none">
            <p><span style="font-size: 18pt;"><strong>@lang('kk01.feature4.title')</strong></span></p>
            <p>
                <img class="wp-image-316 alignleft feature-img" src="/img/004_kikuzo.png" alt="" width="234">
                <span>@lang('kk01.feature4.description')</span>
            </p>
            <p class="d-none">&nbsp;</p>
            <p class="d-none">&nbsp;</p>
            <p class="d-none">&nbsp;</p>
            <p class="d-none">&nbsp;</p>
            <p><br><span style="font-size: 18pt;"><strong>@lang('kk01.feature5.title')</strong></span></p>
            <p>
                <img class=" wp-image-1700 alignleft feature-img" src="/img/005_kikuzo.png" alt="test" width="234" height="232">
                <span>@lang('kk01.feature5.description')</span>
            </p>

            <p>&nbsp;</p>
        </div>
    </article>

</div><!-- #page -->
@endsection

</html>
