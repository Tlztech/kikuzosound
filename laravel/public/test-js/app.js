//screen.orientation.lock('landscape');

//var contents_type = '';//'heart';

if (!window.console){
	window.console = {
		log : function(msg){
		}
	};
}

var current_choices = null;

String.prototype.replaceAll = function (org, dest){  
	return this.split(org).join(dest);  
} 

var mouseDown = false;
var startX, startY;
var lastX, lastY;

var bodymap_targets = {
//	"" : 
};

var  bodymap_audios = [];//new Array(){};
var current_audio_index = 0;

var audio_started = false;
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

function loadContetsScripts()
{
	var timestamp = Date.now();
/*
	// 基礎学習
	{
		var s = "";
		for (var i = 0; i <  BASIC_SCRIPTS.length; i++) {
			var o = BASIC_SCRIPTS[i];
			if (!o) continue;
			s = '<script type="text/javascript" src="contents/scripts/basic/'+ o.path +'?v=' + timestamp + '"></script>';
			document.write(s);
		}
	}
*/	


	// 病態
	{
		var s = "";
		for (var i = 0; i <  SECTION1_SCRIPTS.length; i++) {
			var o = SECTION1_SCRIPTS[i];
			if (!o) continue;
			s = '<script type="text/javascript" src="test-contents/' + contents_type + '/scripts/section1/'+ o.path +'?v=' + timestamp + '"></script>';
			document.write(s);
		}
	}	

	// 症例学習
	{
		var s = "";
		for (var i = 0; i <  SECTION2_SCRIPTS.length; i++) {
			var o = SECTION2_SCRIPTS[i];
			if (!o) continue;
			s = '<script type="text/javascript" src="test-contents/' + contents_type + '/scripts/section2/'+ o.path +'?v=' + timestamp + '""></script>';
			document.write(s);
		}
	}
	
}
loadContetsScripts();

// $(function(){
// 	$("#load_aus_btn").off().click();
// });
// exec get cases when page is ready
jQuery(($) => {
	$(document).ready(()=>{
		doLearn(CASE_TYPE) // exec when on load
	});
})


function doLearn(case_id) {
console.log("case",case_id)
	if (user_attr == null) {
		$('#user-attribute-wrapper').fadeIn();
	} else {
		_doLearn(case_id);
	}

	(function(case_id) {
		$('#btn-usr-ok').unbind("click");
		$('#btn-usr-ok').bind("click",
			function() {
				var job =  $('input[name="job"]:checked').val();
				//if (!(job)) job = null;
				var otc_check = $('input[name="otc_check"]').prop('checked');
				var athome_check = $('input[name="athome_check"]').prop('checked');
				var otc =  $('input[name="otc"]:checked').val();
				var athome =  $('input[name="athome"]:checked').val();
				var year6 =  $('input[name="6year"]:checked').val();
				var year4 =  $('input[name="4year"]:checked').val();
				var usr_other_pharmacist_text =  $('input[name="usr_other_pharmacist_text"]').val();
				var other_student_text =  $('input[name="other_student_text"]').val();
				var other_text =  $('input[name="other_text"]').val();

				if((!job && !otc_check && !athome_check) ||
					(otc_check && otc==null) ||
					(athome_check && athome ==null) ||
					(job=="調剤薬局" && otc==null && athome==null) || 
					(job=="調剤薬局" && (( otc==null) || (athome==null))) || 
					(job=="その他の薬剤師" && usr_other_pharmacist_text=="") ||
					(job=="6年制薬学生" && year6==null) || 
					(job=="4年制薬学生" && year4==null) ||
					(job=="その他の学生" && other_student_text=="") ||
					(job=="その他" && other_text=="")
				) {

					alert("該当する属性を選んでください");
					if (job=="その他の学生") $('input[name="other_student_text"]').focus();
					return;
				}

				{
					user_attr = {};
					if (otc_check && athome_check) {
						user_attr = "薬剤師－調剤薬局－OTC－"+ otc + "－在宅－" + athome;
					} else 	if (otc_check) {
						user_attr = "薬剤師－調剤薬局－OTC－"+ otc;
					} else if (athome_check) {
						user_attr = "薬剤師－調剤薬局－在宅－" + athome;
					} else {
						switch (job) {
							case "病院":
							case "ドラッグストア（調剤部門なし）":
								user_attr = "薬剤師－" + job
								break;
							case "その他の薬剤師":
								user_attr = "薬剤師－その他の薬剤師";
								user_attr_sub = "－" + usr_other_pharmacist_text;
							break;
							case "6年制薬学生":
								user_attr = "学生－6年制薬学生－" + year6;
								break;
							case "4年制薬学生":
								user_attr = "学生－4年制薬学生－" + year4;
								break;
							case "その他の学生":
								user_attr = "学生－その他の学生";
								user_attr_sub = "－" + other_student_text;
							break;
							case "登録販売者":
								user_attr = "その他－登録販売者";
							break;
							case "その他":
								user_attr = "その他－その他";
								user_attr_sub = "－" + other_text;
							break;
						}
					}

					$('#user-attribute-wrapper').hide();
					_doLearn(case_id);
				}
			}
		);
	})(case_id);

	return false;
}

function _doLearn(case_id) {
	results = {};
	memos = {};
	var Case = CASE_LIST[case_id];
	//if (Case==undefined) { alert("症例学習データが未定義です。\nid = " + case_id); return; }

	current_case = CASE_LIST[case_id];
	case_start_time = (new Date()).getTime();


    $('.top-content').hide();
    $('.button-area').show();
    $('.notice').hide();
	$('.left-pane').show();
	$('.right-area').hide();
	//$('.avator_wrapper').show();
	//$('#QOL').hide();

		
		$('.panel-header .inner').text(current_case.title);

		$('.panel-step-buttons').empty();
		//if (HIDE_STEP_BUTTONS) $('.panel-step-buttons').hide();

		for (var node_id in current_case.nodes) {
			var node = Case.nodes[node_id];
			var id = "step_" + node.step_id;
			var s = '<button id="' + id + '"class="btn-panel-step ">' + node.step_id + '</button>';
			$('.panel-step-buttons').append(s);

			(function(node_id) {
				$('#' + id).bind("click",
					function() {
						buildNodeById(node_id);
					}
				);
			})(node_id);

		}

		for (var node_id in Case.nodes) {
			doAction(case_id, "goto", node_id, 0);
			break;
		}


}

function openImageViewer() {

	var src = $(this).attr('src');
console.log(src);
    $('#overlay').show();
	$('#image-view-panel img').attr('src', src);
	$('#image-view-panel-wrapper').fadeIn(100);	

	$('#image-view-panel img, #image-view-panel-wrapper')
	.off('click')
	.on('click', function(){
		closeImageViewer();
	})
}

function closeImageViewer() {
    $('#image-view-panel-wrapper').fadeOut(100);
    $('#overlay').hide();
	//gotoHome();
}

var videoPlayer = null;
function openVideoViewer() {

	var src = $(this).attr('data-video-src');
console.log(src);
    $('#overlay').show();
    $('#video-view-panel video').attr('src', src);    
	$('#video-view-panel-wrapper').fadeIn(100);	

    var me = $('#video-view-panel video').attr('me');
    {
        $('#video-view-panel video')
        .mediaelementplayer(false) // 削除
        .mediaelementplayer(
                            {                                                     
                                success: function(me, dom){  
                                    videoPlayer = me;
                                //$(dom).prop('me', me);
                                //$(me).on('click', clickVideo);
                                me.play();
                                //me.currentTime = 0; // error on IE
                                me.pause();
                                	setTimeout(function(){me.pause();}, 0); 
                                }
                            }
                            );
    }

	$('#video-view-panel video, #video-view-panel-wrapper')
	.off('click')
	.on('click', function(){
		//closeVideoViewer();
	})
}

function closeVideoViewer() {    
    $('#video-view-panel-wrapper').fadeOut(100);
    $('#overlay').hide();
    videoPlayer.pause();
    $('#video-view-panel video').mediaelementplayer(false);
    videoPlayer = null;
}

function closeMovie() {
//	$('#movie-panel').hide();

	if (USE_MP4) {

	} else {
		var player = document.getElementById("medialayer");

		if (player != null) {
			try {
				player.Stop();
				//player.Close();
				player.style.display = "none";
				player.style.visibility = "hidden";
			} catch(e) {
			}
		}

		$('#mediaplayer').hide();
	}

	$('#movie-panel').empty();
	gotoHome();
}

function closeUserAttr() {
	$('#user-attribute-wrapper').hide();
}


function setupViewport(isRotated) {
	//return;
	//$(window).scrollTop(0,1);
	//return;
forceScale = null;
//http://tokidoki-web.com/2013/05/スマホやブラウザの画面サイズや向きを取得して/


//alert($(window).width() + ", " + $(window).height());
//alert(window.innerWidth +" ," + window.innerHeight);
	var width = window.innerWidth ? window.innerWidth: $(window).width();
	var height = window.innerHeight ? window.innerHeight: $(window).height();

	var is_iOS = false;
	var is_iPad = false;
	var is_iPhone = false;
	var is_iPhonePlus = false;
	var is_Android = false;
	var is_Windows = false;
	var is_Mac = false;
	var ua = window.navigator.userAgent.toLowerCase();
	if (ua.indexOf('iphone') != -1 ) {
		is_iPhone = true;
		if(window.devicePixelRatio == 3)  {
			is_iPhonePlus = true;
		}
	} else if(ua.indexOf('ipad') != -1) {
		is_iPad = true;
	}
	var major_version = '';
	//if (ua.indexOf('iphone') != -1 || ua.indexOf('ipad') != -1) {
	if (ua.indexOf('ip') != -1) {
		is_iOS = true;
		var version = ua.split('os ')[1].split(' ')[0];
		if (version.indexOf('_') >= 0){
			// メジャーバージョンのみ取得
			major_version = parseInt(version.split('_')[0]);
		} else {
			major_version = version;
		}
	} else if (ua.indexOf('android')  != -1) {
		is_Android = true;
	} else if (ua.indexOf('windows')  != -1) {
		is_Windows = true;
	} else if (ua.indexOf('mac')  != -1) {
		is_Mac = true;
	}



	if (is_iOS || ua.indexOf('android')  != -1 ) {
	} else {
		s = 'OS:'
		if (is_Windows) s += 'Windows';
		else if (is_Mac) s += 'Mac';
		else s += '?';
		s += '<br />\n';
		s += ua + '<br />\n';
		$('#debug-area').html(s);
		return;
	}



	var view_width = 1280;//1024;
	var view_height = 720;
	var tmpWidth = document.documentElement.clientWidth;
    var screenWidth = window.outerWidth;//screen.width;
    //var w = $(window).width() * (768/1024);// / $(window).height();    
	//if ($(window).width() > $(window).height()) {


	if(Math.abs(window.orientation) === 90) {
        
        if (window.innerWidth / window.innerHeight > 16/9) {
            view_width = window.innerWidth * 720 / window.innerHeight;
            view_height = window.innerHeight * 720 / window.innerHeight;
        }
       
		if (is_iOS) screenWidth = screen.height; // TODO 実機では必要？

	} else {
        // portrait
        view_width = 720;
		view_height = 1280;
		if (is_iOS) screenWidth = screen.width; // TODO 実機では必要？
        if (window.innerWidth / window.innerHeight > 9/16) {
            view_width = window.innerWidth * 1280 / window.innerHeight;
            view_height = window.innerHeight * 1280 / window.innerHeight;
        }
	}


	//alert(view_width);
//	screenWidth = $(document).width();//window.innerWidth;//document.documentElement.clientWidth;//$(window).width();
	var scale = screenWidth/view_width;//0.450;
console.log('window.innerWidth: ' + window.innerWidth);
console.log('window.innerHeight: ' + window.innerHeight);
console.log('view_width: ' + view_width);
console.log('scale: ' + scale);
if (forceScale!=null) scale = forceScale;
//scale *= 0.6;
//scale = 0.2;
//alert("width:" + screenWidth + ","+ screen.height + "; view_width:" + view_width + "; scale:" +  scale);
//scale = 0.5;
//view_width +=10;



	var viewport_content = '';

	if (is_iPhonePlus && Math.abs(window.orientation) === 90) {
		//scale *= 0.95;
	}
//	$("meta[name='viewport']").attr('content', "width=device-width,initial-scale="+scale );
		if (is_iPhone && !is_iPhonePlus && major_version <= 11 ) {
//view_width = 640; scale=0.5;
			viewport_content =  'width='+Math.round(view_width) + ', _height=' + view_height + ',shrink-to-fit=no, _initial-scale=' + scale +  '' ;

		} else {
			viewport_content =  'width='+Math.round(view_width) + ', _height=' + view_height + ', _shrink-to-fit=no, initial-scale=' + scale +  '' ;					
		}

		// iPhoneSE initial-sacale なし
		// iPhone7 initial-sacale なし
		// iPhone6 Plus initial-sacale あり
		// iPhone X initial-sacale ?
		// iPhone XS initial-sacale ?
		// iPhone XS Max initial-sacale ?
		// iPhone XR initial-sacale  あり
//return;
		$("meta[name='viewport']").attr('content', viewport_content );
		$('.main_wrapper').css('width', view_width);

		if (!is_iPhone) {
			$('.main').css('height', window.innerHeight);
		}


		var s = '';
		if (is_iOS) {
			s += 'OS: iOS ' + major_version;
			if (is_iPhone) {
				s += ' iPhone';
				if (is_iPhonePlus)  s += ' Plus';
			} else if (is_iPad) {
				s += ' iPad';
			}			
			s += '<br />\n';
		} else if (is_Android){
			s += 'OS: Android ';// + major_version;
			s += '<br />\n';
		}

		s += ua + '<br />\n';

		s += viewport_content;
		s += '<br />\n';
		var zoom = document.body.clientWidth / window.innerWidth;
		//s += 'zoom: ' + zoom;
		$('#debug-area').html(s);

		//	$("meta[name='viewport']").attr('content', 'width='+view_width + ',  initial-scale=' + scale +  + ',  , maximum-scale=' +scale );



	setTimeout(function(){
//	$("meta[name='viewport']").attr('content', 'width='+view_width + ',  initial-scale=' + scale +  ' , minimum-scale=0.1' );
	},300);

// document.write('<meta name="viewport" content', 'width='+view_width + ',  initial-scale=' + scale +  ' , minimum-scale=0.1');

//$("meta[name='viewport']").attr('content', 'width='+view_width );


	$(window).scrollTop(0, 1);

	setTimeout(function(){
		$(window).scrollTop(0,1);
		/*
		setTimeout(function(){
			$(window).scrollTop(0,100);
		},500);
		*/
	},500);

}

			//sertupViewport();
$(function(){
// android 960x431
// iPad 1024x692

	if (debug) {
		$('.debug').show();
		$('.extended_area').show();
	} else {
		$('.debug').hide();
	}

	
	$('.how-to-use').text('監修：' + SUPERVISOR);
	$('.inquiry').text(ILLUSTLATOR ==''? '' : 'イラスト：' + ILLUSTLATOR);
	if (ILLUSTLATOR =='') $('.inquiry').hide();

	$('.section1 .section-header').text(SECTION1_TITLE);
	$('.section2 .section-header').text(SECTION2_TITLE);


	setTimeout(function() {
		setupViewport(false);
	}, 500);

	setTimeout(function() {
		setupViewport(false);
	}, 100);


	$(window).on('orientationchange', function(){
		console.log('orientationchange');
		//alert('orientationchange');

		setTimeout(function() {
		//	setupViewport(0.1);
			//setupViewport(true);
		}, 1500);

		setTimeout(function() {
			setupViewport(true);
		}, 700)

	});

	$(window).on('resize', function(){
		console.log('resize');
		setupViewport(true);
	});

//alert(w);
	//alert($(window).width() + ", " + $(window).height());
//	alert("");
	var slides = "";
	var movies = "";
	var basics = "";
	var pathosis = "";
	var cases = "";

	$('.basic-learning ul').empty();

	//var cases = [[],[],[],[]];
//	s = '<li><a href="#" onclick="doLearn(\'stetho\')">聴診器の使い方</a></li>'
	for (var key in CASE_LIST) {
		if (key==undefined || key=="top") continue;

		var o = CASE_LIST[key];

		if (o.type == BASIC_SLIDE) {
			var s = '<li>'
					+ '<a class="btn_basic-' + key + '" href="javascript:void(0)" onclick="doLearn(\'' + key + '\')">'
					 + '</a></li>';
			//basics += s;
			var $s = $(s);
			$('.basic-learning ul').append($s);
			(function() {
				console.log(o.title);
				var title = o.title;
				$s.mouseenter(function(){
					$('.basic_balloon').text(title).css({left:$(this).position().left+4});
					$('.basic_balloon').show();
					//event.stopPropagation();
					$(this).mouseleave(function(){ $('.basic_balloon').hide(0); });
				});
			})();

		} else {
			var s = '<li><div class="new">' + (o['new'] ? 'NEW':'') + '</div>'
					+ '<a href="javascript:void(0)" onclick="doLearn(\'' + key + '\')">'
					 + '<span class="title">' + o.title + '</span><div class="abstruct">' + (o.abstruct ? o.abstruct : "") +  '</div></a></li>';
			var index = 0;//o.type - 10; 
			switch (o.type){
				case TYPE_PATHOSIS: pathosis += s; break;
				case TYPE_CASE: cases += s; break;
			}
		}

	}

	//$('.basic-learning ul').empty().append(basics);
	$('.pathosis-learning ul.pathosis-list').empty().append(pathosis);
	$('.case-learning ul.case-list').empty().append(cases);


	//$('.case-learning tbody').empty().append(s);
	//$('.case-learning ul').empty().append(s);

	$('#btn-home').bind("click",
				function() {
					gotoHome();
				}
	);

	$('#btn-case-top').bind("click",
				function() {
					gotoCaseTop();
				}
	);

	$(window).unload(function(){
		destructNode();
	});

	// ユーザ属性
	{

		$('#user-attribute-panel input[type="radio"]').attr("disabled", "");
		$('input[name="job"]').removeAttr("disabled");

		$('#user-attribute-panel input').attr("checked", false);	// ラジオボタン全解除

		$('input[name="job"]').change(function(){

		//	$('input[name="pharmacy_sub"]').attr("disabled", "");
		//	$('input[name="otc_check"]').attr("disabled", "");
		//	$('input[name="athome_check"]').attr("disabled", "");

			$('input[name="otc"]').attr("disabled", "");
			$('input[name="athome"]').attr("disabled", "");

			$('input[name="usr_other_pharmacist_text"]').attr("disabled", "");
			$('input[name="usr_other_pharmacist_text"]').val("");

			$('input[name="6year"]').attr("disabled", "");
			$('input[name="4year"]').attr("disabled", "");

			$('input[name="other_student_text"]').attr("disabled", "");
			$('input[name="other_student_text"]').val("");

			$('input[name="other_text"]').attr("disabled", "");
			$('input[name="other_text"]').val("");

			//$('#user-attribute-panel input').attr("disabled", "");
			switch($(this).val()) {
				case '調剤薬局':
//					$('input[name="pharmacy_sub"]').removeAttr("disabled");
					$('input[name="otc"]').removeAttr("disabled");
					$('input[name="athome"]').removeAttr("disabled");
				break;
				case 'その他の薬剤師':
					$('input[name="usr_other_pharmacist_text"]').removeAttr("disabled").focus();
				break;
				case '6年制薬学生':
					$('input[name="6year"]').removeAttr("disabled");
				break;
				case '4年制薬学生':
					$('input[name="4year"]').removeAttr("disabled");
				break;
				case 'その他の学生':
					$('input[name="other_student_text"]').removeAttr("disabled").focus();
				break;
				case 'その他':
					$('input[name="other_text"]').removeAttr("disabled").focus();
				break;
			}

			$('#user-attribute-panel input[disabled]').attr("checked", false);
		});


		//$('input[name="job"]').attr("checked", false);


		$('input[name="otc_check"]').change(function(){
			if ($(this).prop('checked')) {
				$('input[name="otc"]').removeAttr("disabled");
			} else {
				$('input[name="otc"]').attr("disabled", "").attr("checked", false);
			}
		});

		$('input[name="athome_check"]').change(function(){
			if ($(this).prop('checked')) {
				$('input[name="athome"]').removeAttr("disabled");
			} else {
				$('input[name="athome"]').attr("disabled", "").attr("checked", false);
			}
		});

		$('input.pharmacy_sub').change(function(){
			var otc_check = $('input[name="otc_check"]').prop('checked');
			var athome_check = $('input[name="athome_check"]').prop('checked');

			//alert($('input[name="pharmacy_sub"]:checked').val());

			if (otc_check || athome_check) {
				$('input[name="job"]').attr("disabled", "");
			} else {
				$('input[name="job"]').removeAttr("disabled");
			}
			
			if (otc_check) {
				$('input[name="otc"]').removeAttr("disabled");
			}
			if (athome_check) {
				$('input[name="athome"]').removeAttr("disabled");
			}

/*
			switch($(this).val()) {
				case 'OTC':
					$('input[name="otc"]').removeAttr("disabled");
				break;
				case '在宅':
					$('input[name="athome"]').removeAttr("disabled");
				break;
			}
*/

			$('#user-attribute-panel input[disabled]').attr("checked", false);
		});

	}


	$('#btn-enter').click(function() { 
		//alert("enter");
		if (current_choices != null) {
			var text = $('.reply-frame input[type="text"]').val();
			//現在の選択肢リストの中からテキスト入力と一致する選択肢を探し、アクションを実行する。
			for (var i=0; i < current_choices.length; i++) {
				choice = current_choices[i];
				if (choice.text == text) {
					doAction('', choice.action, choice.param);
					return;
				}
			}
		}
	});

	// 静止画クリックで拡大表示
	$(document).on('click', '.viewable_image', openImageViewer);

    // 動画クリックで拡大表示
	$(document).on('click', '.viewable_video', openVideoViewer);

	if (debug) {
		gotoHome();
		//_doLearn('pharmacy_01');
//		openPupillaryReflex("menu_1", { type:PUPILLARY_REFLEX, reflex:"正常" });
//		openPupillaryReflex({ type:PUPILLARY_REFLEX, reflex:"左のみ縮瞳" });
//		openPupillaryReflex({ type:PUPILLARY_REFLEX, reflex:"右のみ縮瞳" });
//		openPupillaryReflex({ type:PUPILLARY_REFLEX, reflex:"散瞳したまま" });
//		openPupillaryReflex({ type:PUPILLARY_REFLEX, reflex:"縮瞳したまま" });
		//_doLearn('stetho');
		//_doLearn('cardio');
//		_doLearn('respstudy');

		// 肺音
		//doAction('menu_1', 'bodymap',{'type':'respiratory','sound':{'respiratorysound1':'resp1-15normal.mp3','respiratorysound2':'resp2-15normal.mp3','respiratorysound3':'resp3-15normal.mp3'}})

		// 心音
		//doAction('menu_2', 'bodymap',{'type':'cardio','sound':{'cardiacsoundA':'cardiAP-60normal.mp3','cardiacsoundP':'cardiAP-60normal.mp3','cardiacsoundT':'cardiTM-60normal.mp3','cardiacsoundM':'cardiTM-60normal.mp3'}})

	} else {
		gotoHome();
	}

	$('.bodymap-frame .loading-wrapper')
	.on('click mousedown touchstart mousemove touchmove mouseup touchend',
		function(){
			return false;
		}
	);

	$('#btn-bodymap-front').on('click', function(){
		$('.btn-bodymap-reverse').removeClass('current');
		$(this).addClass('current');
		$('.bodymap-front').show();
		$('.bodymap-back').hide();

		stopAllVideo();
		$('.left-video-area .video-wrapper').hide();
	});

	$('#btn-bodymap-back').on('click', function(){
		$('.btn-bodymap-reverse').removeClass('current');
		$(this).addClass('current');
		$('.bodymap-front').hide();
		$('.bodymap-back').show();

		stopAllVideo();
		$('.left-video-area .video-wrapper').hide();
    });

    $('#btn-bodymap-front, #btn-bodymap-back').on('mousedown touchstart', function(event){
        event.stopPropagation();
    });
    
	// bodymap
	{
		$('.bodymap-frame').bind('mousedown touchstart', function(event){
			//event.preventDefault();
				var scope = $('#stethoscope');
				var pageX, pageY;
				if (event.type == "mousedown") {
					pageX = event.pageX;
					pageY = event.pageY;
				} else {
					pageX = event.originalEvent.touches[0].pageX; 
					pageY = event.originalEvent.touches[0].pageY; 
				}

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
                            //"easeOutQuad";
				if (true) {
	                scope.animate({left: x, top: y}, duration, easing,function(){
	                    targetPlay(x, y);
	                });
				} else {
	                scope.css({left: x, top: y});
	                    targetPlay(x, y);
				}
                

				lastX = pageX;
                lastY = pageY;

                return true;
		});

		$('#stethoscope').bind('mousedown touchstart', function(event) {
			//console.log("#stethoscope mousedown");

			$('#stethoscope .label').hide();
			mouseDown = true;
			if (event.type == "mousedown") {
				startX = lastX = event.pageX;
				startY = lastY = event.pageY;
			} else {
				//alert("touchstart");
				startX = lastX = event.originalEvent.touches[0].pageX;
				startY = lastY = event.originalEvent.touches[0].pageY; 
				//alert(startX + "; " + startY);
			}

			//event.preventDefault();

		});



		//$('#stethoscope').



		$('#stethoscope').bind('mouseenter', function(event) {
			$('#stethoscope .label').show();
		});

		$('#stethoscope').bind('mouseleave', function(event) {
			$('#stethoscope .label').hide();
		});
	}

/*
	$('#btn-bodymap-close').click(function(){
		CloseBodyMap();
	});


	$('#btn-bodymap-ok').click(function(){
		var val = $('input[name="question"]:checked').val();

		CloseBodyMap();
	});
*/




	// クエリに症例の指定があれば起動後に症例を開く
	{
		var vars = getUrlVars();

		// ユーザ属性
		if (vars['user_attr']) {
			user_attr = decodeURI(vars['user_attr']);			
			if (vars['user_attr_sub']) {
				user_attr_sub = decodeURI(vars['user_attr_sub']);
			}			
		}

		// 症例ID
		if (vars['case_id']) {
			doLearn(vars['case_id']);
		}
	}

});


function gotoHome() {

	CloseBodyMap();
	destructNode();


    $('.top-content').show();
    $('.button-area').hide();
    $('.notice').show();
	$('.left-pane').hide();
	$('.right-area').hide();
	//$('.avator_wrapper').show();
	//$('#QOL').hide();
	//resetQOLScore();

	$('#movie-panel-wrapper').hide();

	$('.main').css('background-image', '');

	current_case = CASE_LIST["top"];
	buildNodeById('init');
}

function gotoCaseTop() 
{

	destructNode();

	//resetQOLScore();

		for (var node_id in current_case.nodes) {
			doAction("", "goto", node_id, 0);
			break;
		}
//	buildNodeById();
}

/**** ノードを破棄する *****/
function destructNode() {
	//console.log($('#memo').val());

	$('.right-area').hide();

	$('.popup-menu').hide();

	if (text_audio != null) {
		text_audio.pause();
		text_audio = null;
	}

	if (auto_forward_timer) {
		clearTimeout(auto_forward_timer);
		auto_forward_timer = null;
	}

	if (current_node) {
		memos[current_node.step_id] = $('#memo').val();
	}
}


/**** ノードを構築する *****/
function buildNodeById(node_id){
	if (current_case == null) return;
	var node_data = current_case.nodes[node_id];
	if (node_data == undefined || node_data == null) {
		alert("ノードデータが定義されていません。 (" + node_id + ")");
		return;
	}
	buildNode(node_data);
	
}

var text_timer = null;
function buildNode(node_data){

	destructNode();

	current_node = node_data;
	if (!results[node_data.step_id]) results[node_data.step_id] = {};

	// 背景画像設定
	if (current_node.background_image) {
		//$('.main').css('background-image', 'url(' + BACKGROUND_IMAGE_PATH + current_node.background_image + ')');
	} else if (current_case.background_image) {
		//$('.main').css('background-image', 'url(' + BACKGROUND_IMAGE_PATH + current_case.background_image + ')');
	} else {
		
	}

	{
		for (var i=0; i < current_node.menu.length; i++) {
			var menu = current_node.menu[i];
			if (!menu) continue;
			if (menu.action == "popup") {
				for (var j=0; j<menu.param.length; j++) {
					if (!sub_menu) continue;
					var sub_menu = menu.param[j];
					sub_menu.id = "" + (i+1) + "_" + (j+1);
				}
			}
		}
	}

	{
		$('.btn-panel-step').removeClass('current');
		$('#step_' + node_data.step_id).addClass('current');
		$('.panel-sub-content').empty().hide();
	}


    //$('.viewable_image_area').show();

	// bodymap reset
	{
		CloseBodyMap();
		//closePupillaryReflex();
		//$('.bodymap-frame').hide();
		//$('#stethoscope').css({left:"380px", top: "600px"});

		var show_result = current_case.has_question && (node_data.step_id.match(/[ABC]/) != null);		
		if (show_result) {

			 $('#btn-result').show();
			 //$('#bodymap-question').empty().append(question).show();
			 $('#btn-bodymap-ok').show();
			 $('#btn-bodymap-close').removeClass('center');
		} else {
			 $('#btn-result').hide();
			 $('#bodymap-question').empty().hide();
			 $('#btn-bodymap-ok').hide();
			 $('#btn-bodymap-close').addClass('center');
		}

		var show_memo = current_case.enable_memo;
		if (show_memo) {
			$('#btn-memo').on("click",function(){
				$('#memo').show();
			});
			 $('#btn-memo').show();
			 $('#memo').hide();
		} else {
			 $('#btn-memo').hide();
			 $('#memo').hide();
		}

		if (audio != null) audio.pause();
		audio = null;
        currernt_audio_file = "";
        current_audio_calss = "";
	}




	if (true){
		var s = "";
		var index = 0;
		if (MESSAGE_SPEED == 0 || text_audio_off) {
			clearInterval(message_timer);
			s += node_data.text.replaceAll('\n', '<br />');
			$('.explanation-inner .scroll-view')
			.empty()
			.append(s)
			.scrollTop(0);

		} else {
			s += node_data.text;//.replaceAll('\n', '<br />')
			$('.explanation-inner .scroll-view')
			.empty()
			.append(s)
			.contents().each(function() {
				switch(this.nodeType) {
					// ElementNode
					case 1: {
						$(this).contents().each(function() {
							$(this).replaceWith($(this).text().replace(/(\S)/g, '<span>$1</span>'));
						});
					} break;
					// TextNode
					case 3: {
					var text = $(this).text().replace(/(\S)/g, '<span>$1</span>').replaceAll('\n', '<span><br /></span>');
					$(this).replaceWith(text);
					} break;
				}
				// alert($(this).tagName);
			});

			(function() {
				clearInterval(message_timer);
				var _index = 0;
				var count = $('.explanation-inner .scroll-view span').length;
				message_timer = setInterval(
					function() {
						if (_index > count) {
							clearInterval(message_timer);
							return;
						}
						var target = $('.explanation-inner .scroll-view span').get(_index);
						$(target).css({display:"inline", opacity: 1});

						$('.explanation-inner .scroll-view').scrollTop(9999);
						_index++;
					}
					, 100/MESSAGE_SPEED
				);
			})();

		}

			if (text_audio_off) $('#btn_text_audio_volume').addClass('off');

	}

	// 右カラムイメージ
	{


		var image_base = getImageContentsBaseUrl();
		var video_base = './test-contents/' + contents_type + '/video/';
/*
		var image_base = BASIC_CONTENTS_IMAGE_PATH;
		if (isCaseStudy()) {
			image_base = CASE_CONTENTS_IMAGE_PATH + "png/";
		}
*/

//		var panel_contents = $('.dual-panel-wrapper .panel-content').empty();
		var panel_contents = $('.panel-content').empty();
		for (var i = 0; i < panel_contents.length; i++) {
			if (node_data.images == null) {
				panel_contents.hide();
				$('.panel-header').hide();
				continue;
			}
			panel_contents.show();
			$('.panel-header').show();
			if (node_data.images[i] == undefined) continue;
			switch (node_data.images[i].type) {
			case "image": {
				var src = image_base + node_data.images[i].src;
				if (i==0) {
                    if (false/*type=='cardiac'*/){
                        /*
                        var onclick = "openImageViewer('" + src + "')";
                        var s = '<img class="viewable_image" src="' + src + '" />';
                        s += '<div class="btn-maximize" style=""></div>';
                        panel_contents.eq(i).empty().append(s);
                        */
                    } else {
                       //var src = "../images/ui/bodymap/bodymap1_01.png";
                        var s = '<img class="viewable_image" src="' + src + '" />';
                        s += '<div class="btn-maximize" style=""></div>';
                        panel_contents.eq(i).empty().append(s);
                    }

				} else {
					$('#bodymap-cardiac .bodymap-front').css('background-image', 'url('+ src + ')');
				}
			} break;

			case "video": {
                var poster = image_base + node_data.images[i].poster;
                var video_src = video_base + node_data.images[i].src;
                
				if (i==0) {
					//var onclick = "openVideoViewer('" + src + "')";
					var s = '<img class="viewable_video" src="' + poster + '" data-video-src="' + video_src + '" />';
                    s += '<div class="btn_overlay-play" style=""></div>';
					panel_contents.eq(i).empty().append(s);
				} else {
					$('#bodymap-cardiac .bodymap-front').css('background-image', 'url('+ src + ')');
				}

				panel_contents.eq(i).empty().append(s);
			} break;
			default:
				panel_contents.eq(i).empty();
			break;
			}
		}


/*
		setTimeout(function(){
			$('.panel-content').css({display:"none", opcacity:0.5});
			setTimeout(function(){
			$('.panel-content').css({display:"block", opcacity:0.5});
			}, 100);
		}, 100);
*/
	}

	//オペレーターテキスト
	{
		var s = node_data.text == undefined ? "" : node_data.operator_text;
		$('.operator-balloon-inner').text(s);

		$(".operator-balloon-inner").stop();
		$(".operator-balloon-inner").css({color:"#FFF", opacity:1.0});
	
		if (node_data.operator_text_effect != undefined && node_data.operator_text_effect=="flash") {
			$(".operator-balloon-inner").css({color:"#FFF"});
			(function(){
				var func = arguments.callee;
				$(".operator-balloon-inner").fadeTo(600,(func.b = !!!func.b) ? 0.1 : 1,func);
			})();
		}

	}

//	$('.balloon-inner .scroll-view').empty().append(node_data.text.replace(/\n/g, '<br />'));
//	console.log(node_data.text.replace('\n', '<br />'));

	var listArea = $('#main-menu');//$('.menu .list-area ul');
	listArea.empty().fadeIn(300);
	$('#sub-menu').empty().hide();


	var menu = node_data.menu;
	for (var i=0; i < menu.length; i++) {
		var s = "";
		var munu_item = menu[i];
		var id= "menu_" + (i+1);
		if (!munu_item) continue;
		var param = JSON.stringify(munu_item.param ? munu_item.param : {}).replaceAll("\"", "'");
		var QOL = (munu_item.QOL == undefined) ? 0 :munu_item.QOL;

		if (munu_item.action=='bodymap') {
			$('.popup-menu').hide();
			var param = munu_item.param ? munu_item.param : {};
			doAction(id, munu_item.action, param, QOL);
			break;
		}

		var onclick = "$('.popup-menu').hide(); doAction('" + id + "', '"  + munu_item.action + "'," + param + "," + QOL + ")";
		var onmouseover = munu_item.action=="popup" ? onclick: "$('.popup-menu').hide();";
		var _class = munu_item.action=="popup" ? "popup": "";
		//var onclick = "popupMenu('" + id + "', '" + munu_item.param + "');";
		s += '<li class="' + _class + '" id="' + id + '" onclick="' + onclick + '" onmouseenter="' + onmouseover + '"><a href="javascript:void(0);" >' + munu_item.text + '</a>';
		

		if (munu_item.action == "popup") {
			var choices = munu_item.param;//choices_map[munu_item.param];
			if (choices != undefined) {
				s += '<ul class="popup-menu" onclick=" (event.stopPropagation) ? event.stopPropagation() : event.cancelBubble = true;">';
				current_choices = choices;
				for (var j=0; j < choices.length; j++) {
					var choice = choices[j];
					if (!choice) continue;
					//var onclick = "onClickSubMenu('" + choice.text + "')";
					var param = JSON.stringify(choice.param).replaceAll("\"", "'");
					var QOL = choice.QOL ? choice.QOL : 0;
					var onclick = " doAction('" + choice.id + "', '"  + choice.action + "'," + param + "," + QOL + "); (event.stopPropagation) ? event.stopPropagation() : event.cancelBubble = true;";
					s += '<li onclick="' + onclick + '"><a href="javascript:void(0);" >' + choice.text + '</a></li>';
				}
				s += '</ul>';
			}
		}
		if (munu_item.action == "play-sound") {
			var audio_id = "audio_" + (i+1);
			var play_btn_id = "btn_play_"+ (i+1);
			//var onPlayClick = "stopAllSound(); document.getElementById('" + audio_id + "').play(); $('#" + play_btn_id + "').addClass('playing')";
			//var onStopClick = "stopAllSound(); document.getElementById('" + audio_id + "').pause()";
//			var onPlayClick = "stopAllSound();  MediaElement('" + audio_id + "',{success:function(me, domObject){ alert(me); $('#"+ audio_id +"').me = me; alert($('#"+ audio_id +"').me); me.play();}}); $('#" + play_btn_id + "').addClass('playing')";
			var onPlayClick = "stopAllSound();  $('#"+ audio_id +"').prop('me').play(); $('#" + play_btn_id + "').addClass('playing')";
			var onStopClick = "stopAllSound(); document.getElementById('" + audio_id + "').me.pause()";



			var audio_base = BASIC_CONTENTS_AUDIO_PATH;//"./contents/audio/";
			if (!isBasicStudy()) {
				audio_base = CASE_CONTENTS_AUDIO_PATH ;//+ "/" + param.type + "/";
			}
			
			s += '<audio id="' + audio_id + '" src="' + audio_base + munu_item.param + '" /><button id="' + play_btn_id + '" class="play" onclick="' + onPlayClick + '">再生</button> <button class="stop" onclick="' + onStopClick + '">停止</button>';
		}
		s += '</li>';
		listArea.append(s);

			if (audio_id) {
				MediaElement( audio_id,
						{
							success:function(me, domObject){
							$(domObject ).prop('me', me);
						}
				});
			}


	}
	//console.log(s);


/*
	// Note
	if (node_data.note != undefined ) {		
		var s = '<div class="note">' + node_data.note.replaceAll('\n', '<br />') + '</div>';
		listArea.append(s);
	}
*/

	$('#btn-back').unbind("click");
	$('#btn-forward').unbind("click");

	// 戻るボタン
	if (node_data.back == undefined || node_data.back == null) {
		$('#btn-back').hide();
	} else {
		$('#btn-back').show();
		(function(node_id){
			$('#btn-back').bind(
				"click",
				function() {
					buildNodeById(node_id);
				}
			);
		})(node_data.back);
	}

	// 進むボタン
	if (node_data.forward == undefined || node_data.forward == null) {
		$('#btn-forward').hide();
	} else {
		$('#btn-forward').show();
		(function(node_id){
			$('#btn-forward').bind(
				"click",
				function() {
					buildNodeById(node_id);
				}
			);
		})(node_data.forward);
	}

	$('.reply-frame input[type="text"]').val("");


	// 自動遷移制御
	{
		restartAutoFowardTimer();
/*
		$(document).on('mousedown mousemove touchstart touchmove', function(){
			restartAutoFowardTimer();
		});
*/
	}

	if (current_case != CASE_LIST["top"]) {
		setTimeout(function() {
			$('.right-area').fadeIn(1);	
		}, 0);
	}

	// タイトル表示
	{
		$('.panel-header .inner').show();
		if (node_data.hide_title != undefined) {
			if (node_data.hide_title) {
				$('.panel-header .inner').hide();
			}
		} else {
			if (current_case.hide_title == true) {
				$('.panel-header .inner').hide();
			}
		}

	}

	{
		$('.panel-step-buttons').show();
		if (node_data.hide_step_buttons != undefined) {
			if (node_data.hide_step_buttons) {
				$('.panel-step-buttons').hide();
			}
		} else {
			if (current_case.hide_step_buttons == true) {
				$('.panel-step-buttons').hide();
			}
		}

	}


}

function restartAutoFowardTimer()
{
	if (!current_node.auto_forward) return;

		clearTimeout(auto_forward_timer);

		var time = DEFAULT_AUTO_FORWARD_TIME ;
		if (current_node.auto_forward_time != undefined) time = current_node.auto_forward_time;
		if (time <= 0) return;
		auto_forward_timer = setTimeout(
			function() {
				if (current_node.auto_forward) {
					buildNodeById(current_node.auto_forward);
				}
			}, time * 1000
		);
}

function stopAutoFowardTimer()
{
	if (auto_forward_timer == null) return;
	clearTimeout(auto_forward_timer);
	auto_forward_timer = null;
}

function isBasicStudy() {

	if (current_case.type <= BASIC_SLIDE)  return true;
	else return false;
}

function isCaseStudy() {

	if (current_case.type == TYPE_CASE)  return true;
	else return false;
}

function getImageContentsBaseUrl()
{
	return './test-contents/' + contents_type + '/images/';
	switch (current_case.type) {
		case BASIC_SLIDE:
			return BASIC_CONTENTS_IMAGE_PATH;
			break;
		case TYPE_PATHOSIS:
			return PATHOSIS_CONTENTS_IMAGE_PATH ;//+ "png/";
			break;
		case TYPE_CASE:
			return CASE_CONTENTS_IMAGE_PATH ;//+ "png/";
			break;
	}
}

function showSubContentPanel()
{
	$('.panel-content').fadeOut(500);
	$('.panel-sub-content').empty().append(s).fadeIn(1000);
}

function hideSubcontentPanel()
{
}

function doAction(menu_id, action, param, QOL) {

	//addQOLScore(QOL);

	//$('.right-area').fadeIn();

	if (action != "popup") {
		$('.panel-sub-content').hide();
		CloseBodyMap();
		//closePupillaryReflex();
	}

	var sub_content = null;
	//results[current_node.step_id][menu_id] = "hoge";	

	switch (action) {
		case "popup": {
			popupMenu(menu_id, param);
		} break;
		case "goto": {
//			buildNode(node_data_map[param]);
			buildNode(current_case.nodes[param]);
		} break;
		case "open": {
			window.open(param.url, "_blank");
		} break;
		case "image": {
			var image_base = getImageContentsBaseUrl();
/*
			var image_base = BASIC_CONTENTS_IMAGE_PATH;
			if (isCaseStudy()) {
				image_base = CASE_CONTENTS_IMAGE_PATH + "png/";
			}
*/
			var src = image_base + param;
			var onclick = "openImageViewer('" + src + "')";
			sub_content = '<img class="viewable_image" src="' + src + '" />';
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);

		} break;
		case "video": {
			var src = CONTENTS_VIDEO_PATH + param;
			if (USE_MP4) {
				var onclick = "";//"clickVideo(event);";
				var s = '<video id= "video_sub" controls autoplay width="400" height="300" onclick="' + onclick + '" ontouchstart="' + onclick + '"><source type="video/mp4;" src="' + src+ '" /></video>';
					s += "<script>MediaElement('video_sub', {success: function(me){  /*me.play(); setTimeout(function(){me.pause();}, 300); */ } });</script>"
			} else {
			 src= src.replace('.mp4', '.wmv');

			var s = '<object id="medialayer" width="400" height="300" autoplay \
			  type="video/x-ms-wmv"\
			  id="WMPPlayer" \
			  data="' + src + '" >\
			  <param name="uiMode" value="full" />\
			  <param name="AutoStart" value="true" />\
			  <param name="filename" value="' + src + '" />\
			</object>';
			s += "<script>setTimeout(function(){ /*medialayer.pause();*/}, 300);</script>"
			}
			sub_content = s;
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);
		} break;
		case "bodymap": {
			CloseBodyMap();
			OpenBodyMap(menu_id, param);
			results[current_node.step_id][menu_id] = "";
		//	bodymap_targets[]
		} break;
		case "watch": {
			var sub_dir = "body";
			var src = CASE_CONTENTS_IMAGE_PATH + /*sub_dir + "/" + param.type + "/"*/ + param.image;
			var units = {"shoulder":"bpm"};
			var unit = "";
			if (param.text.indexOf("チェーンストークス") == -1) unit = units[param.type] ? units[param.type] : "";
			sub_content = '<div class="watch-body"><img class="viewable_image" src="' + src + '" /><div class="text">' + param.text + unit + '</div></div>';
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);
			results[current_node.step_id][menu_id] = param.text;
		} break;
		case "touch": {
			var sub_dir = "touch";
			var touch_imagess = {
				radius:"150touch_01.png",
				carotid:"151touch_02.png",
				instep:"152touch_03.png"
			};
			var src = CASE_CONTENTS_IMAGE_PATH + /*sub_dir + "/"*/ + touch_imagess[param.type];//param.image;
			var unit = "bpm";
			sub_content =  '<div class="watch-body"><img src="' + src + '" /><div class="text">' + param.text + unit + '</div></div>';
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);
			results[current_node.step_id][menu_id] = param.text;
		} break;
		case "ask": {
			sub_content = '<div class="ask"><div class="dummy"></div><div class="text">' + param.answer + '</div></div>';
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);
			results[current_node.step_id][menu_id] = "";
		} break;
		case "equip": {
			// 瞳孔反射
			if (param.type == "瞳孔反射") {
				results[current_node.step_id][menu_id] = "";
				openPupillaryReflex(menu_id, param);
				return;
			}

			

			//var disp_units = { "体温計":"℃" };
			//var device = devices[param.type];
			//var sub_dir = "_device";
			//var image_name = device.image;
			var src = CASE_CONTENTS_IMAGE_PATH + /*sub_dir + "/" +*/ image_name;
/*			
			var text = "";
			if ( param.texts && device.classes) {
				for (var i=0; i < param.texts.length; i++) {
					var t = param.texts[i];
					var disp_unit = disp_units[param.type] ? disp_units[param.type] : "";
					text += '<div class="text ' + device.classes[i] + '">' + t + disp_unit + '</div>';
				
				}
			}

			sub_content =  '<div class="equip"><img class="viewable_image" src="' + src + '" />' + text +'</div>';
			*/
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);
			results[current_node.step_id][menu_id] = (param.texts) ? param.texts.join("/") : "";
		} break;

		case "data": {
			var text = param.text.replaceAll("\n", "<br />");
			if (param.url) text = '<a target="_blank" href="' + param.url + '">' + text + '</a>'
			sub_content =  '<div class="data">' + text +'</div>';
			//$('.panel-sub-content').empty().append(s).fadeIn(1000);
			results[current_node.step_id][menu_id] = "";
		} break;
		case "stop_auto_forward": {
			stopAutoFowardTimer();
		} break;
	}

	if (sub_content) {
		$('.panel-sub-content')
		.empty()
		.append(sub_content);


		$('.panel-content').hide();
		//$('.panel-content').fadeIn(300, function() {
			$('.panel-sub-content').fadeIn(1000);
		//});
	}
	
}

function onClickSubMenu(text) {
	$('.reply-frame input').val(text);
	$('.popup-menu').hide();
}

function popupMenu( menu_id, choices_id) {
//alert($('#'+menu_id + ' .popup-menu').length);
//$('#'+menu_id + ' .popup-menu')
	//$(document).unbind('click');
	$('.popup-menu').hide();
//alert('popupMenu');
	var clone_menu = $('#'+menu_id + ' .popup-menu').clone();
	$('#'+menu_id + ' .popup-menu').show();
	clone_menu
	.fadeIn(200, function() {	$(document).bind('click', function(event){ 
			//$('.popup-menu').hide();
		});	});
	current_choices = choices_map[choices_id];

//var offset = $('#'+menu_id + ' .popup-menu').offset();
var offset = $('#'+menu_id ).offset();
var parentLeft = $('#'+menu_id ).position().x;
var menu_left = $('#'+menu_id ).width() + 20 + 20 + 20;
var pos = $('#'+menu_id + ' .popup-menu').position();

	var clone_menu = $('#'+menu_id + ' .popup-menu').clone();
	clone_menu.css({opacity:1, left:menu_left/*offset.left*/+"px", top:offset.top+"px"}).fadeIn(200);
	//$('body').append(clone_menu);
	$('.main').append(clone_menu);

$('#'+menu_id + ' .popup-menu').hide();
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

function targetPlay(x, y) {
    var scope = $('#stethoscope');
    var ss_cx = x + scope.width()/2;
    var ss_cy = y + scope.width()/2;


    var target_border_width = 3;

	var hits = [];
	var none_hits = [];

    for (var target in bodymap_targets) {
        $('#'+target).each(function() {
            //console.log($(this).width());
            var hit = false;
            var extended_hit = false;
            var classes = $(this).attr("class").split(' ');
            var _class = "";//$(this).attr("class").split(' ')[0];

            var id = $(this).attr("id");            

            for (var i=0; i < classes.length; i++) {

                if (bodymap_targets[classes[i]]) {
                    _class = classes[i];
                    break;
                }
            }
			var audio = bodymap_audios[current_audio_index][id];
			var video_id = $('#'+target).attr('data-video-id');

            var target_pos = actualPosition($(this));//$(this).position();
            //target_pos.left -= target_border_width;
            //target_pos.top -= target_border_width;
            var target_w = $(this).outerWidth();
            var target_h = $(this).outerWidth();
			var scope_radius = $('#stethoscope').width() / 2;
            var target_radius = target_w/2 + scope_radius;
            var extended_target_radius = $(this).find('.extended_area').outerWidth() / 2 + scope_radius;//target_w/2 + EXTEND_AREA_RADIUS;
            var target_cx = target_pos.left + target_w/2;
            var target_cy = target_pos.top + target_h/2;
            var distance = Math.sqrt(Math.pow(target_cx - ss_cx, 2) + Math.pow(target_cy - ss_cy, 2));
			console.log("distance: "  + distance);
			
            if (distance <= extended_target_radius ) {
                
                extended_hit = true;
                //console.log("extended hit");
                //console.log("distance: "  + distance);
                var audio_file = bodymap_targets[_class];
                
                //if (audio != null && current_audio_calss == _class) {
                if (false/*audio.playing()*/) {
                    //console.log(audio.src);
                    
                } else {
                    //console.log("play: " + audio_file);
                    if (audio != null) {
                        //audio.fade(audio.volume(), 0, 30/*ms*/);
                    }
                    

                    //playng_audios.push(audio);
                    currernt_audio_file = audio_file;
                    current_audio_calss = _class;
                }

                var newVolume = 0;
                var r1 = current_bodymap_param.sound[target].r1;
                var r2 = current_bodymap_param.sound[target].r2;                
                var v1 = current_bodymap_param.sound[target].v1;
                var v2 = current_bodymap_param.sound[target].v2;
                if (current_bodymap_param.sound[target].r1 == undefined) {
                    r1 = 28;
                    r2 = 50;
                    v1 = 0.5;
                    v2 = 0.3;
                }
				var core_hit = false;
                if (distance <= target_radius ) {
                    if (false && r1 > distance) {
                        //audio.volume = 0.5;
                        newVolume = 1.0;
                    } else {
                        core_hit = true;
                        //var volume = (target_radius - distance ) / (target_radius-r1) + v1;
                        var volume = (1.0 - v1) * (r1 - distance +scope_radius) / (r1+scope_radius) + v1;
                        newVolume = Math.min(volume, 1.0);
					//console.log('core zone:' + newVolume);
                    }
                } else {
                    //newVolume = 0.3;
                    var volume = (v1-v2) * (extended_target_radius - distance ) / (extended_target_radius - target_radius + scope_radius) + v2;
                    newVolume = Math.min(volume, 1.0);
					//console.log('extended zone:' + newVolume);
                }

				//if (!audio.playing()) audio.play();
                //audio.fade(audio.volume(), newVolume, 30/*ms*/);
                var hit = {
                        id: target,
						core_hit: core_hit,
                        audio:audio,
                        distance:distance,
                        volume: volume,
                        video_id: video_id//$('#'+target).attr('data-video-id')
                        };
                hits.push(hit);
                //console.log(newVolume);                
            }

            if (distance <= target_radius ) {
                hit = true;
                
                //console.log("distance: " + distance + " dx: " + (target_cx - ss_cx) + " dy: " + (target_cy - ss_cy)  );

                //console.log("hit");


//                $(this).css({opacity:1});
//                $(this).find('*').css({opacity:1});


            } else {
                if (!debug) {
                    $(this).css({opacity:0});
                    $(this).find('*').css({opacity:0});
                }
            }

            if (!extended_hit) {
                //console.log("not hit");
        
                if (audio != null) {
                    if (debug) console.log("mute audio");
                    //for (var i=0; i < playng_audios.length; i++) {
                        audio.fade(audio.volume(), 0, 30/*ms*/);
					//}
					var video = $('#' + video_id).get(0);
					if (video != null) {
						//video.pause();
					}
                    
                    //audio.pause();
                    //audio = null;
                    currernt_audio_file = "";
                    current_audio_calss = "";
				}
				
				var none_hit = {
					id: target,
					core_hit: core_hit,
					audio:audio,
					distance:distance,
					volume: volume,
					video_id: video_id
					};
			none_hits.push(none_hit);
            }

        });
    }

    hits.sort(function(a,b){
        if( a.distance < b.distance ) return -1;
        if( a.distance > b.distance ) return 1;
        return 0;
    });

    //console.log(hits);
    if (hits.length > 0) {
        playAllSound();
    }

                $('.sound-target').css({opacity:0});
                $('.sound-target').find('*').css({opacity:0});

	$('.left-video-area .video-wrapper').hide();
	$('.left_pulmonary').show();
	

    for (var i=0; i < hits.length; i++) {
        var hit = hits[i];
        if (i==0) {
            //console.log("play:" + hit.id);
            //hit.audio.volume(hit.volume);
            hit.audio.fade(hit.audio.volume(), hit.volume, 30/*ms*/);
			$('#' + 'wrapper-' + hit.video_id).show();
			$('.left_pulmonary').hide();
			var video = $('#' + hit.video_id).get(0);
			if (video != null) {
				if (video.paused) {
					video.currentTime = 0;
					video.play();					
				}
				video.volume = hit.volume;
			}
			$('#debug-volume .value').text(hit.volume.toFixed(3));

				if (hit.core_hit) {
					var id = '#' + hit.id;
					$(id).css({opacity:1});
					$(id).find('*').css({opacity:1});
				}
        } else {
            hit.audio.fade(hit.audio.volume(), 0, 30/*ms*/);
            none_hits.push(hit);
        }
	}
	
	var pause_videos = {};
	for (var i=0; i < none_hits.length; i++) {
		var none_hit = none_hits[i];
		if (hits.length > 0 && hits[0].video_id == none_hit.video_id) continue;
		var video = $('#' + none_hit.video_id).get(0);
		if (video != null) {
			pause_videos[ none_hit.video_id] = video;
		}
	}


	for (var key in pause_videos) {
		pause_videos[key].pause();
	}

	if (hits.length==0) {
		$('#debug-volume .value').text(0);
	}

}

var current_bodymap_param = null;
function OpenBodyMap(menu_id, param) {
    current_bodymap_param = param;
    stopAllSound();
    
    $('.bodymap-frame').removeClass('type-cardio');
    $('.bodymap-frame').removeClass('type-respiratory');

    $('.bodymap-frame').addClass('type-' + param.type);
	//$('.panel-content video').hide(); /// iOSで<video>があるとその上の要素のタッチが取れない
	// イベント登録
	{
		$('#btn-bodymap-close').unbind().click(function(){
			console.log("click #btn-bodymap-close");
			CloseBodyMap();
		});


		$('#btn-bodymap-ok').unbind().click(function(){
			var val = $('input[name="question"]:checked').val();
			results[current_node.step_id][menu_id] = val;	
			CloseBodyMap();
		});


        var extended_size = 50 + 2*EXTEND_AREA_RADIUS;
        $('.cardiacsound .extended_area')
        .css({width:extended_size, height:extended_size, left:-EXTEND_AREA_RADIUS, top:-EXTEND_AREA_RADIUS});

        if (!debug) {
            //$('.cardiacsound .extended_area').hide();
            $('.extended_area').hide();
        }

		$(document).bind('mousemove touchmove', function(event) {
			//alert("touchmove");
			//$('#stethoscope .label').hide();
			//console.log("mousemove");

			if (mouseDown) {
				//event.preventDefault();

				var scope = $('#stethoscope');
				var pageX, pageY;
				if (event.type == "mousemove") {
					pageX = event.pageX;
					pageY = event.pageY;
				} else {
					pageX = event.originalEvent.touches[0].pageX; 
					pageY = event.originalEvent.touches[0].pageY; 
				}

				var dx = pageX - lastX;
				var dy = pageY - lastY;
				var offset = actualPosition(scope);//scope.position();
				var x = offset.left + dx;
                var y = offset.top + dy;
                
				//scope.css({left: x, top: y});
				lastX = pageX;
                lastY = pageY;
                
                scope.css({left: x, top: y});

                targetPlay(x, y);


			}  else {


			}

		});

		$(document).bind('mouseup touchend', function(event) {
			mouseDown = false;	
		});
	}


    $('.bodymap-front').show();
    $('.bodymap-back').hide();
	$('.bodymap-frame').fadeIn(3);
	$('.bodymap-frame .loading-wrapper').show();

    $('.bodymap').hide();
    var type = param.type;
    if (type=="cardio") type = "cardiac";
    if (type=="respiratory") type = "pulmonary";
    $('#bodymap-' + type).show();

    if ( type == "pulmonary") {
        //$('.viewable_image_area').hide();
        $('.left-panel-content').empty();
        
        var s = '<div class="left_pulmonary bodymap-front"></div>';
        var $s = $(s);
        $('.left-panel-content').append($s); 
        var src =  "./images/ui/bodymap/bodymap2_01.png";
        $s.css('background-image', 'url('+ src + ')');

        var s = '<div class="left_pulmonary bodymap-back"></div>';
        var $s = $(s);
        $('.left-panel-content').append($s); 
        var src =  "./images/ui/bodymap/bodymap2_02.png";
        $s.css('background-image', 'url('+ src + ')'); 

        $('.left-video-area').show();
        var videos = {};
        var video_no = 1;
        for(key in param.sound) {
            var stetho_area_radius = param.sound[key].r1;
            var extended_area_radius = param.sound[key].r2;
            if (param.sound[key].src==undefined) {
                stetho_area_radius = 28;
                //extended_area_radius = 50;
            }
            stetho_area_radius = 22;//

            var stetho_area_size = 2*stetho_area_radius;                
            //var extended_size = 2*extended_area_radius;
            var border_size = 3;
            var cx = $('#'+key).position().left + $('#'+key).outerWidth()/2;
            var cy = $('#'+key).position().top + $('#'+key).outerWidth()/2;
            if (key in STETHO_POSITIONS) {
                cx = STETHO_POSITIONS[key].cx;
                cy = STETHO_POSITIONS[key].cy;
            }
            cx += 50;
            cy += -50
            var offset = stetho_area_radius + border_size;

            var s = '<div class="target_area"></div>';
            var $s = $(s);
            var side = 'front';
            if (key.indexOf('front')==-1) {
                side = 'back';
            }
            var left_area_target_size = 40;
            $('.left_pulmonary.bodymap-' + side).append($s);
            $s.css({width:stetho_area_size, height:stetho_area_size, left:cx-offset, top:cy-offset});   
            if (param.sound[key].abnormal==true) $s.addClass('abnormal');


            // 動画            
            {
				var video_base = './test-contents/' + contents_type + '/video/';
                var s  = '<video preload="auto" playsinline webkit-playsinline  __muted width="100%" height="100%"></video>';
                var $s =$(s);
                var path = video_base + param.sound[key].video;
                if (path in videos) {
                    video_id = videos[path];
                } else {
                    $s.attr('src', path);
                    video_id = 'video-' + video_no;
                    $s.attr('id', video_id/*'video-' + key*/);
                    $wrapper = $('<div class="video-wrapper"></div>');
                    $('.left-video-area').append($wrapper)
					$wrapper.append($s)
					$wrapper.attr('id', 'wrapper-' + video_id/*'video-' + key*/);;
					$('.left_pulmonary.bodymap-front').show();
                    $wrapper.hide();

					var s='<div class="seeker" style="width:2px;height:100%;color:red"></div>';
					$wrapper.append(s);

                    videos[path] = video_id;
                    video_no++

					$s.get(0).play();
					//$s.get(0).volume = 0;
					$s.get(0).pause();
                }
                $('#'+ key).attr('data-video-id', video_id);
            }

        }


    }
    

		/*
		var audio_base = BASIC_CONTENTS_AUDIO_PATH;//"./contents/audio/";
		if (!isBasicStudy()) {
			audio_base = CASE_CONTENTS_AUDIO_PATH ;//+ "/" + param.type + "/";
		}
		*/
		var audio_base = './test-contents/' + contents_type + '/audio/';

			bodymap_targets = {};
			bodymap_audios[0] = {};
            bodymap_audios[1] = {};
			//if (type!="respiratory")
			loaded_audio_count = 0;
			audio_count = Object.keys(param.sound).length;

			for(key in param.sound) {
				console.log(key + ":" + param.sound[key]);
				var subdir = "";
				if (!isBasicStudy() && key != "bowel") {
					subdir = key + "/";
				}
				
                var path = audio_base + /*subdir +*/ param.sound[key].src;
                if (param.sound[key].src==undefined) {
                    path = audio_base + param.sound[key];
                }
				console.log(path);
				//var path_aiff = path.replace(".mp3", ".aif");
				bodymap_targets[key] = path;
				//var _audio = new Audio(path);

//				var id = 'bodymap_audio'

                var stetho_area_radius = param.sound[key].r1;
                var extended_area_radius = param.sound[key].r2;
                if (param.sound[key].src==undefined) {
                    stetho_area_radius = 28;
                    extended_area_radius = 50;
                }
                

                var stetho_area_size = 2*stetho_area_radius;                
                var extended_size = 2*extended_area_radius;
                var border_size = 3;
                var cx = $('#'+key).position().left + $('#'+key).outerWidth()/2;
                var cy = $('#'+key).position().top + $('#'+key).outerWidth()/2;
                if (key in STETHO_POSITIONS) {
                    cx = STETHO_POSITIONS[key].cx;
                    cy = STETHO_POSITIONS[key].cy;
                }
                var offset = extended_area_radius - stetho_area_radius + border_size;
                

                $('#'+key).css({left:cx-stetho_area_radius, top:cy-stetho_area_radius, width: stetho_area_size, height: stetho_area_size});
                $('#'+key).empty().append('<div class="extended_area"></div>');
                $('#'+key + ' .extended_area')
                .css({width:extended_size, height:extended_size, left:-offset, top:-offset});                    

				var use_html5Audio = false;
				var ua = window.navigator.userAgent.toLowerCase();
				if (ua.indexOf('android')  != -1) {
					use_html5Audio = true;
				}

				for (var i=0; i < 1; i++) {

					(function(key, i, path) {
                        var _key = key + '_' + i;
                        
                        var sound = new Howl({
                            xhrWithCredentials : false,
                            src: [path],
                            autoplay: false,
                            html5: use_html5Audio,
                            preload : true,
                            loop: false,
                            pool : 5, 
							volume: 0.0,

							onload: function(){
								console.log('Loaded! ' + this._src);
								loaded_audio_count++;
								if (loaded_audio_count==audio_count){
									$('.bodymap-frame .loading-wrapper').hide();
								}
								
                            },
                            onloaderror: function(id,err) {
                                console.log('Load error!!!!!!!!!!!!',id,err);
                            },							
							onplay: function(){
							console.log('Play!');
							$('#debug-webaudio .web_audio_api').text(this._webAudio);
								$('.left-pane video').each(function(){
									var video = $(this).get(0);
									if (video.paused) {
										//video.play();
										video.currentTime = 0.0;
									}
								});

							},
                            onend: function() {
                                console.log('Finished!');
                            }
                            });
							sound.play();
							sound.pause();


                            bodymap_audios[i][key] = sound;
 
					})(key, i, path)
				}
				//_audio = document.getElementById(key);

				//_audio.preload = true;
				
				
            }
            

			// メッセージ
			{
				var message = (param.message == undefined) ? "" : param.message;
				$('.message').text(message);
			}

			$('.btn-bodymap-reverse').removeClass('current');
			$('#btn-bodymap-front').addClass('current');


		if (debug) {
			$('.extended_area').show();
		}
}

function CloseBodyMap() {
//	stopAllSound();
    $('.left-video-area').empty();
    $('.left-video-area').hide();

	//if (current_node !=null && current_node.images) {
		//$('.panel-content').show();
		$('.panel-content').fadeIn(500);
	//}
	$('.panel-content video').show();
	$('.bodymap_audio_wrapper').empty();;

        $('.bodymap-frame').hide();
        
        // Stethoscope default position
        $('#stethoscope').css({left:"440px", top: "390px"});
        // todo
        if (!debug) {
            $('.sound-target, .sound-target *').css({opacity:0});
        }
        //if (audio != null) audio.pause();        
        audio = null;        
        currernt_audio_file = "";
        current_audio_calss = "";
        
        stopAllSound();

		bodymap_audios[0] = {};
		bodymap_audios[1] = {};

		if (timer_blink_message) {
			clearInterval(timer_blink_message);
			$('.message').hide();
			timer_blink_message = null;
		}

	$(document).unbind('mousemove touchmove');
	$(document).unbind('mouseup touchend');
}


function playAllSound(volume) {
    //console.log("playAllSound");
    if (audio_started) return;
    audio_started = true;

console.log("playAllSound");

    $('.left-pane video').each(function(){
        var video = $(this).get(0);
       //video.volume = 0;
        //video.play();
        //video.volume = 0;
    });

    for (var key in bodymap_audios[0]) {
        var audio = bodymap_audios[0][key]; 
    	audio.seek(0);
    }

    for (var key in bodymap_audios[0]) {
        var audio = bodymap_audios[0][key]; 

        //audio.volume(volume);
//        audio.volume(0);
		//audio.seek(0);
		audio.play();		
		audio.volume(0);
		audio.seek(0);
		audio.pause();
        
    }

	for (var key in bodymap_audios[0]) {
		var audio = bodymap_audios[0][key]; 
		audio.play();		
	}


	setInterval(function(){
	    for (var key in bodymap_audios[0]) {
    	    var audio = bodymap_audios[0][key]; 
			var seek = audio.seek();
            var video_id =  $('#'+key).attr('data-video-id')
			//console.log('seek: ' + key + ' ' + seek);
			var pos = 640 * seek / audio.duration();
			$('#wrapper-' + video_id + ' .seeker').css('left', pos);
			//break;
		}
	}, 30);


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
		audio.unload();
    }    
    bodymap_audios = [];

	try {
		$('audio').each(function() { /*alert(this);*/ /*alert($(this));*/ $(this).prop('me').pause(); });
		$('button.play').removeClass('playing')
	} catch(e) {
	}
}

function stopAllVideo()
{
	$('.left-video-area .video-wrapper video').each(function(){
		this.pause();
	});
}

function clickVideo(event){ 
	//alert("");
	var videoPlayer = document.getElementById('video').me;
	if (videoPlayer == null) return;
	if (videoPlayer.paused || videoPlayer.ended) {
		videoPlayer.play();
	} else {
		videoPlayer.pause();
	}
	event.preventDefault(); 
//	event.stopPropagation(); 
} 


function openResult() {
	$('#result-panel-wrapper').show();
	
	var html = "";

	//var column_prefix = current_node.step_id.toLowerCase() + "_";
	for (var i=0; i < current_node.menu.length; i++) {
		var menu = current_node.menu[i];
		if (!menu) continue;
		//console.log(menu.action);
		if (menu.action =="popup") {
			for (var j=0; j<menu.param.length; j++) {
				var sub_menu = menu.param[j];
				//console.log(sub_menu.id + ": " + sub_menu.action);
				var item_name = "";
				var model_answer = "";
				var my_answer = results[current_node.step_id][sub_menu.id];
				var executed = my_answer != null;
				var exist_my_answer = my_answer != null && my_answer!="";
				if (!my_answer) my_answer = "";
				//if (results[current_node.step_id][menu_id]) my_answer = 
				switch (sub_menu.action) {
					case "bodymap": {
					//if (sub_menu.param.answer && (sub_menu.action == "bodymap" || sub_menu.action == "bodymap")) {
						item_name = sub_menu.text;
						model_answer = sub_menu.param.answer;
	//					console.log(sub_menu.text + ": " + sub_menu.param.answer);
/*
						var column = column_prefix + "sound_" + sub_menu.param.type;
						var column_q = column + "_q";
						if (executed) {
							post_data[column] = "○";
						}
						if (exist_my_answer) {
							post_data[column_q] = (my_answer==model_answer) ? "正解" : "不正解";
						}
*/
					} break;
					case "ask": {
/*
						var column = column_prefix + "ask_" + ((j<9)?"0":"") + (j+1);
						if (executed) post_data[column] = "○";
*/
					} break;
					case "equip": {
						item_name = sub_menu.text;
						if (sub_menu.param.texts) {
							model_answer = sub_menu.param.texts.join("/");
	//						console.log(sub_menu.text + ": " + model_answer);
						} else {
							model_answer = sub_menu.param.answer;
						}
/*
						var column = column_prefix + "equip_" + DEVICES[sub_menu.param.type].id;
						if (executed) {
							post_data[column] = "○";
						}
						if (exist_my_answer) {
							if (sub_menu.param.type == "瞳孔反射") {
								var column_q = column + "_q";
								post_data[column_q] = (my_answer==model_answer) ? "正解" : "不正解";
							}
						}
*/

					} break;
					case "watch":
					case "touch":
					 {
						item_name = sub_menu.text;
						model_answer = sub_menu.param.text;
/*
						if (executed) {
							var column = column_prefix + sub_menu.action + "_" + sub_menu.param.type;
							post_data[column] = "○";
						}
*/
					} break;
					case "data":
/*
						if (executed) {
							var column = column_prefix + "data";
							post_data[column] = "○";
						}
*/
					break;
				}

				if (item_name) {
					//console.log(item_name + ": " + model_answer + " ,  " + my_answer);
					var incorrect = model_answer != my_answer;
					var my_class = "my-answer" + (incorrect ? " incorrect" : "");
					var units = { "体温計":"℃", "血圧計":"mmHg",  "パルスオキシメーター":"％", "自己血糖測定器":"mg/dL",
									 "肩":"bpm", "橈骨":"bpm", "頸動脈":"bpm", "足背":"bpm" };
					var unit = units[item_name] ? units[item_name] : "";
//					if (units[])
					html += '<tr class=""><td class="name"><div style="float:left">' + item_name +'</div><div class="unit">' + unit + '</div></td><td class="model-answer">' + model_answer + '</td><td class="' + my_class + '">' + my_answer + '</td></tr>';
				}
			}
		}
	}

//	html = '<tr class=""><td>体温</td><td class="model-answer">36.5</td><td class="my-answer">36.5</td></tr>';
	$('#result-panel table tbody').empty().append(html);


	// 解説	
	if (current_node.explanation) {
		$('#result-explanation').empty().append(current_node.explanation.replaceAll("\n", "<br />"));
	}

	// メモ 
	{
		memos[current_node.ste_id] = $('#memo').val();
		var memo_text = memos[current_node.ste_id].replaceAll("\n", "<br />");
		$('#result-memo').empty().append("【MEMO】<br />" + memo_text);
	}

}

function closeResult() {
	$('#result-panel-wrapper').hide();

	$('#result-panel table tbody').empty()
	$('#result-explanation').empty();
	$('#result-memo').empty();
}



function Round(val, precision)
{
    digit = Math.pow(10, precision);

    val = val * digit;
    val = Math.round(val);
    val = val / digit;

    return val;
}

