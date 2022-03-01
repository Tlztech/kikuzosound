@extends('layouts.app')

@section('title', 'Ausculaide')

<html class="page" lang="ja" >
<head>
	<style type="text/css">
		body {
			background-color:#22aab6;
				}
		.site {
			font-size:16px;
			font-family:"Open Sans",Helvetica,Arial,sans-serif;
			width:auto;
			max-width:900px;
			background-color:#FFF;
			margin-top:15px !important;
				}
		.site p {
			line-height:2;
			font-size:unset;
		}
		.entry-content,
		.content-width {
			width:auto;
			max-width:800px;
			margin: 0 auto;
		}
		.bg-youtube-content {
			box-sizing: border-box;
			margin: 0 auto;
			max-width:800px;
		}
		.site {
			box-shadow: 0 0 10px rgba( 0, 0, 0,0.25);
			-moz-box-shadow: 0 0 10px rgba( 0, 0, 0,0.25);
			-webkit-box-shadow: 0 0 10px rgba( 0, 0, 0,0.25);
			margin: auto;
		}
		.wp-image-1543,
		.wp-image-93,
		.wp-image-1422,
		.wp-image-1172,
		.wp-image-1773,
		.wp-image-1302,
		.wp-image-1064 {
			height: auto;
			width: 100%;
		}
		.wp-image-1417 {
			float: left;
		}
		@media(max-width:740px){
			.aligncenter.wp-image-94{
				width: 100%;
				height: auto;
			}
			p span strong{
				font-size:14px !important;
			}
			p.sp-subtitle{
				padding-left:0px !important;
			}
			p.btn-custom{
				text-align:center;
			}
			p.Ausculaide-topimage{
				padding-left:0px !important;
			}
		}
	</style>
</head>

<!-- ＝＝＝＝＝＝＝　headここまで　＝＝＝＝＝＝＝ -->


<!-- ＝＝＝＝＝＝＝　ここからbody　＝＝＝＝＝＝＝ -->

<body class="page-template-default page page-id-284 custom-background-empty custom-font-enabled single-author">
@section('breadcrumb')
{!! Breadcrumbs::render('aa01') !!}
@endsection

@section('content')

<?php
    $locale = Session::get('lang');
    App::setLocale($locale);
?>
<div style="width:100%;height:30px;position:fixed;z-index:10;" onMouseOver="dispWin();"></div>
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
	</header><!-- #masthead -->

	<div id="main" class="wrapper aa01">
		<div id="primary" class="site-content">

			<div id="content" role="main">
			<article id="post-284" class="post-284 page type-page status-publish">
				<header class="entry-header">
					<!-- <h1 class="entry-title">ausculaide01</h1> -->
				</header>

				<div class="entry-content">
					<!-- <p>@lang('aa01.entry_cont')</p> -->
					<p style="padding-left: 80px;padding-top: 24px;" class="Ausculaide-topimage">
					@lang('aa01.header_img')
					</p>
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.p1')</strong></span></p>
					<p style="padding-left: 40px;">@lang('aa01.p3')</p>
					<p style="padding-left: 40px;"><span style="font-size: 10pt;">@lang('aa01.aus_aide')</span></p>
					<hr />
					<p class=""><span style="font-size: 24pt;"><strong>@lang('aa01.p4')</strong></span></p>
					<p class=""><img class="size-full wp-image-1417 alignleft" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-13-10-11-35-170.jpg" alt="" width="185" height="195" /></p>
					<p>@lang('aa01.p5')</p>
					<p>@lang('aa01.p6')</p>
					<p>@lang('aa01.p7')</p>
					<p style="text-align: left; padding-left: 40px;">@lang('aa01.p8')</p>
					<p style="padding-left: 40px;">
					@lang('aa01.signature')
					</p>
					<hr />
					<p><strong><span style="font-size: 24pt;">@lang('aa01.p9')</span></strong></p>
					<p>
					<ol class="features-ol">
						<li><strong>@lang('aa01.p10')</strong></li>
						<li><strong>@lang('aa01.p11')</strong></li>
						<li><strong>@lang('aa01.p12')</strong></li>
						<li><strong>@lang('aa01.p13')</strong></li>
						<li><strong>@lang('aa01.p14')</strong></li>
						<li><strong>@lang('aa01.p15')</strong></li>
					</ol>
					</p>
					<!-- <p style="padding-left: 40px;"><strong>@lang('aa01.p16')</strong></p> -->
					<br/>
					<p>&nbsp;</p>
                        <p style="padding-left: 40px;"><span style="font-size: 18pt;">@lang('aa01.movie')</span></p>
                        <p class="text-center">
							@lang('aa01.movie_video')
						</p>
					<p style="text-align: center;">@lang('aa01.movie_note')</p>
                    <hr />
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.pspan1')</strong></span></p>
					<p>&nbsp;</p>
					<p style="padding-left: 40px;">@lang('aa01.p17')</p>
					<p>&nbsp;</p>
					<p><img class="aligncenter size-full wp-image-1543" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/soundmixing0001.jpg" alt=""/><span style="font-size: 18pt;"><strong>@lang('aa01.p18')</strong></span></p>
					<p style="padding-left: 40px;">@lang('aa01.p19')</p>
					<p><img class="aligncenter wp-image-93 size-full" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383.jpg" alt="" width="996" height="191" srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383.jpg 996w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383-300x58.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383-768x147.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/3-e1571022575383-624x120.jpg 624w" sizes="(max-width: 996px) 100vw, 996px" /></p>
					<p style="padding-left: 40px;">@lang('aa01.p20')</p>
					<p><img class="aligncenter size-full wp-image-1785" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-12-14-20-684.jpg" alt="" width="819" height="403" srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-12-14-20-684.jpg 819w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-12-14-20-684-300x148.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-12-14-20-684-768x378.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/bandicam-2019-10-24-12-14-20-684-624x307.jpg 624w" sizes="(max-width: 819px) 100vw, 819px" /></p>
					<p>&nbsp;</p>
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.p21')</strong></span></p>
					<p>
					@lang('aa01.feature3_img')

					</p><br/>
					<p style="padding-left: 40px;">@lang('aa01.p22')</p>
					<p>&nbsp;</p>
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.p23')</strong></span></p>
					<p style="padding-left: 40px;">@lang('aa01.p24')</p>
					<p>
					@lang('aa01.feature4_img')
					</p>
					<p>&nbsp;</p>
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.p25')</strong></span></p>
					<p style="padding-left: 40px;">@lang('aa01.p26')</p>
					<p><img class="aligncenter wp-image-1172 size-full" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860.jpg" alt="" width="1280" height="502" srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860.jpg 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-300x118.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-768x301.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-1024x402.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/E4.言語切替画面-e1570934734860-624x245.jpg 624w" sizes="(max-width: 1280px) 100vw, 1280px" /></p>
					<p>&nbsp;</p>
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.p27')</strong></span></p>
					<p class="text-center"><img class="wp-image-316 aligncenter" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580.jpg" alt="" width="438" height="357" srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580.jpg 3000w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-300x245.jpg 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-768x626.jpg 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-1024x835.jpg 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/08/kkz6-e1571080982580-624x509.jpg 624w" sizes="(max-width: 438px) 100vw, 438px" /></p>
					<p style="padding-left: 40px;">@lang('aa01.p28')</p>
					<p>&nbsp;</p>
					<!-- <p><span style="font-size: 18pt;"><strong>@lang('aa01.p29')</strong></span></p>
					<p style="padding-left: 40px;">@lang('aa01.p30-1')<strong>@lang('aa01.p30-2')</strong>@lang('aa01.p30-3')</p>
					<p style="padding-left: 40px;">@lang('aa01.p31-1')<span style="font-size: 14pt;"><a href="https://telemedica.sakura.ne.jp/3sp-lp/ausculaide04/" target="_blank" rel="noopener noreferrer"><strong>@lang('aa01.p31-2')</strong></a></span>。</p>
					<p><img class="aligncenter size-full wp-image-1302" src="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Cond12-2.png" alt="" width="1280" height="720" srcset="https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Cond12-2.png 1280w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Cond12-2-300x169.png 300w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Cond12-2-768x432.png 768w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Cond12-2-1024x576.png 1024w, https://telemedica.sakura.ne.jp/3sp-lp/wp-content/uploads/2019/10/Cond12-2-624x351.png 624w" sizes="(max-width: 1280px) 100vw, 1280px" /></p> -->
					<hr />
					<p><span style="font-size: 18pt;"><strong>@lang('aa01.p32')</strong></span></p>
			
						<p class="text-center">
							<a href="https://apps.apple.com/jp/app/ausculaide/id1483003588">
								@lang('aa01.app_store')
							</a>
						</p>
						<p>&nbsp;</p>
                        <p class="text-center">
							<a href="https://play.google.com/store/apps/details?id=test.ausculaide.ios.production">
								@lang('aa01.play_store')
							</a>
						</p>
				
					<p style="text-align: center;">@lang('aa01.p33')</p>
					<br>
				</div>
			</article>		
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main .wrapper -->

</div><!-- #page -->
@endsection

</body>
</html>
