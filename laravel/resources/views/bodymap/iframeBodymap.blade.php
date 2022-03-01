
<!-- bodymapJS/CSS -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('test-js/lib/howler.core.js')}}"></script>
<script type="text/javascript" src="{{asset('test-js/data.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{asset('test-js/config.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{asset('test-js/common.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{asset('test-contents/heart/scripts/contents_config.js')}}" ></script>
<script type="text/javascript" src="{{asset('test-contents/heart/scripts/section1_script_list.js')}}" ></script>
<script type="text/javascript" src="{{asset('test-contents/heart/scripts/section2_script_list.js')}}" ></script>
<script type="text/javascript" >
 let CASE_TYPE = '<?php echo $case; ?>'
</script>
<script src="{{asset('test-js/app.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('body-style/style.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('body-style/bodymap.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('body-style/style_responsive.css')}}"/>
	<div id="ausculaide_app" class="ausculaide_container" _ontouchstart="alert(''); return false;"
	    _ontouchmove="event.preventDefault();" style="">
		<div id="zoom_adjustment" data-width="0" data-height="0" style="display:none"> </div>
	    <div class="main_wrapper ausculaide" ontouchmove="event.preventDefault()" ontouchstart="">
	        <div class="main ausculaide">
	            <!-- 左エリア -->
	            <div class="left-pane" style="display:none">
	                <div class="balloon">
	                    <div class="explanation">
	                        <div class="explanation-inner">
	                            <div class="scroll-view" ontouchmove="">ここに説明テキストが入ります</div>
	                            <div id="tmp_text" style="display:none"></div>
	                        </div>
	                    </div>
	                    <div class="list-area">
	                        <div class="list-area-inner">
	                            <ul id="main-menu">
	                                <li><a href="javascript:void(0);">ここにメインメニューが入ります</a></li>
	                            </ul>
	                            <button id="btn-result" onclick="openResult();"></button>
	                        </div>
	                    </div>
	                </div>
	                <div class="left-panel-content panel-content" ontouchmove="enablePinch()" style="display: none;">
	                    <div class="viewable_image_area">
	                        <img class="viewable_image" src="">
	                        <div style="width:40px;height:40px;background-color: red"></div>
	                    </div>
	                </div>
	                <div class="left-video-area" style="display: none;"></div>
	            </div>

	            <!-- Bodymap -->
	            <div id="">
	                <div class="bodymap-frame type-cardio" ontouchmove="">
	                    <div class="bodymap_audio_wrapper" style="display:none;"></div>
	                    <div id="bodymap-cardiac" class="bodymap">
	                        	<div id="body_image_container" class="bodymap-front" _style="display:none">
								<div id="cardiacsoundA" class="cardiacsound A cardiacsoundA sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundP" class="cardiacsound P cardiacsoundP sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundT" class="cardiacsound T cardiacsoundT sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundM" class="cardiacsound M cardiacsoundM sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>

								<div id="cardiacsoundH1" class="cardiacsound H1 cardiacsoundH1 sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundH2" class="cardiacsound H2 cardiacsoundH2 sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundH3" class="cardiacsound H3 cardiacsoundH3 sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundH4" class="cardiacsound H4 cardiacsoundH4 sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>

								<div id="cardiacsoundPA" class="cardiacsound PA cardiacsoundPA sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundPP" class="cardiacsound PP cardiacsoundPP sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundPT" class="cardiacsound PT cardiacsoundPT sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundPM" class="cardiacsound PM cardiacsoundPM sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>

								<div id="cardiacsoundTR1" class="cardiacsound TR1 cardiacsoundTR1 sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundTR2" class="cardiacsound TR2 cardiacsoundTR2 sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>

								<div id="cardiacsoundBR1" class="cardiacsound BR1 cardiacsoundBR1 sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundBR2" class="cardiacsound BR2 cardiacsoundBR2 sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundBR3" class="cardiacsound BR3 cardiacsoundBR3 sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundBR4" class="cardiacsound BR4 cardiacsoundBR4 sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>

								<div id="cardiacsoundVE1" class="cardiacsound VE1 cardiacsoundVE1 sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE2" class="cardiacsound VE2 cardiacsoundVE2 sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE3" class="cardiacsound VE3 cardiacsoundVE3 sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE4" class="cardiacsound VE4 cardiacsoundVE4 sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE5" class="cardiacsound VE5 cardiacsoundVE5 sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE6" class="cardiacsound VE6 cardiacsoundVE6 sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE7" class="cardiacsound VE7 cardiacsoundVE7 sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE8" class="cardiacsound VE8 cardiacsoundVE8 sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE9" class="cardiacsound VE9 cardiacsoundVE9 sound-target"><div class="label">大動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE10" class="cardiacsound VE10 cardiacsoundVE10 sound-target"><div class="label">肺動脈弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE11" class="cardiacsound VE11 cardiacsoundVE11 sound-target"><div class="label">三尖弁領域</div><div class="extended_area"></div></div>
								<div id="cardiacsoundVE12" class="cardiacsound VE12 cardiacsoundVE12 sound-target"><div class="label">僧帽弁領域</div><div class="extended_area"></div></div>

	                        </div>
	                    </div>
	                    <div id="bodymap-bowel" class="bodymap" style="display: none;">
	                        <div class="bodymap-front" _style="display:none">
	                            <div id="bowelsounds" class="bowel bowelsounds sound-target" style="opacity: 0;"></div>
	                        </div>
	                    </div>
	                    <div id="bodymap-question" style="display: none;"></div>
	                    <button id="btn-bodymap-close" style="display:none;" class="center"></button>
	                    <button id="btn-bodymap-ok" style="display:none;"></button>
	                    <div class="message"></div>
	                    <div id="stethoscope">
	                        <div class="label" style="display: none;"></div>
	                    </div>
	                    <div class="loading-wrapper" style="display: none;">
	                        <div class="loading">
	                            <div class="sk-fading-circle">
	                                <div class="sk-circle1 sk-circle"></div>
	                                <div class="sk-circle2 sk-circle"></div>
	                                <div class="sk-circle3 sk-circle"></div>
	                                <div class="sk-circle4 sk-circle"></div>
	                                <div class="sk-circle5 sk-circle"></div>
	                                <div class="sk-circle6 sk-circle"></div>
	                                <div class="sk-circle7 sk-circle"></div>
	                                <div class="sk-circle8 sk-circle"></div>
	                                <div class="sk-circle9 sk-circle"></div>
	                                <div class="sk-circle10 sk-circle"></div>
	                                <div class="sk-circle11 sk-circle"></div>
	                                <div class="sk-circle12 sk-circle"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>

	            <!-- bodymap-farme -->

	            <!-- 右エリア -->
	            <div class="right-area" style="display:none; height:auto;">
	                <div class="dual-panel-wrapper" _style="display:none;">
	                    <div class="panel">
	                        <div class="panel-header">
	                            <div class="inner">学習タイトル</div>
	                        </div>
	                        <div class="panel-content-wrapper">
	                            <div class="panel-content"></div>
	                            <div class="panel-sub-content"></div>
	                        </div>
	                    </div>
	                </div>
	            </div> <!-- /right-area -->
			</div>
		</div>
		<div class="footer ausculaide" class="ausculaide" ontouchmove="event.preventDefault()">
			<div>
				<button id="btn-case-top"></button>
				<div class="points">
					<button id="btn-point-A" class="btn-points" disabled>A</button>
					<button id="btn-point-P" class="btn-points" disabled>P</button>
					<button id="btn-point-T" class="btn-points" disabled>T</button>
					<button id="btn-point-M" class="btn-points" disabled>M</button>
				</div>
			</div>
		</div>
	</div>
