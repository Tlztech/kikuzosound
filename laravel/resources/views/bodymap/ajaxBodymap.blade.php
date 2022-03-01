    <!-- bodymapJS/CSS -->
<!-- <script type="text/javascript" src="{{asset('body-js/lib/howler.core.js?v=').mt_rand()}}"></script> -->
<script type="text/javascript" src="{{asset('body-js/data.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{asset('body-js/config.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{asset('body-js/common.js')}}" charset="UTF-8"></script>
<script type="text/javascript" src="{{asset('body-js/points_config.js')}}" charset="UTF-8"></script>
<script src="{{asset('body-contents/heart/scripts/contents_config.js')}}"></script>
<script src="{{asset('body-js/ajax_apps.js?v=').mt_rand()}}"></script>

<link rel="stylesheet" type="text/css" href="{{asset('body-style/style.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('body-style/bodymap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('body-style/style_responsive.css')}}"/>
<div id="ausculaide_app" class="ausculaide_container" _ontouchstart="alert(''); return false;"
	    _ontouchmove="event.preventDefault();" style="" data-data="{{$sound}}" data-case="{{$case}}">
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
