@extends('layouts.app')
<link rel="stylesheet" id="twentytwelve-style-css"
    href="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/themes/lptemp_colorful/style.css?ver=5.2.5" type="text/css"
    media="all">

<style type="text/css">
body {
    background-color: #FFF;
}

.site {
    font-size: 16px;
    font-family: "Open Sans", Helvetica, Arial, sans-serif;
    width: auto;
    max-width: 900px;
    background-color: #e8e8e8;
}

.site p {
    line-height: 2.3;
}

.entry-content,
.content-width {
    width: auto;
    max-width: 800px;
}

img.wide {
    max-width: 900px;
}

.bg-youtube .sp-bgimage {
    display: none;
}

.site {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
}

@media only screen and (max-width: 768px) {
    #primary {
        padding: 0 20px;
    }

    input[type="text"] {
        width: 216px;
    }
}
</style>

<!-- ＝＝＝＝＝＝＝　ここからbody　＝＝＝＝＝＝＝ -->
@section('breadcrumb')
{!! Breadcrumbs::render('landing page') !!}
@endsection

@section('content')

<div id="page" class="hfeed site">
    <header id="masthead" class="site-header" role="banner">
    </header><!-- #masthead -->

    <div id="main" class="wrapper">
        <div id="primary" class="site-content">

            <div id="content" role="main">


                <article id="post-276" class="post-276 page type-page status-publish">
                    <header class="entry-header">
                        <!-- <h1 class="entry-title">video01</h1> -->
                    </header>

                    <div class="entry-content">
                        @lang('landing-page.title')
                        <table class="pressed">
                            <tbody>
                                <tr>
                                    <td>
                                        <h6>@lang('landing-page.paragraph1')</h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p>&nbsp;</p>
                        <p><img class="aligncenter wp-image-32 size-full"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/computer-3343887_1280.jpg"
                                alt="" width="1280" height="758"></p>
                        <p>&nbsp;</p>
                        <h6>@lang('landing-page.paragraph2')</h6>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <table class="shikaku" style="border: 1px solid #000000;" border="1">
                            <tbody>
                                <tr>
                                    <td><img src="https://lptemp.com/dx/wp-content/uploads/2013/11/check_green.png"
                                            alt="" width="32" height="32">@lang('landing-page.checklist1')
                                        <p><img src="https://lptemp.com/dx/wp-content/uploads/2013/11/check_green.png"
                                                alt="" width="32" height="32">@lang('landing-page.checklist2')</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph3')
                        <p>&nbsp;</p>
                        <h2 style="text-align: center;"><span
                                style="font-size: 24pt;">@lang('landing-page.title2')</span></h2>
                        <p><span style="font-size: 14pt;"><img class="aligncenter size-full wp-image-35"
                                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/despair-1235582_1280.jpg"
                                    alt="" width="1280" height="853"
                                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/despair-1235582_1280.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/despair-1235582_1280-300x200.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/despair-1235582_1280-768x512.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/despair-1235582_1280-1024x682.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/despair-1235582_1280-624x416.jpg 624w"
                                    sizes="(max-width: 1280px) 100vw, 1280px"></span></p>
                        <p><span style="font-size: 14pt;">@lang('landing-page.paragraph4')</span><br>
                            <span style="font-size: 14pt;">@lang('landing-page.paragraph5')</span><br>
                            <span style="font-size: 14pt;">　@lang('landing-page.paragraph6')</p>
                        <p><span style="font-size: 14pt;">@lang('landing-page.paragraph7')</p>
                        @lang('landing-page.paragraph8')
                        <p>&nbsp;</p>
                        <p><img class="aligncenter wp-image-37 size-full"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/stethoscope-1584223_1280.jpg"
                                alt="" width="1280" height="960"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/stethoscope-1584223_1280.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/stethoscope-1584223_1280-300x225.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/stethoscope-1584223_1280-768x576.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/stethoscope-1584223_1280-1024x768.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/stethoscope-1584223_1280-624x468.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px"></p>
                        <p>&nbsp;</p>
                        <h2 style="text-align: center;"><span
                                style="font-size: 24pt;">@lang('landing-page.title3')</span></h2>
                        @lang('landing-page.paragraph9')
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-36"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/beard-2286440_1280.jpg"
                                alt="" width="1280" height="854"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/beard-2286440_1280.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/beard-2286440_1280-300x200.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/beard-2286440_1280-768x512.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/beard-2286440_1280-1024x683.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/beard-2286440_1280-624x416.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px"></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph10')
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-38"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bear-1863992_1280.jpg"
                                alt="" width="1280" height="661"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bear-1863992_1280.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bear-1863992_1280-300x155.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bear-1863992_1280-768x397.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bear-1863992_1280-1024x529.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bear-1863992_1280-624x322.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px"></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph11')
                        <p><img class="aligncenter size-full wp-image-39"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/pic_fujiki_takashina.jpg"
                                alt="" width="1209" height="907"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/pic_fujiki_takashina.jpg 1209w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/pic_fujiki_takashina-300x225.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/pic_fujiki_takashina-768x576.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/pic_fujiki_takashina-1024x768.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/pic_fujiki_takashina-624x468.jpg 624w"
                                sizes="(max-width: 1209px) 100vw, 1209px"></p>
                        @lang('landing-page.paragraph12')
                        <p><span style="font-size: 14pt;"><img class="aligncenter size-full wp-image-46"
                                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyoushin.jpg"
                                    alt="" width="1082" height="607"
                                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyoushin.jpg 1082w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyoushin-300x168.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyoushin-768x431.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyoushin-1024x574.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyoushin-624x350.jpg 624w"
                                    sizes="(max-width: 1082px) 100vw, 1082px"></span></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph13')
                        <p><img class="aligncenter size-full wp-image-47"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/sril.jpg" alt=""
                                width="1163" height="652"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/sril.jpg 1163w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/sril-300x168.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/sril-768x431.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/sril-1024x574.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/sril-624x350.jpg 624w"
                                sizes="(max-width: 1163px) 100vw, 1163px"></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph14')
                        <p>&nbsp;</p>
                        <p><iframe src="https://www.youtube.com/embed/OfEGFHaoKBo?rel=0&amp;showinfo=0" width="560"
                                height="315" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.h2')
                        <p>
                            @if(config('app.locale') == 'en')
                            <img class="aligncenter size-full wp-image-472" src="/img/realistic.png" alt="" width="621"
                                height="152" srcset="/img/realistic.png 621w, /img/realistic.png 300w"
                                sizes="(max-width: 621px) 100vw, 621px">
                            @else
                            <img class="aligncenter size-full wp-image-472"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/09/bandicam-2019-08-18-04-35-46-076-e1570107210220.jpg"
                                alt="" width="621" height="152"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/09/bandicam-2019-08-18-04-35-46-076-e1570107210220.jpg 621w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/09/bandicam-2019-08-18-04-35-46-076-e1570107210220-300x73.jpg 300w"
                                sizes="(max-width: 621px) 100vw, 621px">
                            @endif
                        </p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph15')
                        <table style="background-color: #f0f0f0; height: 70px;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0 10px;">@lang('landing-page.td1')</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>
                            @if(config('app.locale') == 'en')
                            <img class="aligncenter size-full wp-image-95" src="/img/mr_en.png" alt="" width="1280"
                                height="720"
                                srcset="/img/mr_en.png 1280w, /img/mr_en.png 300w, /img/mr_en.png 768w, /img/mr_en.png 1024w, /img/mr_en.png 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px">
                            @else
                            <img class="aligncenter size-full wp-image-95"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/case1.jpg" alt=""
                                width="1280" height="720"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/case1.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/case1-300x169.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/case1-768x432.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/case1-1024x576.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/case1-624x351.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px">
                            @endif
                        </p>
                        <p>
                            @if(config('app.locale') == 'en')
                            <img class="aligncenter size-full wp-image-93" src="/img/sound_en.jpeg" alt="" width="997"
                                height="560">
                            @else
                            <img class="aligncenter size-full wp-image-93"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3.jpg" alt=""
                                width="997" height="560">
                            @endif
                        </p>
                        <p>
                            @if(config('app.locale') == 'en')
                            <img class="aligncenter size-full wp-image-94" src="/img/case_list_en.png" alt=""
                                width="997" height="560">
                            @else
                            <img class="aligncenter size-full wp-image-94"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/4-1.jpg" alt=""
                                width="997" height="560">
                            @endif

                        </p>
                        <table style="background-color: #f0f0f0; height: 70px;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0 10px;">@lang('landing-page.td2')</td>
                                </tr>
                            </tbody>
                        </table>
                        <h3 style="text-align: center;"><span style="font-size: 18pt;">
                                @if(config('app.locale') == 'en')
                                <img class="aligncenter size-full wp-image-78" src="/img/kikuzo_en.png" alt=""
                                    width="670" height="951"
                                    srcset="/img/kikuzo_en.png 670w, /img/kikuzo_en.png 211w, /img/kikuzo_en.png 624w"
                                    sizes="(max-width: 670px) 100vw, 670px"></span>
                            @else
                            <img class="aligncenter size-full wp-image-78"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-02-697.jpg"
                                alt="" width="670" height="951"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-02-697.jpg 670w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-02-697-211x300.jpg 211w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-02-697-624x886.jpg 624w"
                                sizes="(max-width: 670px) 100vw, 670px"></span>
                            @endif
                        </h3>
                        <h3></h3>
                        <h3 style="text-align: center;"><span
                                style="font-size: 18pt;">@lang('landing-page.title4')</span></h3>
                        <table class="marukaku" style="border: 1px solid #000000; width: 782px; height: 359px;"
                            border="1">
                            <tbody>
                                <tr>
                                    <td style="width: 740.8px;"><img
                                            src="https://lptemp.com/dx/wp-content/uploads/2013/11/check_green.png"
                                            alt="" width="32" height="32">　<span
                                            style="font-size: 14pt;">@lang('landing-page.checklist.list1')</span>
                                        <p></p>
                                        <p><img src="https://lptemp.com/dx/wp-content/uploads/2013/11/check_green.png"
                                                alt="" width="32" height="32">　@lang('landing-page.checklist.list2')
                                            <p><img src="https://lptemp.com/dx/wp-content/uploads/2013/11/check_green.png"
                                                    alt="" width="32" height="32">　<span
                                                    style="font-size: 14pt;">@lang('landing-page.checklist.list3')</span>
                                            </p>
                                            <p><img src="https://lptemp.com/dx/wp-content/uploads/2013/11/check_green.png"
                                                    alt="" width="32" height="32">　@lang('landing-page.checklist.list4')
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p><span style="font-size: 14pt;">@lang('landing-page.paragraph16')</span></p>
                        <p>&nbsp;</p>
                        <h2 style="text-align: center;"><span
                                style="font-size: 24pt;">@lang('landing-page.title5')</span></h2>
                        <p><img class="aligncenter size-full wp-image-58"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyousihn.jpg"
                                alt="" width="640" height="427"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyousihn.jpg 640w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyousihn-300x200.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/tyousihn-624x416.jpg 624w"
                                sizes="(max-width: 640px) 100vw, 640px"></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph17')
                        <p>&nbsp;</p>
                        <p><strong><span style="text-decoration: underline;"><span
                                        style="font-size: 14pt;">@lang('landing-page.video1')</span></span></strong>
                        </p>
                        <p><iframe src="https://www.youtube.com/embed/pc4DKj4q9y0" width="560" height="315"
                                frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
                        <p>&nbsp;</p>
                        <p><span style="text-decoration: underline;"><strong><span
                                        style="font-size: 14pt;">@lang('landing-page.video2')</span></strong></span>
                        </p>
                        <p><iframe src="https://www.youtube.com/embed/onIc8fM97Vk" width="560" height="315"
                                frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
                        @lang('landing-page.paragraph18')
                        <p><img class="aligncenter size-full wp-image-59"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/treatment-1327811_1280.jpg"
                                alt="" width="1280" height="850"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/treatment-1327811_1280.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/treatment-1327811_1280-300x199.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/treatment-1327811_1280-768x510.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/treatment-1327811_1280-1024x680.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/treatment-1327811_1280-624x414.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px"></p>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph19')
                        <table class="submit"
                            style="border: 2px solid #000000; background-color: #f3f1f2; height: 228px;" border="2"
                            frame="box" rules="all" align="center">
                            <tbody>
                                <tr style="height: 228px;">
                                    <td style="height: 228px; width: 796px;">
                                        <form action="https://mm.jcity.com/MM_PublicSubscribeProc.cfm" method="post"
                                            target="MMApp"><input name="UserID" type="hidden" value="tlmdc"><input
                                                name="MagazineID" type="hidden" value="2"><input name="HTMLMail"
                                                type="hidden" value="1"><input name="MoreItem" type="hidden" value="1">
                                            <p></p>
                                            <table border="0">
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align: center;" align="left">
                                                            @lang('landing-page.label1')</td>
                                                        <td style="text-align: center;" align="left"><input
                                                                maxlength="50" name="sName"
                                                                style="height:40px !important;"
                                                                type="text">&nbsp;@lang('landing-page.required')
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center;" align="left">
                                                            @lang('landing-page.label2')</td>
                                                        <td style="text-align: center;" align="left"><input
                                                                maxlength="100" name="sEmail"
                                                                style="height:40px !important;"
                                                                type="text">&nbsp;@lang('landing-page.required')
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center;" colspan="2" align="center">
                                                            <input type="submit"
                                                                value="@lang('landing-page.btn_submit')"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @lang('landing-page.paragraph19')
                        <p><img class="aligncenter wp-image-49 size-medium"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/takashina-250x300.jpg"
                                alt="" width="250" height="300"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/takashina-250x300.jpg 250w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/takashina.jpg 500w"
                                sizes="(max-width: 250px) 100vw, 250px"></p>
                        @lang('landing-page.paragraph20')
                        <h3 style="text-align: center;">
                            <span style="font-size: 18pt;">
                                @if(config('app.locale') == 'en')
                                <img class="aligncenter size-full wp-image-349" src="/img/awards_en.png" alt=""
                                    width="1499" height="718"
                                    srcset="/img/awards_en.png 1499w, /img/awards_en.png 300w, /img/awards_en.png 768w, /img/awards_en.png 1024w, /img/awards_en.png 624w"
                                    sizes="(max-width: 1499px) 100vw, 1499px">
                                @else
                                <img class="aligncenter size-full wp-image-349"
                                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-22-10-46-19-686.jpg"
                                    alt="" width="1499" height="718"
                                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-22-10-46-19-686.jpg 1499w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-22-10-46-19-686-300x144.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-22-10-46-19-686-768x368.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-22-10-46-19-686-1024x490.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-22-10-46-19-686-624x299.jpg 624w"
                                    sizes="(max-width: 1499px) 100vw, 1499px">
                                @endif
                            </span>
                        </h3>
                        <p>&nbsp;</p>
                        @lang('landing-page.paragraph21')
                        <p>&nbsp;</p>
                        @if(config('app.locale') == 'en')
                        <p><img class="aligncenter size-full wp-image-52" src="/img/nl_green_en.png" alt="" width="1030"
                                height="1122"
                                srcset="/img/nl_green_en.png 1030w, /img/nl_green_en.png 275w, /img/nl_green_en.png 768w, /img/nl_green_en.png 940w, /img/nl_green_en.png 624w"
                                sizes="(max-width: 1030px) 100vw, 1030px"></p>
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-53" src="/img/nl_blue_en.png" alt="" width="1007"
                                height="1036"
                                srcset="/img/nl_blue_en.png 1007w, /img/nl_blue_en.png 292w, /img/nl_blue_en.png 768w, /img/nl_blue_en.png 995w, /img/nl_blue_en.png 624w"
                                sizes="(max-width: 1007px) 100vw, 1007px"></p>
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-51" src="/img/nl_lightgreen_en.png" alt=""
                                width="1021" height="868"
                                srcset="/img/nl_lightgreen_en.png 1021w, /img/nl_lightgreen_en.png 300w, /img/nl_lightgreen_en.png 768w, /img/nl_lightgreen_en.png 624w"
                                sizes="(max-width: 1021px) 100vw, 1021px"></p>
                        @else
                        <p><img class="aligncenter size-full wp-image-52"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/003.png" alt=""
                                width="1030" height="1122"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/003.png 1030w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/003-275x300.png 275w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/003-768x837.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/003-940x1024.png 940w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/003-624x680.png 624w"
                                sizes="(max-width: 1030px) 100vw, 1030px"></p>
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-53"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/004.png" alt=""
                                width="1007" height="1036"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/004.png 1007w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/004-292x300.png 292w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/004-768x790.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/004-995x1024.png 995w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/004-624x642.png 624w"
                                sizes="(max-width: 1007px) 100vw, 1007px"></p>
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-51"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/002.png" alt=""
                                width="1021" height="868"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/002.png 1021w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/002-300x255.png 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/002-768x653.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/002-624x530.png 624w"
                                sizes="(max-width: 1021px) 100vw, 1021px"></p>
                        @endif
                        @lang('landing-page.faqs')
                        <p>&nbsp;</p>
                        <h3><span style="font-size: 24pt;">@lang('landing-page.title')</span><br>
                            @if(config('app.locale') == 'en')
                            <img class="aligncenter size-full wp-image-79" src="/img/how_to_use_en.png" alt=""
                                width="670" height="675"
                                srcset="/img/how_to_use_en.png 670w, /img/how_to_use_en.png 150w, /img/how_to_use_en.png 298w, /img/how_to_use_en.png 624w"
                                sizes="(max-width: 670px) 100vw, 670px"></h3>
                        @else
                        <img class="aligncenter size-full wp-image-79"
                            src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-25-065.jpg"
                            alt="" width="670" height="675"
                            srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-25-065.jpg 670w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-25-065-150x150.jpg 150w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-25-065-298x300.jpg 298w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/bandicam-2019-08-03-16-25-25-065-624x629.jpg 624w"
                            sizes="(max-width: 670px) 100vw, 670px"></h3>
                        @endif
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        @lang('landing-page.steps')
                        <div style="border: 2px solid #000000; background-color: #f3f1f2; height: 228px;">
                            <form action="https://mm.jcity.com/MM_PublicSubscribeProc.cfm" method="post" target="MMApp">
                                <input name="UserID" type="hidden" value="tlmdc"><input name="MagazineID" type="hidden"
                                    value="2"><input name="HTMLMail" type="hidden" value="1"><input name="MoreItem"
                                    type="hidden" value="1">
                                <table style="width: 80%; margin: 35px auto;">
                                    <tr>
                                        <td><label>@lang('landing-page.label1')</label></td>
                                        <td><input maxlength="50" name="sName" type="text"
                                                style="height: 40px;">&nbsp;@lang('landing-page.required')</td>
                                    </tr>

                                    <tr>
                                        <td><label>@lang('landing-page.label2')</label></td>
                                        <td><input maxlength="100" name="sEmail" type="text"
                                                style="height: 40px;">&nbsp;@lang('landing-page.required')</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align:center">
                                            <input type="submit" value="@lang('landing-page.btn_submit')">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <p style="text-align: center;">
                            <span><strong>&nbsp;</strong></span>
                            <!-- <span style="font-size: 18pt; background-color: #ffff00;"><strong>&nbsp;</strong></span> -->
                        </p>
                        @lang('landing-page.paragraph22')
                        <p>&nbsp;</p>
                        <p style="text-align: center;"><span style="font-size: 10pt;">Copyright (C) 2019 <a
                                    href="https://telemedica.sakura.ne.jp/3sp-lp/company/">@lang('landing-page.telemedica')</a>
                                All Rights Reserved.</span></p>
                        <p style="text-align: center;"><a href="https://telemedica.sakura.ne.jp/3sp-lp/privacy/"><span
                                    style="font-size: 10pt;">@lang('landing-page.privacy')</span></a></p>
                    </div><!-- .entry-content -->
                </article><!-- #post -->

            </div><!-- #content -->
        </div><!-- #primary -->


    </div><!-- #main .wrapper -->
</div><!-- #page -->
@endsection