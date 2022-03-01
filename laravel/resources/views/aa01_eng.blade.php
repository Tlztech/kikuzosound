@extends('layouts.app')

@section('title', 'Ausculaide')

@section('breadcrumb')
{!! Breadcrumbs::render('aa01_eng') !!}
@endsection

@section('content')
<?php
    $locale = Session::get('lang');
    App::setLocale($locale);
?>

    <link rel='stylesheet' id='twentytwelve-style-css'
        href='https://telemedica.sakura.ne.jp/3sp-lp/wp-content/themes/lptemp_colorful/style.css?ver=5.2.5'
        type='text/css' media='all' />

    <style type="text/css">
    body {
        background-color: #22aab6;
    }

    .site {
        font-size: 16px;
        font-family: "Open Sans", Helvetica, Arial, sans-serif;
        width: auto;
        max-width: 900px;
        background-color: #FFF;
    }

    .site p {
        line-height: 2;
    }

    .entry-content,
    .content-width {
        width: auto;
        max-width: 800px;
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
        .site-content {
            margin: 0 24px;
        }
    }
    @media only screen and (max-width:720px){
        .site p{
            padding-left:0px !important;
        }
        .hfeed.site{
            margin-top:0px !important;
        }
    }
    </style>


<div id="page" class="hfeed site" style="margin-top:2em">
    <div id="main" class="wrapper">
        <div id="primary" class="site-content">
            <div id="content" role="main">
                <article id="post-1764" class="post-1764 page type-page status-publish">
                    <div class="entry-content">
                        <p style="padding-left: 80px;padding-top: 24px;">@if(Session::get('lang')== 'ja') <img class="aligncenter wp-image-2000 size-full"
                                src="/img/bandicam-2019-10-13-11-15-45-012.jpg" alt="" width="801" height="450" /> @else <img class="aligncenter wp-image-2000 size-full" src="img/ausculaide_img.jpg" alt="" width="801" height="450" /> @endif</p>
                        <p><span style="font-size: 18pt;"><strong>Ausculaide</strong><strong> is Your Own Personal
                                    Simulator</strong></span></p>
                        <p style="padding-left: 40px;">You have to listen to a lot of sounds to learn auscultation
                            techniques. Even if you learn auscultation through clinical training or simulation training,
                            unfortunately it is difficult to review them at home. Ausculaide makes this possible.</p>
                        <p style="padding-left: 40px;"><span style="font-size: 10pt;">*Auscultation aide =
                                Ausculaide</span></p>
                        <hr />
                        <p><span style="font-size: 24pt;"><strong>Recommendation</strong></span></p>
                        <p><img class="size-full wp-image-1417 alignleft"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-13-10-11-35-170.jpg"
                                alt="" width="185" height="195" /></p>
                        <p>The cardiology patient simulator “K” was developed in 1993.</p>
                        <p>In 1997, in this study its educational effectiveness was </p>
                        <p>reported in CARDIOLOGY, many university medical schools,,</p>
                        <p>schools of nursing, and other educational institutions have been using this simulator for their educational activities throughout the world.</p>
                        <p>&nbsp;</p>
                        <p>The advancement in diagnostic instruments using high technology has been remarkable in the last few decades. However, there is a tendency for many clinicians to become too dependent on these highly sophisticated instruments and to forget the importance of bedside clinical skills. I believe that we have a remarkable inborn sensor to recognize “organ language” and are able to find out, even minor variations in physical findings, such as heart sounds or murmurs.</p>
                        <p>The Ausculaide (auscultation aide) is used as an exciting teaching tool , whenever and wherever the self-learning of auscultation is needed. Please start with normal heart sounds and advance to each case at your own pace. You will be able to recognize various abnormal sounds and murmurs. The “Ausculaide” will be a great auscultation aide for you. </p>
                        <p style="text-align: left; padding-left: 40px;">October 29.2019</p>
                        <p style="padding-left: 40px;"><img class="wp-image-1765 alignnone"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Sign-of-TT.jpg"
                                alt="" width="232" height="63"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Sign-of-TT.jpg 1984w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Sign-of-TT-300x82.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Sign-of-TT-768x209.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Sign-of-TT-1024x279.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Sign-of-TT-624x170.jpg 624w"
                                sizes="(max-width: 232px) 100vw, 232px" /></p>
                        <hr />
                        <p><strong><span style="font-size: 24pt;">Features</span></strong></p>
                        <p style="padding-left: 40px;"><strong>1．You can hear realistic heart sounds (LIFELIKE HEART
                                SOUNDS) just like real patients.</strong></p>
                        <p style="padding-left: 40px;"><strong>2．Depending on the part of the body, you can hear
                                different sounds so you can learn practical skills sooner.</strong></p>
                        <p style="padding-left: 40px;"><strong>3．You will understand each sound deeply through both
                                explanation and the heart chart.<br />
                            </strong></p>
                        <p style="padding-left: 40px;"><strong>4．The 23 cases covered will give you the ability to make
                                a differential diagnosis.</strong></p>
                        <p style="padding-left: 40px;"><strong>5．You can learn in both English and Japanese</strong></p>
                        <p style="padding-left: 40px;"><strong>6．You can feel the thrill by Kikuzo.<br />
                            </strong></p>
                        <p>&nbsp;</p>
                        <p style="padding-left: 40px;"><span style="font-size: 18pt;">Movie</span></p>
                        <p><iframe title="Ausculaide ver.English" width="625" height="352"
                                src="https://www.youtube.com/embed/fLQQmipyAsU?feature=oembed" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe></p>
                        <p style="text-align: center;">The more you listen, the better your auscultation skills will be!
                        </p>
                        <hr />
                        <p><span style="font-size: 18pt;"><strong>Feature 1. You can listen to realistic heart sounds
                                    (LIFELIKE HEART SOUNDS) that are the same as real patients.</strong></span></p>
                        <p>&nbsp;</p>
                        <p style="padding-left: 40px;">With the aim of achieving the highest sound quality of heart
                            sounds, we used our original sound mixing technology to develop a heart sound and heart
                            murmur that has the same frequency as an actual patient. We checked the sound quality and
                            noise of each part of the auscultation with a supervisor, adjusted the balance between
                            parts, and adjusted the sound quality, volume, and timing throughout all of the cases. You
                            can listen to LIFELIKE heart sound and murmur cases on your smartphone. The editorial
                            supervisor is Dr. Tsunekazu Takashina (President of the Japanese Educational Clinical
                            Cardiology Society and the Founder cardiac disease patient simulator ”K”), a cardiologist
                            who has been active in Japan and overseas for many years and is still engaged in clinical
                            bedside physical examinations.</p>
                        <p>&nbsp;</p>
                        <p><img class="aligncenter size-full wp-image-1543"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001.jpg"
                                alt="" width="1280" height="720"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001-300x169.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001-768x432.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001-1024x576.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001-624x351.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px" /><strong><span
                                    style="font-size: 18pt;">Feature 2. Ability to practice with different sounds
                                    depending on the part of body<br />
                                </span></strong></p>
                        <p style="padding-left: 40px;">All cases have four sounds. When you tap the trunk of the screen
                            illustration, the chest piece icon moves to that part and you start listening to heart
                            sounds. You can also hear the crescendo of the sound at the maximum point, as well as the
                            farther away it is, the decrescendo of the sound. It’s just like examining an actual
                            patient.</p>
                        <p><img class="aligncenter wp-image-93 size-full"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383.jpg"
                                alt="" width="996" height="191"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383.jpg 996w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383-300x58.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383-768x147.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383-624x120.jpg 624w"
                                sizes="(max-width: 996px) 100vw, 996px" /></p>
                        <p style="padding-left: 40px;">You can also press the &#8220;A, P, T, M&#8221; buttons at the
                            bottom of the screen to move the icon to the correct position. The following screen shows
                            the screen when you press the A, P, T or M site from the left.</p>
                        <p><img class="aligncenter size-full wp-image-1785"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-12-14-20-684.jpg"
                                alt="" width="819" height="403" /></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p><span style="font-size: 18pt;"><strong>Feature 3. Sound comprehension deepens through
                                    listening to explanations on auscultatory findings and
                                    phonocardiogram</strong></span></p>
                        <p>&nbsp;</p>
                        <p style="padding-left: 40px;"><img class="wp-image-1795 alignleft"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-14-32-10-126.jpg"
                                alt="" width="515" height="288"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-14-32-10-126.jpg 758w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-14-32-10-126-300x168.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-14-32-10-126-624x349.jpg 624w"
                                sizes="(max-width: 515px) 100vw, 515px" /></p>
                        <p style="padding-left: 40px;">The case screen displays a description of auscultation findings
                            and a phonocardiogram. The explanation and the phonocardiogram will further deepen your
                            understanding. We also have the Ausculaide guidebook (sold separately). The guidebook
                            includes descriptions of the heart sound simulation method (Cardiophonetics), which explains
                            the mechanism of heart murmurs and sound symbols.</p>
                        <p>&nbsp;</p>
                        <p><span style="font-size: 18pt;"><strong>Feature 4. Twenty three cases of basic cardiac
                                    differential diagnosis</strong></span></p>
                        <p style="padding-left: 40px;">It carries 23 representative cases of heart sounds and heart
                            murmurs.</p>
                        <p><img class="aligncenter size-full wp-image-1773"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/オースカレイド_LP画像_20191005.jpg"
                                alt="" width="1280" height="720"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/オースカレイド_LP画像_20191005.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/オースカレイド_LP画像_20191005-300x169.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/オースカレイド_LP画像_20191005-768x432.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/オースカレイド_LP画像_20191005-1024x576.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/オースカレイド_LP画像_20191005-624x351.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px" /></p>
                        <p>&nbsp;</p>
                        <p><span style="font-size: 18pt;"><strong>Feature 5. Use the contents in both English and
                                    Japanese.</strong></span></p>
                        <p style="padding-left: 40px;">All cases of Ausculaide are available in English and Japanese. If
                            you purchase a case in the Japanese mode, the English version of the case is still
                            available. Select &#8220;Menu&#8221; → &#8220;Settings&#8221; → &#8220;Change
                            Language&#8221; .</p>
                        <p><img class="aligncenter wp-image-1172 size-full"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860.jpg"
                                alt="" width="1280" height="502"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-300x118.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-768x301.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-1024x402.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-624x245.jpg 624w"
                                sizes="(max-width: 1280px) 100vw, 1280px" /></p>
                        <p>&nbsp;</p>
                        <p><span style="font-size: 18pt;"><strong>Feature 6. Experience thrills</strong></span></p>
                        <p><img class="wp-image-316 aligncenter"
                                src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580.jpg"
                                alt="" width="438" height="357"
                                srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580.jpg 3000w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-300x245.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-768x626.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-1024x835.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-624x509.jpg 624w"
                                sizes="(max-width: 438px) 100vw, 438px" /></p>
                        <p style="padding-left: 40px;">Regenerating a case of an Ausculaide heart murmur, we can feel
                            the thrill of the silicon face on a Kikuzo (Sold separately). It’s just as thrilling as
                            touching a real patient.</p>
                        <p>&nbsp;</p>
                        <hr />
                        <p><span style="font-size: 18pt;"><strong>Free download from the App Store</strong></span></p>
                        <p><a href="https://apps.apple.com/jp/app/ausculaide/id1483003588"><img
                                    class="aligncenter wp-image-1780"
                                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-1-e1571883241176.png"
                                    alt="" width="195" height="65"
                                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-1-e1571883241176.png 839w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-1-e1571883241176-300x100.png 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-1-e1571883241176-768x257.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-1-e1571883241176-624x209.png 624w"
                                    sizes="(max-width: 195px) 100vw, 195px" /></a></p>
                        <p>&nbsp;</p>
                        <p><a href="https://play.google.com/store/apps/details?id=test.ausculaide.ios.production"><img
                                    class="aligncenter wp-image-1779"
                                    src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-e1571883209914.png"
                                    alt="" width="195" height="65"
                                    srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-e1571883209914.png 839w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-e1571883209914-300x100.png 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-e1571883209914-768x257.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/android-app-store-app-store-and-android-icons-11553546864dl6gbnzyt2-e1571883209914-624x209.png 624w"
                                    sizes="(max-width: 195px) 100vw, 195px" /></a></p>
                        <p style="text-align: center;">Requires iOS 9.0 or later, Android5.0 or later</p>                      
                        <p>&nbsp;</p>
                    </div><!-- .entry-content -->
                </article><!-- #post -->

            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main .wrapper -->
    
</div><!-- #page -->
@endsection
