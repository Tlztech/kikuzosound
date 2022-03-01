@extends('layouts.app')
<?php
    $locale = Session::get('lang');
    App::setLocale($locale);
?>
@section('breadcrumb')
{!! Breadcrumbs::render('contents') !!}
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
        background-color: #FFF;    }

    .site p {
        line-height: 1.7;
        font-weight: bold;
    }

    .entry-content,
    .content-width {
        width: auto;
        max-width: 800px;
        margin: auto;
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

    .site {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    }

    @media only screen and (max-width: 899px) {
        .head-image {
            background-size: cover !important;
        }
        .entry-content, .content-width {
            margin: 0 24px;
            padding-top: 10px;
        }
    }
    @media (min-width:740px){
        .hfeed.site{
            margin-top:20px;
        }
    }
    @media(max-width:740px){
        .wp-image-2397.aligncenter{
            width:100%;
            height:auto;
        }
        p.title span{
            font-size:12px !important;
        }
        p.sub-title span{
            font-size:11px !important;
        }
    }
    </style>

</head>

<div id="page" class="hfeed site">
    <div id="main" class="wrapper">
        <div id="primary" class="site-content" >

            <div id="content" role="main">


                <article id="post-2402" class="post-2402 page type-page status-publish">
                    <header class="entry-header">
                        <!-- <h1 class="entry-title">kikuzosound.com-lp01</h1> -->
                    </header>

                    <div class="entry-content">
                        <p class="feature-title">@lang('library-lp.Features')</p>
                        <p class="title"><span style="font-size: 14pt; font-family: arial black, sans-serif; color: #333333;"><span
                                    style="font-size: 14pt; font-family: arial black, sans-serif;" >@lang('library-lp.p1')</span><br>
                            </span></p>
                        <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-size: 12pt; color: #333333; font-family: arial black, sans-serif;">@lang('library-lp.p2')</span></p>
                        <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-size: 12pt; color: #333333; font-family: arial black, sans-serif;">@lang('library-lp.p3')</span></p>
                        <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-size: 12pt; color: #333333; font-family: arial black, sans-serif;">@lang('library-lp.p4')</span></p>
                        <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-size: 12pt; color: #333333; font-family: arial black, sans-serif;">@lang('library-lp.p5')</span></p>
                        <p><img class="wp-image-2397 aligncenter"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/bandicam-2020-05-02-13-29-59-369-e1588394949372.jpg"
                                alt="" width="637" height="341"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/bandicam-2020-05-02-13-29-59-369-e1588394949372.jpg 1171w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/bandicam-2020-05-02-13-29-59-369-e1588394949372-300x160.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/bandicam-2020-05-02-13-29-59-369-e1588394949372-768x411.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/bandicam-2020-05-02-13-29-59-369-e1588394949372-1024x547.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2020/05/bandicam-2020-05-02-13-29-59-369-e1588394949372-624x334.jpg 624w"
                                sizes="(max-width: 637px) 100vw, 637px"></p>
                        <p>&nbsp;</p>
                        <p class="title"><span style="font-family: arial black, sans-serif; font-size: 14pt; color: #333333;">@lang('library-lp.p6')</span></p>
                        <p><iframe title="Kikuzosound.com Libraly and sort" width="625" height="352"
                                src="https://www.youtube.com/embed/Wlegw_su_Wg?feature=oembed" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="" data-gtm-yt-inspected-31282268_5="true"></iframe></p>
                        <p style="padding-left: 80px;">@lang('library-lp.p12')</p>
                        <p>&nbsp;</p>
                        <p class="title"><span style="font-size: 14pt; font-family: arial black, sans-serif; color: #333333;">@lang('library-lp.p8')</span></p> 
                       
                        <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-family: arial black, sans-serif; font-size: 12pt; color: #333333;"> @lang('library-lp.p9')</span></p>
                         
                          <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-family: arial black, sans-serif; font-size: 12pt; color: #333333;">@lang('library-lp.p10')</span>
                        </p>
                        <p style="padding-left: 40px;" class="sub-title"><span
                                style="font-family: arial black, sans-serif; font-size: 12pt; color: #333333;">@lang('library-lp.p11')</span></p>
                        
                        <p><iframe title="Kikuzosound.com Bookmarks" width="625" height="352"
                                src="https://www.youtube.com/embed/AROlw0GkLIo?feature=oembed" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="" data-gtm-yt-inspected-31282268_5="true"></iframe></p>
                        <p style="padding-left: 80px;">@lang('library-lp.p12')</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        
                        <p style="text-align: center; padding-bottom: 44px;">
                        <a class="submit_btn" href="/member_login" style="cursor:pointer;width: 240px;">@lang('library-lp.p13')</a>
                        </p>
                    </div><!-- .entry-content -->
                </article><!-- #post -->

            </div><!-- #content -->
        </div><!-- #primary -->


    </div><!-- #main .wrapper -->
</div><!-- #page -->
@endsection