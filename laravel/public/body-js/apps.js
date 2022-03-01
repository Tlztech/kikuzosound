var mouseDown = false;
var startX, startY;
var lastX, lastY;

var bodymap_targets = {
//	"" : 
};

var  bodymap_audios = [];//new Array(){};
var current_audio_index = 0;

var audio_started = false;
var audio_unlocked = false;
var audio = null;
//var playng_audios = [];
var currernt_audio_file = "";
var current_audio_calss = "";
var timer_blink_message = null;
var message_timer = null;

var text_audio = null;
var text_audio_off = false;

var current_case = null;
var current_node = null;
var user_attr = 1;
var user_attr_sub = "";
var results = {};
var memos = {};
//var post_data = null;
var case_start_time = -1;

var auto_forward_timer = null

var audio_count = 0;
var loaded_audio_count = 0;
let offline_mode = false;


document.addEventListener("visibilitychange", function(e) {
	//alert();
	//if (audio) {
        //audio.stop();
        if (document.hidden) {
            for (var key in bodymap_audios[0]) {
                var audio = bodymap_audios[0][key]; 
                audio.pause();
            }
        } else {
            for (var key in bodymap_audios[0]) {
                var audio = bodymap_audios[0][key]; 
                //if (audio.playing())
                var seek = audio.seek();
                if (seek != 0) {
                    audio.play();
                }
            }
        }
//	}
});

jQuery.cachedScript = function( url, options ) {
 
	// Allow user to set any option except for dataType, cache, and url
	options = $.extend( options || {}, {
	  dataType: "script",
	  cache: true,
	  url: url
	});
   
	// Use $.ajax() since it is more flexible than $.getScript
	// Return the jqXHR object so we can chain callbacks
	return jQuery.ajax( options );
};

$(document).ready(function(){
	$( ".audio_slider" ).slider({
        slide:
        function( event, ui ) {
			var stethoId = localStorage.getItem("sthetho_id");
			var playRate = getSoundSpeed(ui.value);
			if (stethoId) {
				localStorage.setItem("slider_" + stethoId, ui.value);
				localStorage.setItem("playrate_" + stethoId, playRate);
			}
			setSoundRate(playRate);
        },
        change: function( event, ui ) {
			var stethoId = localStorage.getItem("sthetho_id");
			var playRate = getSoundSpeed(ui.value);
			if (stethoId) {
				localStorage.setItem("slider_" + stethoId, ui.value);
				localStorage.setItem("playrate_" + stethoId, playRate);
			}
			setSoundRate(playRate);
        }
    });
	$( "#stethoscope" ).draggable({
		containment: ".bodymap-frame",
		drag: function(event,scope) {
			var x = scope.position.left;
			var y = scope.position.top;
			//console.log(x,y,scope)
			targetPlay(x, y);
		},
	 });


	var id = "body-"+localStorage.getItem("sthetho_id");
	var parent_iframe = $(".bodyFrame");
	if(parent_iframe.hasClass("aus_fullscreen_img")){
		$("#btn-case-top").addClass("__fullscreen");
	}
	// set playrate for initialization
	var stethoId = localStorage.getItem("sthetho_id");
	var soundRate = localStorage.getItem("playrate_" + stethoId);
	setSoundRate(soundRate ? soundRate : 1.0);

    $('#btn-case-top').bind("click",
				function() {
					$(".btn-points").each(function( index ) {
						$( this ).removeClass("btn-point-active");
					});
					OpenBodyMap();
					var stethoId = localStorage.getItem("sthetho_id");
					var soundRate = localStorage.getItem("playrate_" + stethoId);
					setSoundRate(soundRate ? soundRate : 1.0);
				}
	);

	$('.playZ').bind("click",
		function() {
			$( '#recommended_ausculaide' ).attr( 'src', function ( i, val ) { return val; });
		}
	);

	$('#btn-point-A').bind("click",
		function() {
			left_x = parseInt(CONFIGURATION_SETTINGS.a.x,10);
			top_y = parseInt(CONFIGURATION_SETTINGS.a.y,10);
			activePoint = "A";
			//console.log(left_x,top_y)
			//console.log(bodymap_audios[0]);
			if ((bodymap_audios[0]["cardiacsoundA"] || bodymap_audios[0]["cardiacsoundPA"]) && left_x && top_y) {
				playToPoint(left_x,top_y);
				setActivePoint(this);
			}
		}
	);

	$('#btn-point-P').bind("click",
		function() {
			left_x = parseInt(CONFIGURATION_SETTINGS.p.x,10);
			top_y = parseInt(CONFIGURATION_SETTINGS.p.y,10);
			activePoint = "P";
			//console.log(bodymap_audios[0]);
			if ((bodymap_audios[0]["cardiacsoundP"] || bodymap_audios[0]["cardiacsoundPP"]) && left_x && top_y) {
				playToPoint(left_x,top_y);
				setActivePoint(this);
			}
		}
	);

	$('#btn-point-T').bind("click",
		function() {
			left_x = parseInt(CONFIGURATION_SETTINGS.t.x,10);
			top_y = parseInt(CONFIGURATION_SETTINGS.t.y,10);
			activePoint = "T";
			if ((bodymap_audios[0]["cardiacsoundT"] || bodymap_audios[0]["cardiacsoundPT"]) && left_x && top_y) {
				playToPoint(left_x,top_y);
				setActivePoint(this);
			}
		}
	);

	$('#btn-point-M').bind("click",
		function() {
			left_x = parseInt(CONFIGURATION_SETTINGS.m.x,10);
			top_y = parseInt(CONFIGURATION_SETTINGS.m.y,10);
			activePoint = "M";
			if ((bodymap_audios[0]["cardiacsoundM"] || bodymap_audios[0]["cardiacsoundPM"]) && left_x && top_y) {
				playToPoint(left_x,top_y);
				setActivePoint(this);
			}
		}
	);

	$(window).unload(function(){
		stopAllSound();
	});
	$('.bodymap-frame .loading-wrapper')
	.on('click mousedown touchstart mousemove touchmove mouseup touchend',
		function(){
			return false;
		}
	);

	$('.bodymap-frame').bind('click touchstart', function(event) {
		//ios unlock audio by play empty sound
		if (audio_unlocked) return;
		audio_unlocked = true;

		var audio_unlocker = new Audio(SITEURL+"/audio/stetho_sounds/no_sound.mp3");
		audio_unlocker.play();
		audio_unlocker.loop=false;
		//audio_unlocker.pause();
		for (var key in bodymap_audios[0]) {
			var audio = bodymap_audios[0][key]; 
			if(audio._playLock){
				audio.play();	
				audio.pause();	
			}
			//console.log("ios audio is lock -",key,"-",audio._playLock);
		}
	});

    	// bodymap
	{
		$('.bodymap-frame').bind('click', function(event){
			var scope = $('#stethoscope');
				var pageX, pageY;

				pageX = event.pageX;
				pageY = event.pageY;

				//console.log("clicked111",pageX,pageY)
		
				// var pos = getAnimationZoomPoints({left: pageX, top: pageY})
				// pageX = pos.left;
				// pageY = pos.top;
				
				mouseDown = true;

				var dx = pageX - $('.bodymap-frame').offset().left - scope.width()/2;;//- lastX;
				var dy = pageY - $('.bodymap-frame').offset().top - scope.height()/2;;//- lastY;
				var currPos = actualPosition(scope);
				var x = dx;
				var y = dy;

				//scope.css({left: x, top: y});

                
//                var distance = Math.sqrt( Math.pow(lastX-pageX, 2) + Math.pow(lastY-pageY, 2) );
				var distance = Math.sqrt( Math.pow(currPos.left-x, 2) + Math.pow(currPos.top-y, 2) );
                
                
                var duration  = distance / 1 / STETHOSCOPE_SPEED;
                var easing = "linear";
                var orientation = "";
                if (window.matchMedia("(orientation: portrait)").matches) {
                	orientation = "portrait";
				}else{
                	orientation = "landscape";
				}

				//console.log("clicked",x,y)
				if(isDragArea(x,y)){
					scope.animate({left: x, top: y}, duration, easing,function(){
						targetPlay(x, y);
					});
				}

				lastX = pageX;
                lastY = pageY;

                return true;
		});
	}

});
function loadAus(id,is_quiz){
	if( $('#data-offline').length ){
		offline_mode = true;
	}else{
		offline_mode = false;
	}
    var data = $("#aus_"+id).data('result');
    var case_type = $("#body-"+id).data('case');
    if(is_quiz){
        data = $('#aus-img_wrapper_'+id).data("result");
    }
    //console.log(data,case_type)
    setAusculaideData(data,case_type);
    setDataPoints(data,case_type);
    //setAusCase();
    //console.log("sound",AUS_SOUND);
    $("#ausculaide_app").removeClass("aus_loaded");
	var ver = "0."+AUSCULAIDE_DATA.id;
	var script_file = "/body-js/lib/howler.core.js?v=" +ver;
	$.cachedScript(script_file).done(function( script, textStatus ) {
		generateCache(script_file);
		OpenBodyMap(); // exec when on load;
	});
}
function generateCache(script_file){
	var ver = $("#env_asset_url").data("ver");
	var CACHE_NAME = $("#ausculaide_app").data("cache_version");
	var assetsCache = [];
	var urlsToCache = [
	AUSCULAIDE_DATA.body_image+"?v="+ver,
	AUSCULAIDE_DATA.body_image_back+"?v="+ver,
	"/img/no_image.png?v="+ver,
	script_file
	];
	if(offline_mode){
		var version = $("#asset_version").data("version");
		assetsCache = [
			'/css/common.css?v='+version,
			'/css/style.css?v='+version,
			'/css/style2.css?v='+version,
			'/css/respons.css?v='+version,
			'/css/bodymap.css?v='+version,

			'/body-style/style.css?v='+version,
			'/body-style/bodymap.css?v='+version,
			'/body-style/style_responsive.css?v='+version,

			'/js/common.js?v='+version,
			'/body-js/data.js?v='+version,
			'/body-js/config.js?v='+version,
			'/body-js/common.js?v='+version,
			'/body-js/points_config.js?v='+version,
			'/body-contents/heart/scripts/contents_config.js?v='+version,
			'/body-js/apps.js?v='+version,
		]
		urlsToCache.push(window.location.href)
	}
	caches.open(CACHE_NAME)
	.then(function(cache) {
		//console.log('Opened cache');
		cache.addAll(urlsToCache);
		cache.addAll(ALL_SOUND);
		cache.addAll(assetsCache);
		return true;
	});
}

async function OpenBodyMap() {
    stopAllSound();
	audio_unlocked = false;

	//set body image
	var body_image = localStorage.getItem("body") == "front" ? AUSCULAIDE_DATA.body_image : AUSCULAIDE_DATA.body_image_back;
	let body_src= body_image ? SITEURL + body_image: SITEURL + "/img/no_image.png";
	var ver = $("#env_asset_url").data("ver");
	body_src = body_src+"?v="+ver;
    
    $('#stethoscope').css({left:"0px", top: "0px"});
    $('#bodymap-cardiac .bodymap-front').css('background-image', 'url('+ body_src + ')');
    $('.bodymap-front').show();
	$('.bodymap-frame').fadeIn(3);
	$('.bodymap-frame .loading-wrapper').show();
    $('#bodymap-cardiac').show();
	if( $('#offline_switch_'+AUSCULAIDE_DATA.id).length ){
		document.getElementById('offline_switch_'+AUSCULAIDE_DATA.id).switchButton('disable');
	}

		//var audio_base = './body-contents/' + contents_type + '/audio/';
		//var audio_base = SITEURL+'/audio/stetho_sounds/';

			bodymap_targets = {};
			bodymap_audios[0] = {};
            bodymap_audios[1] = {};
			//if (type!="respiratory")
			loaded_audio_count = 0;
			audio_count = Object.keys(AUS_SOUND).length;
			var err_count = 0;
			bodymap_targets = AUS_SOUND;
			var loop_cnt = 0;
			for(key in AUS_SOUND) {
				//console.log(key + ":" + param.sound);
				loop_cnt++;
                var path = AUS_SOUND[key];
				
				//var path_aiff = path.replace(".mp3", ".aif");
				var use_html5Audio = false;
				var ua = window.navigator.userAgent.toLowerCase();
				if (ua.indexOf('android')  != -1) {
					use_html5Audio = true;
				}
				
					var sound =  new Howl({
						xhrWithCredentials : false,
						src: [path],
						autoplay: false,
						html5: use_html5Audio,
						loop: false,
						volume: 0.0,
						autoUnlock:true,
						onload: function(){
							// when successful
							//myResolve();
							//console.log('Loaded! ' + this._src);
							loaded_audio_count++;
							if ((err_count+loaded_audio_count)==audio_count){
								//console.log("CASE_TYPE",CASE_TYPE)
								$("#ausculaide_app").addClass("aus_loaded");
								$('.bodymap-frame .loading-wrapper').hide();
								//console.log("Howler",Howler)
								//console.log("targets",bodymap_targets)
								if( $('#offline_switch_'+AUSCULAIDE_DATA.id).length ){
									document.getElementById('offline_switch_'+AUSCULAIDE_DATA.id).switchButton('enable');
								}
							}
							
						},
						onloaderror: function(id,err) {
							//myResolve();
							err_count++;
							//console.log('Load error!!!!!!!!!!!!',id,err);
							if ((err_count+loaded_audio_count)==audio_count){
								$('.bodymap-frame .loading-wrapper').hide();
								if( $('#offline_switch_'+AUSCULAIDE_DATA.id).length ){
									document.getElementById('offline_switch_'+AUSCULAIDE_DATA.id).switchButton('enable');
								}
							}
						},							
						onplay: function(){
						//console.log('Play!');
						},
						onplayerror: function(){
							//console.log('Load error!!!!!!!!!!!!',id,err);
							sound.once('unlock', function() {
								sound.play();
								sound.pause();
							});
						},
						onend: function() {
							//console.log('Finished!');
						}
						});
						sound.play();
						sound.pause();
						bodymap_audios[0][key] = sound;

						// if(audio_count==loop_cnt){
						// 	$('.bodymap-frame .loading-wrapper').hide();
						// }
	
				//_audio = document.getElementById(key);

				//_audio.preload = true;
				

            }
			if(audio_count<1){
				$('.bodymap-frame .loading-wrapper').hide();
			}
            
}

function targetPlay(x, y) {
	var stethoId = localStorage.getItem("sthetho_id");
	var soundRate = localStorage.getItem("playrate_" + stethoId);
	setSoundRate(soundRate ? soundRate : 1.0);
    var scope = $('#stethoscope');
    var ss_cx = x + scope.width()/2;
    var ss_cy = y + scope.width()/2;

	var hits = [];
	var has_hits = false;
	var core_hit = false;
	//console.log("audios",bodymap_audios[current_audio_index],Howler._howls)
	for (var target in bodymap_targets) {

		var audio = bodymap_audios[current_audio_index][target];

		var scope_radius = $('#stethoscope').width() / 2;
		var point = getAnimationZoomPoints(STETHO_POSITIONS[target]);
		var target_radius = point.r;
		var target_cx = Number(point.cx) + scope_radius;
		var target_cy = Number(point.cy) + scope_radius;
		var distance = Math.sqrt(Math.pow(target_cx - ss_cx, 2) + Math.pow(target_cy - ss_cy, 2));
		var newVolume = 0;
		if (distance <= target_radius ) {
			core_hit = true;
			has_hits = true;
			var volume = (target_radius-distance)/target_radius;
			newVolume = Math.min(volume, 1.0);
			//console.log(target,newVolume,distance)
		}else{
			newVolume = 0;
		}

		var target_hit = {
			id: target,
			core_hit: core_hit,
			audio:audio,
			distance:distance,
			volume: newVolume,
			type:point.type,
		};

		hits.push(target_hit);


	};
	hits.sort(function(a,b){
		if( a.distance < b.distance ) return -1;
		if( a.distance > b.distance ) return 1;
		return 0;
	});

	if (localStorage.getItem("type") == 2) { // only change color if type is heart
		if (hits[0].volume > 0) { // set aptm active button
			switch (hits[0].id) {
				case "cardiacsoundA":
				case "cardiacsoundPA":
					setActivePoint("#btn-point-A");
					break;
				case "cardiacsoundP":
				case "cardiacsoundPP":
					setActivePoint("#btn-point-P");
					break;
				case "cardiacsoundT":
				case "cardiacsoundPT":
					setActivePoint("#btn-point-T");
					break;
				case "cardiacsoundM":
				case "cardiacsoundPM":
					setActivePoint("#btn-point-M");
					break;
				default:
					break;
			}
		}
	}

    if (has_hits) {
        playAllSound();
    } else {
		setActivePoint(); // clear aptm active button
	}
	//console.log("hits",hits,bodymap_audios[current_audio_index])
	var lung_hit = null;
	var heart_hit = null;
    for (var i=0; i < hits.length; i++) {
        var hit = hits[i];
		if(CASE_TYPE=="heart_lungs" || CASE_TYPE=="heart_pulse_lungs"){
			var heart_type=(CASE_TYPE=="heart_pulse_lungs")?"pulse":"heart";
			var hit = hits[i];
			if ((hit.type===heart_type && !heart_hit) || (hit.type==="lung" && !lung_hit)) {//if not yet hit
				//fade audio when has volume
				if(hit.volume>0){
					hit.audio.fade(hit.audio.volume(), hit.volume, 30/*ms*/);
				}else{//set volume 0
					hit.audio.volume(0);
				}
				
				if(hit.type==="heart" || hit.type==="pulse"){
					heart_hit = hit.id;
				}else{
					lung_hit = hit.id;
				}
			} else {
				//hit.audio.fade(hit.audio.volume(), 0, 10/*ms*/);
				hit.audio.volume(0);
			}
		}else{
			if (i==0) {
				//fade audio when has volume
				if(hit.volume>0){
					hit.audio.fade(hit.audio.volume(), hit.volume, 30/*ms*/);
				}else{//set volume 0
					hit.audio.volume(0);
				}
				
			} else {
				//hit.audio.fade(hit.audio.volume(), 0, 10/*ms*/);
				hit.audio.volume(0);
			}
		}
	}
	

}

function playAllSound(volume) {
    //console.log("playAllSound");
    if (audio_started) return;
    audio_started = true;

	for (var key in bodymap_audios[0]) {
		var audio = bodymap_audios[0][key]; 
        audio.volume(0);
		audio.seek(0);
		audio.play();		
	}
}

function stopAllSound() {
	audio_started = false;
	/*
    for (var i=0; i < playng_audios.length; i++) {
        playng_audios[i].pause();
	}
	*/

    for (var key in bodymap_audios[0]) {
        var audio = bodymap_audios[0][key]; 
		audio.stop();
		if(!offline_mode){
			audio.unload();
		}

    }  
	for (var key in Howler._howls) {
        var audio = Howler._howls[key]; 
		audio.stop();
		if(!offline_mode){
			audio.unload();
		}
    }    
    bodymap_audios = [];
}

function setActivePoint(e) {
	$(".btn-points").each(function( index ) {
		$( this ).removeClass("btn-point-active");
	});
	$(e).addClass("btn-point-active");
}

function playToPoint(x,y){
	var adjust_point = getAnimationZoomPoints({cx:x, cy:y, r:0, type:""})
	x = adjust_point.cx;
	y= adjust_point.cy;
	var scope = $('#stethoscope');
	var easing = "linear";
	var duration = 500;

	var currPos = actualPosition(scope);
	
	var distance = Math.sqrt( Math.pow(currPos.left-x, 2) + Math.pow(currPos.top-y, 2) );
	
	
	var duration  = distance / 1 / STETHOSCOPE_SPEED;
	var easing = "linear";
	var orientation = "";

	scope.animate({left: x, top: y}, duration, easing,function(){
		targetPlay(x, y);
	});	
}

function Round(val, precision)
{
    digit = Math.pow(10, precision);

    val = val * digit;
    val = Math.round(val);
    val = val / digit;

    return val;
}

function getAnimationZoomPoints(config){
	var body_image_width = $(".bodymap-front").width();
	var body_image_height = $(".bodymap-front").height();
	var x = config.cx;
	var y = config.cy;
	var r = config.r;

	var prcent_x = (x / 500) * 100;
    var prcent_y = (y / 400) * 100;
    var percent_r = (r / 500) * 100;
   
	return {
		cx: Number((body_image_width / 100) * prcent_x),
		cy: Number((body_image_height / 100) * prcent_y),
		r: Number((body_image_width / 100) * percent_r),
		type: config.type
	}

}
function getAnimationZoomOutPoints(config){
	var body_image_width = $(".bodymap-front").width();
	var body_image_height = $(".bodymap-front").height();
	var x = config.cx;
	var y = config.cy;

	var adjustment_width = Number($("#zoom_adjustment").data("width"));
	var adjustment_height = Number($("#zoom_adjustment").data("height"));

	var prcent_x = (x / adjustment_width) * 100;
    var prcent_y = (y / adjustment_height) * 100;
   
	return {
		cx: Number((body_image_width / 100) * prcent_x),
		cy: Number((body_image_height / 100) * prcent_y),
	}

}

function checkNullConfig(config){
	var configuration = {};
	for (var key in config) {
		var settings = {
			x: config[key].x ? config[key].x : 0,
			y: config[key].y ? config[key].y : 0,
			r: config[key].r ? config[key].r : 0
		}
		configuration[key]= settings;
	}
	return configuration;
}

function isDragArea(x,y){
	var scope = $("#stethoscope");
	var width = $(".bodymap-frame").width();
	var height = $(".bodymap-frame").height();
	var bottom = height - scope.height();
	var right = width - scope.width();
	if((x>=0 && y>=0) &&  (x<=right && y<=bottom)){
		return true
	}
	return false;
}

//set Sound Playback Rate
function setSoundRate(playrate) {
	//console.log("set rate",playrate)
	for (var key in bodymap_audios[0]) {
		var audio = bodymap_audios[0][key]; 
		audio.rate(playrate);		
	}
}

function getVolumeByRadius(position, x, y, radius) {
	var top_r = (position.top + radius) - y;
	var left_r = (position.left + radius) - x;
	var bottom_r = Math.abs((position.top - radius) - y);
	var right_r = Math.abs((position.left - radius) - x);

	var radius_p = 0;

	if (top_r < left_r && top_r < bottom_r && top_r < right_r) {
	  radius_p = top_r;
	} else if (left_r < top_r && left_r < bottom_r && left_r < right_r) {
	  radius_p = left_r;
	} else if (bottom_r < top_r && bottom_r < left_r && bottom_r < right_r) {
	  radius_p = bottom_r;
	} else if (right_r < top_r && right_r < left_r && right_r < bottom_r) {
	  radius_p = right_r;
	} else {
	  radius_p = radius;
	}

	var vol_p = radius_p/radius;
	return vol_p;
}

function getSoundSpeed(value){
	switch(value){
	  case 0:
		return 0.8
	  case 1: 
		return 0.9
	  case 2:
		return 1.0
	  case 3:
		return 1.2
	  case 4:
		return 1.4
	  case 5:
		return 1.6
	  case 6:
		return 1.8
	  case 7:
		return 2.0
	  default:
		return 1.0
	}
  }

function actualPosition($o)
{


//	$(document.body).addClass('scale_1');
	var pos = $o.position();

	var matrixRegex = /matrix\((-?\d*\.?\d+),\s*0,\s*0,\s*(-?\d*\.?\d+),\s*0,\s*0\)/;
	var matches =$("body").css('transform').match(matrixRegex);
	if (matches) {
	var scale = matches[1];
		pos.left /= scale;
		pos.top /= scale;
	}
//	$(document.body).removeClass('scale_1');
	return pos;
		
}