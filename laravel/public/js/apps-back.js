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
var CASE_LIST = {};
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
var case_start_time = -1;

var auto_forward_timer = null

var audio_count = 0;
var loaded_audio_count = 0;

var is_iOS = false;

var points = [];
var show_reload;
var lang = "";
var language = "";
var onStop = false;
var exp_modal="";
var c_id;
var arr = [];
var id;
var token;
var curr_points = 0;
var content_id = 0;
var content_title = "";
var content_point = 0;
var hide_buy_button = false;
var exec = false;
var modalError = "";
var translations = [];
var soundPoints = {};
var playedPoints = {};
var purchased_exp= {};
var activePoint = "";
var activeExp = "";
var complete_explanation = "";
var isSoundPurchased = false;
var isExplanationPurchased = false;
var disableReload = false;
var playedPoint = 0;
var unpurchasedViewedContents = "";
var hasUnpurchasedViewedContents = false;
var activeBuyButton = "";
var case_id = "";


document.addEventListener("visibilitychange", function(e) {
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
});
// exec get cases when page is ready
jQuery(($) => {
	$(document).ready(()=>{
		var urlParams = new URLSearchParams(document.location.href);
		case_id = 1 // case id
		language = "EN";
		auth = null
		doLearn(case_id,auth,lang,JSON.parse(getParameterByName('params'))) // exec when on load
	});
})

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

async function doLearn(case_id,USER_TOKEN,lang,sound) {
	function getContentNormal(){
		const bodyImage = sound.body_image_back.replace(/^.*[\\\/]/, '')
		const configuration = JSON.parse(sound.configuration)
		const soundPath = JSON.parse(sound.sound_path)
		const settings = {
			hide_reload_time: 10,
			points: {
				aptm: {
					A: {
						audito_name: soundPath.a_sound_path.replace(/^.*[\\\/]/, ''),
						position: (configuration.a.x)+"px,"+configuration.a.y+"px,"+configuration.a.r,
						volume_control: 1
					},
					P: {
						audito_name: soundPath.p_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.p.x+"px,"+configuration.p.y+"px,"+configuration.p.r,
						volume_control: 1
					},
					T: {
						audito_name: soundPath.t_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.t.x+"px,"+configuration.t.y+"px,"+configuration.t.r,
						volume_control: 1
					},
					M: {
						audito_name: soundPath.m_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.m.x+"px,"+configuration.m.y+"px,"+configuration.m.r,
						volume_control: 1
					},
				},
				heart: {
					H1: {
						audito_name: soundPath.h1_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.m.x+"px,"+configuration.m.y+"px,"+configuration.m.r,
						volume_control: 1
					},
					H2: {
						audito_name: soundPath.h2_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.m.x+"px,"+configuration.m.y+"px,"+configuration.m.r,
						volume_control: 1
					},
					H3: {
						audito_name: soundPath.h3_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.m.x+"px,"+configuration.m.y+"px,"+configuration.m.r,
						volume_control: 1
					},
					H4: {
						audito_name: soundPath.h4_sound_path.replace(/^.*[\\\/]/, ''),
						position: configuration.m.x+"px,"+configuration.m.y+"px,"+configuration.m.r,
						volume_control: 1
					}
				},
			}
		}
		return new Promise((resolve, reject)=>{
			let data = {
				"status":"ok",
				"message":"",
				"user":1,
				"reload":"show",
				"current_points":0,
				"translations":[
					{"id":97,"key":"yes","jp":"\u306f\u3044","en":"Yes","created_at":"2019-06-26 06:41:18","updated_at":"2019-06-26 06:41:18","deleted_at":null},
					{"id":98,"key":"no","jp":"\u3044\u3044\u3048","en":"No","created_at":"2019-06-26 06:44:39","updated_at":"2019-06-26 06:44:39","deleted_at":null},
					{"id":147,"key":"notEnoughPoints","jp":"\u30b3\u30a4\u30f3\u304c\u4e0d\u8db3\u3057\u3066\u3044\u307e\u3059","en":"Not enough coins","created_at":"2019-07-25 23:55:00","updated_at":"2019-10-15 07:21:18","deleted_at":null},
					{"id":174,"key":"buy","jp":"\u8cfc\u5165\u3059\u308b","en":"Buy","created_at":"2019-12-13 10:52:49","updated_at":"2019-12-13 10:52:49","deleted_at":null},
					{"id":175,"key":"buySoundPointDialog","jp":"\u30b3\u30a4\u30f3\u3067\u97f3\u6e90A\u3001P\u3001T\u3001M\u3092\u8cfc\u5165\u3057\u3066\u3082\u3088\u308d\u3057\u3044\u3067\u3059\u304b\uff1f","en":"Are you sure you want to buy sound source A, P, T, and M with","created_at":"2019-12-13 10:53:09","updated_at":"2019-12-13 10:53:09","deleted_at":null},
					{"id":176,"key":"buyExplanationDialog","jp":"\u30b3\u30a4\u30f3\u3067A\u3001P\u3001T\u3001\u304a\u3088\u3073M\u306e\u8aac\u660e\u3092\u8cfc\u5165\u3057\u3066\u3082\u3088\u308d\u3057\u3044\u3067\u3059\u304b\uff1f","en":"Are you sure you want to purchase A, P, T and M explanations for","created_at":"2019-12-13 10:53:26","updated_at":"2019-12-13 10:53:26","deleted_at":null}
				],
				"unpurchasedViewedContents":[],
				"result":[
					{
						"id":1,
						"title": sound.title,
						"description": sound.description,
						"description_image":"912.png",
						"settings": settings,
						"created_at":"2019-04-25 10:47:00",
						"updated_at":"2021-02-15 17:13:35",
						"deleted_at":null,"content_group":1,
						"title_en":"Normal Heart Sound",
						"description_en":"Please use a Kikuzo speaker or earphones to listen sounds. Refresh when a heart beat is out of sync.",
						"description_image_en":"6bwIwVXwBVPtqPe7nNim.png",
						"sort":1,
						"body_image": bodyImage,
						"explanation":"1: 画面の前胸部イラスト上で、大動脈弁部位を指先で軽くタップするとチェストピース・アイコンがその部位に来ます。2: 大動脈弁部位で心音は、S1＜S2 と聴こえてきます。S2 はイラストで分かるように大動脈弁と肺動脈弁が殆ど同時に閉鎖する時相に一致して発生する音で、その振動音が体表面に伝わった音がS2 です。3: 心音疑似法（Cardiophonetics）では、da TA という音に類似します。4: 次に指先を肺動脈弁部位に動かすと、やはりS1＜S2 の関係で、前の大動脈弁部位で聴いた心音と殆ど変わらない音da TA という音になります。5: 次に指先を三尖弁部位に動かして下さい。S1 とS2 の関係が心基部（大動脈弁部位と肺動脈弁部位）とは逆にS1＞S2 に変わりました。心音疑似法では、Da ta となります。",
						"explanation_en":"1: S1\uff1cS2 at A and P. S1\uff1eS2 at T and M.(Phonocardiogram (PCG) of Normal Heart Sound)\r\n2: No abnormal or extra sounds at auscultation sites heard through the earphones (you can hear \u201cKIKUZO\u201d Auscultation speaker), you can hear heart sounds clearly. S1 is a sound developed by closure of mitral and tricuspid valves at almost the same timing. S2 is a sound developed by closure of A and P valves at almost the same timing, and these vibrations are transmitted to surface of chest wall.\r\n3: Cardiophonetics is \u201cda TA\u201d at A.\r\n4: Then move your finger to P, you can also hear S1\uff1cS2.\r\n5: The move your finger to T. S1\uff1eS2.\r\n6: Cardiophonetics is \u201cDA ta\u201d\r\n7: Finally, when your fingertip is moved to M, S1\uff1eS2 is heard, same as T. (For illustrations, see the Ausculaide Guidebook.)",
						"purchases":[],
						"content_group": 1,
						"points": 0,
						"sort": 1,
					},
				],
			}
			let results = []
			const newData = {}
			curr_points = data.current_points
			data.translations.forEach((v,k)=>{
				translations.push(v.en,v.jp)
			});

			data.result.forEach((v,k)=>{
				var doc = v.settings;
				var sound = doc.points;
				console.log(sound)
				var show_reload = data.reload;
				var hide_reload_time = doc.hide_reload_time;
				var sound_position_A = sound.aptm.A.position.split(",");
				var sound_position_P = sound.aptm.P.position.split(",");
				var sound_position_T = sound.aptm.T.position.split(",");
				var sound_position_M = sound.aptm.M.position.split(",");
				var sound_position_H1 = sound.heart.H1.position.split(",");
				var sound_position_H2 = sound.heart.H2.position.split(",");
				var sound_position_H3 = sound.heart.H3.position.split(",");
				var sound_position_H4 = sound.heart.H4.position.split(",");
				var title="";
				var description="";
				var description_image="";
				var body_image = v.body_image;
				var explanation="";
				var content_id = v.id;
				var content_points = v.points;
				id = data.user;
				unpurchasedViewedContents = data.unpurchasedViewedContents;
				if(lang == "EN"){
					title = v.title_en
					description = v.description_en
					description_image = v.description_image_en
					explanation = v.explanation_en
					lang = "EN";
				}else{
					title = v.title
					description = v.description
					description_image = v.description_image
					explanation = v.explanation
				}

				newData[v.id] = {
					content_id : content_id,
					content_points : content_points,
					type : v.content_group,
					title : title,
					// aptm
					aleft: sound_position_A[0],
					atop: sound_position_A[1],
					pleft: sound_position_P[0],
					ptop: sound_position_P[1],
					tleft: sound_position_T[0],
					ttop: sound_position_T[1],
					mleft: sound_position_M[0],
					mtop: sound_position_M[1],
					// heart
					h1left: sound_position_H1[0],
					h1top: sound_position_H1[1],
					h2left: sound_position_H2[0],
					h2top: sound_position_H2[1],
					h3left: sound_position_H3[0],
					h3top: sound_position_H3[1],
					h4left: sound_position_H4[0],
					h4top: sound_position_H4[1],

					show_reload : show_reload,
					hide_reload_time : hide_reload_time,
					explanation : explanation,
					purchases : v.purchases,
					has_question: false,
					enable_memo: false,
					nodes : {
						001 : {
							avator: "dr_tokunaga",
							avator_message: "",
							back: null,
							forward: null,
							hide_step_buttons: true,
							images: [
								{type: "image", src: SITEURL+"/img/stetho_sound_images/"+description_image},
								{type: "image", src: SITEURL+"/img/library_images/"+body_image},
							],
							text: 
								"\n\n" +description ,
								// title + "\n\n" +description ,
							menu: [
								{ text: "📢①Normal Heart Soundを聴く", action:"bodymap", param:{
										type: "cardio",
										sound: {
											cardiacsoundA: { src:sound.aptm.A.audito_name, r1:20, v1:0.8, r2:sound_position_A[2], v2:0.3, volume_control: sound.aptm.A.volume_control},
											cardiacsoundP: { src:sound.aptm.P.audito_name, r1:20, v1:0.8, r2:sound_position_P[2], v2:0.3, volume_control: sound.aptm.P.volume_control},
											cardiacsoundT: { src:sound.aptm.T.audito_name, r1:20, v1:0.8, r2:sound_position_T[2], v2:0.3, volume_control: sound.aptm.T.volume_control},
											cardiacsoundM: { src:sound.aptm.M.audito_name, r1:20, v1:0.8, r2:sound_position_M[2], v2:0.3, volume_control: sound.aptm.M.volume_control},
										},
										message: "",
									}
								},
							],
						}
					}
				}
			})
			resolve(newData)
		})
	}
	
	await getContentNormal().then((res)=>{
		
		CASE_LISTS = res
		CASE_LIST = CASE_LISTS
		if (user_attr == null) {
			$('#user-attribute-wrapper').fadeIn();
		} else {
			_doLearn(case_id);
		}
		// (function(case_id) {
		// 	$('#btn-usr-ok').unbind("click");
		// 	$('#btn-usr-ok').bind("click",
		// 		function() {
		// 			var job =  $('input[name="job"]:checked').val();
		// 			//if (!(job)) job = null;
		// 			var otc_check = $('input[name="otc_check"]').prop('checked');
		// 			var athome_check = $('input[name="athome_check"]').prop('checked');
		// 			var otc =  $('input[name="otc"]:checked').val();
		// 			var athome =  $('input[name="athome"]:checked').val();
		// 			var year6 =  $('input[name="6year"]:checked').val();
		// 			var year4 =  $('input[name="4year"]:checked').val();
		// 			var usr_other_pharmacist_text =  $('input[name="usr_other_pharmacist_text"]').val();
		// 			var other_student_text =  $('input[name="other_student_text"]').val();
		// 			var other_text =  $('input[name="other_text"]').val();

		// 			if((!job && !otc_check && !athome_check) ||
		// 				(otc_check && otc==null) ||
		// 				(athome_check && athome ==null) ||
		// 				(job=="調剤薬局" && otc==null && athome==null) || 
		// 				(job=="調剤薬局" && (( otc==null) || (athome==null))) || 
		// 				(job=="その他の薬剤師" && usr_other_pharmacist_text=="") ||
		// 				(job=="6年制薬学生" && year6==null) || 
		// 				(job=="4年制薬学生" && year4==null) ||
		// 				(job=="その他の学生" && other_student_text=="") ||
		// 				(job=="その他" && other_text=="")
		// 			) {

		// 				alert("該当する属性を選んでください");
		// 				if (job=="その他の学生") $('input[name="other_student_text"]').focus();
		// 				return;
		// 			}

		// 			{
		// 				user_attr = {};
		// 				if (otc_check && athome_check) {
		// 					user_attr = "薬剤師－調剤薬局－OTC－"+ otc + "－在宅－" + athome;
		// 				} else 	if (otc_check) {
		// 					user_attr = "薬剤師－調剤薬局－OTC－"+ otc;
		// 				} else if (athome_check) {
		// 					user_attr = "薬剤師－調剤薬局－在宅－" + athome;
		// 				} else {
		// 					switch (job) {
		// 						case "病院":
		// 						case "ドラッグストア（調剤部門なし）":
		// 							user_attr = "薬剤師－" + job
		// 							break;
		// 						case "その他の薬剤師":
		// 							user_attr = "薬剤師－その他の薬剤師";
		// 							user_attr_sub = "－" + usr_other_pharmacist_text;
		// 						break;
		// 						case "6年制薬学生":
		// 							user_attr = "学生－6年制薬学生－" + year6;
		// 							break;
		// 						case "4年制薬学生":
		// 							user_attr = "学生－4年制薬学生－" + year4;
		// 							break;
		// 						case "その他の学生":
		// 							user_attr = "学生－その他の学生";
		// 							user_attr_sub = "－" + other_student_text;
		// 						break;
		// 						case "登録販売者":
		// 							user_attr = "その他－登録販売者";
		// 						break;
		// 						case "その他":
		// 							user_attr = "その他－その他";
		// 							user_attr_sub = "－" + other_text;
		// 						break;
		// 					}
		// 				}

		// 				$('#user-attribute-wrapper').hide();
		// 				_doLearn(case_id);
		// 			}
		// 		}
		// 	);
		// 	// viewImage()
		// })(case_id);
		return false;
	}).catch((err)=>{
		// err = (err.error) ? err.error : err.status + ": Session expired. Please re-login"
		err = (err.error) ? err.error : modalError
		if(err.length == 0 || err == ""){
            console.log(err)
        }else{
        	$(".modal-body").html("<h1 class='text-center' style='margin:0;color:red'>"+err+"</h1>")
        	$("#myModal").modal('show');
        }
	})
}

function setSoundPoints(params){
	$('.cardiacsoundA').css({
		'left': params.a_left,
		'top': params.a_top,
	});
	$('.cardiacsoundP').css({
		'left': params.p_left,
		'top': params.p_top,
	});
	$('.cardiacsoundT').css({
		'left': params.t_left,
		'top': params.t_top,
	});
	$('.cardiacsoundM').css({
		'left': params.m_left,
		'top': params.m_top,
	});

}

function resetSoundPoints(orientation){

	var aleft = parseInt(points[0],10);
	var atop = parseInt(points[1],10);
	var pleft = parseInt(points[2],10);
	var ptop = parseInt(points[3],10);
	var tleft = parseInt(points[4],10);
	var ttop = parseInt(points[5],10);
	var mleft = parseInt(points[6],10);
	var mtop = parseInt(points[7],10);

	if(orientation == "landscape"){
		const params = {
			a_left: (aleft) + "px",
			a_top: (atop) + "px",
			p_left: (pleft) + "px",
			p_top: (ptop) + "px",
			t_left: (tleft) + "px",
			t_top: (ttop) + "px",
			m_left: (mleft) + "px",
			m_top: (mtop) + "px",
		}
		setSoundPoints(params);
		console.log("l", params)
	   	setTimeout(function(){
			$('#btn-case-top').trigger("click");
		},100);
	}else if(orientation == "portrait"){
		const params = {
			a_left: aleft,
			a_top: atop,
			p_left: pleft,
			p_top: ptop,
			t_left: tleft,
			t_top: ttop,
			m_left: mleft,
			m_top: mtop,
		}
		setSoundPoints(params);
		console.log("p", params)
		setTimeout(function(){
			$('#btn-case-top').trigger("click");
		},100);
	}
}

function viewImage(){
	jQuery(($)=>{
		const arrayClicable = [".panel-content",".viewable_image"];
		$.each(arrayClicable ,function(k,v){
			$(v).click(function(){
				var getImg = $(".viewable_image").attr("src")
				$('#modal-img').attr('src',getImg);
				$('#content-explanation').html(exp_modal);
				if(language == "EN"){
					$('.modal-buy').html(translations[6]);
					$('.confirm-details').html(translations[8] + " " + content_point + " coins?");
					$('#btn-yes').html(translations[0]);
					$('#close').html(translations[2]);
				}else{
					$('.modal-buy').html(translations[7]);
					$('.confirm-details').html( content_point+ " "+translations[9]);
					$('#btn-yes').html(translations[1]);
					$('#close').html(translations[3]);
				}

				$("#myModal").modal('show');
				$('#snackbar-front').attr('style', 'visibility: hidden !important');
				$('.modal-backdrop').css('width','100vw');
				$(".main_wrapper").css({
					'overflow' : 'hidden',
					'position' : 'relative',
					'touch-action' : 'none'
				});
				$(".modal").css('width',$(window).width());
				$(".modal-dialog ").css('cssText', "max-width: " +$(window).width()+ 'px !important;');
				$(".main_wrapper").attr("ontouchmove", "");
				$(".panel-content-wrapper").attr("ontouchstart", "");
				$("html").css({
					'overflow-y' : 'scroll',
					'position' : 'fixed',
					'width' : '100%',
					'left' : '0px',
					'top' : '0px'
				});
			})
		})
		
		$('#myModal').on('hide.bs.modal', function(e) {
			/*var style="transform: scale(1); transform-origin: 50% 0px; width: 100%;";
			$("body").attr("style", style);*/
			if(is_iOS){
				$(".main_wrapper").css({'overflow':'hidden','position' : 'fixed','height' : '100%'});
			}else{
				$(".main_wrapper").css('overflow','hidden');
			}
			//$(".main_wrapper").attr("ontouchmove", "event.preventDefault()");
			$(".panel-content-wrapper").attr("ontouchstart", "event.preventDefault()");
			$("html").css({
				'overflow-y' : '',
				'position' : '',
				'width' : '',
				'left' : '',
				'top' : ''
			});
			//window.location = window.location.href;
		});
		
	})
}

function enablePinch(){
	if(is_iOS){
		$('.main_wrapper').css({
			'overflow': 'hidden',
			'position': 'fixed',
			'height': '100%'
		});
	}else{
		$('.main_wrapper').css({
			//'overflow': 'visible'
		});
	}
}

function isIphoneX(){
	var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

	var ratio = window.devicePixelRatio || 1;

	var screen = {
		width : window.screen.width * ratio,
		height : window.screen.height * ratio
	};

	if (iOS && screen.width == 1125 && screen.height === 2436) {
		return true;
	}else{
		return false;
	}
}

function _doLearn(case_id) {
	results = {};
	memos = {};
	//if (Case==undefined) { alert("症例学習データが未定義です。\nid = " + case_id); return; }
	current_case = CASE_LIST[case_id];
	content_title = current_case.title;
	c_id = case_id;
	content_point = current_case.content_points;
	var Case = CASE_LIST[case_id];
	var explanation = current_case.explanation;
	var data="";
	var boughtPoints = current_case.purchases;
	// var boughtExp = current_case.bought_exp;
	var source_points = ['A', 'P', 'T', 'M'];
	var content = "";
	var viewed_contents = unpurchasedViewedContents.length != 0 ? jsyaml.load(unpurchasedViewedContents)[0]['unpurchased_viewed_contents'] : [];

	if(viewed_contents.length != 0){
		hasUnpurchasedViewedContents = true;
		if(viewed_contents.toString().match(',')){
			arr = viewed_contents.split(",");
		}else{
			arr.push(viewed_contents);
		}
		var exist = arr.find(element => element == case_id); 
		if(exist){
			playedPoint = 1;
		}
	}

	content_id = current_case.content_id;
	case_start_time = (new Date()).getTime();
	show_reload = current_case.show_reload;
	complete_explanation = "<pre>" + explanation + "</pre>";

	source_points.forEach((v)=>{
		soundPoints[v] = "0";
		playedPoints[v] = "0";
		purchased_exp[v] = "0";
	});

	if(boughtPoints.length != 0 || content_point == 0){
		isSoundPurchased = true;
		isExplanationPurchased = true;
		playedPoint = 0;
	}else{
		$('#btn-case-top').hide();
	}

	if(isExplanationPurchased){
		exp_modal = "<pre>" + explanation + "</pre>";
		$('.modal-buy').hide();
	}else{
		exp_modal = "<pre>" + explanation.substring(0,120) + " ..." + "</pre>";
		$('.modal-buy').show();
	}

	// set button text translations
	if(language == "EN"){
		$('#snackbar').html(translations[4]);
		$('.sound-source-buy').html(translations[6]);
	}else{
		$('#snackbar').html(translations[5]);
		$('.sound-source-buy').html(translations[7]);
	}
	console.log(current_case,"cccucucucu")
	const params = {
		a_left: current_case.aleft,
		a_top: current_case.atop,
		p_left: current_case.pleft,
		p_top: current_case.ptop,
		t_left: current_case.tleft,
		t_top: current_case.ttop,
		m_left: current_case.mleft,
		m_top: current_case.mtop,
	}
	setSoundPoints(params);
	console.log("m",params)
	points = [current_case.aleft,current_case.atop,current_case.pleft,current_case.ptop,current_case.tleft,current_case.ttop,current_case.mleft,current_case.mtop];
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
// console.log(src);
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

	// var is_iOS = false;
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
/*console.log('window.innerWidth: ' + window.innerWidth);
console.log('window.innerHeight: ' + window.innerHeight);
console.log('view_width: ' + view_width);
console.log('scale: ' + scale);*/

if (forceScale!=null) scale = forceScale;
//scale *= 0.6;
//scale = 0.2;
//alert("width:" + screenWidth + ","+ screen.height + "; view_width:" + view_width + "; scale:" +  scale);
//scale = 0.5;
//view_width +=10;


	var viewport_content = '' ;

	if (is_iPhonePlus && Math.abs(window.orientation) === 90) {
		//scale *= 0.95;
	}
//	$("meta[name='viewport']").attr('content', "width=device-width,initial-scale="+scale );
		if (is_iPhone && !is_iPhonePlus && major_version <= 11 ) {
//view_width = 640; scale=0.5;
			//viewport_content =  'width='+Math.round(view_width) + ', _height=' + view_height + ',shrink-to-fit=no, _initial-scale=' + scale +  '' ;

		} else {
			//viewport_content =  'width='+Math.round(view_width) + ', _height=' + view_height + ', _shrink-to-fit=no, initial-scale=' + scale +  '' ;					
		}

		// iPhoneSE initial-sacale なし
		// iPhone7 initial-sacale なし
		// iPhone6 Plus initial-sacale あり
		// iPhone X initial-sacale ?
		// iPhone XS initial-sacale ?
		// iPhone XS Max initial-sacale ?
		// iPhone XR initial-sacale  あり
//return;
		$("meta[name='viewport']").attr('content', viewport_content);
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

	window.onload = function(e){
		setTimeout(function(){
			if (window.matchMedia("(orientation: landscape)").matches) {
				resetSoundPoints("landscape");
				if(screen.width<760){
					$('.bodymap-frame').css('right','-3%');
					$('.button-area').css('width','90%');
				}
			}
			if (window.matchMedia("(orientation: portrait)").matches) {
				resetSoundPoints("portrait");
			}
			
			if(is_iOS){
				$(".main_wrapper").attr("ontouchmove", "event.preventDefault()");
				$(".panel-content-wrapper").attr("ontouchstart", "event.preventDefault()");
			}
		},500);
		setTimeout(function() {
			setupViewport(true);
		}, 100);
	}

	$(window).on('orientationchange', function(){
		
		if (window.matchMedia("(orientation: landscape)").matches) {
			resetSoundPoints("portrait");
			$('.bodymap-frame').css('right','');
		}
		if (window.matchMedia("(orientation: portrait)").matches) {
			resetSoundPoints("landscape");
			$('.bodymap-frame').css('left',"");

			if(screen.width<760){
				$('.bodymap-frame').css('right','-3%');
			}
		}

		setTimeout(function() {
		//	setupViewport(0.1);
			//setupViewport(true);
		}, 1500);

		setTimeout(function() {
			setupViewport(true);
		}, 50)

		// location.reload();

	});

	$(window).on('resize', function(){
		// console.log('resize');
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
	var left = 0;
	var top = 0;

	$('#btn-home').bind("click",
				function() {
					gotoHome();
				}
	);

	$('#btn-case-top').bind("click",
				function() {
					hideSnackbarFront();
					gotoCaseTop();
				}
	);

	$('#btn-point-A').bind("click",
				function() {
					hideSnackbarFront();
					if (window.matchMedia("(orientation: portrait)").matches) {
					   	left = parseInt(points[0],10);
					   	top = parseInt(points[1],10);
					}
					if (window.matchMedia("(orientation: landscape)").matches) {
						left = parseInt(points[0],10);
					   	top = parseInt(points[1],10);
					}
					activePoint = "A";
					playToPoint(left,top);
					setConditions(activePoint);

				}
	);

	$('#btn-point-P').bind("click",
				function() {
					hideSnackbarFront();
					if (window.matchMedia("(orientation: portrait)").matches) {
					   	left = parseInt(points[2],10);
					   	top = parseInt(points[3],10);
					}
					if (window.matchMedia("(orientation: landscape)").matches) {
						left = parseInt(points[2],10);
					   	top = parseInt(points[3],10);
					}
					activePoint = "P";
					playToPoint(left,top);
					setConditions(activePoint);

				}
	);

	$('#btn-point-T').bind("click",
				function() {
					hideSnackbarFront();
					if (window.matchMedia("(orientation: portrait)").matches) {
					   	left = parseInt(points[4],10);
					   	top = parseInt(points[5],10);
					}
					if (window.matchMedia("(orientation: landscape)").matches) {
						left = parseInt(points[4],10);
					   	top = parseInt(points[5],10);
					}
					activePoint = "T";
					playToPoint(left,top);
					setConditions(activePoint);

				}
	);

	$('#btn-point-M').bind("click",
				function() {
					hideSnackbarFront();
					if (window.matchMedia("(orientation: portrait)").matches) {
					   	left = parseInt(points[6],10);
					   	top = parseInt(points[7],10);
					}
					if (window.matchMedia("(orientation: landscape)").matches) {
						left = parseInt(points[6],10);
					   	top = parseInt(points[7],10);
					}
					activePoint = "M";
					playToPoint(left,top);
					setConditions(activePoint);

				}
	);

	$('#close').bind("click",function(){
		$("#myModal").css('z-index','1050');
	});

	$('#confirmModal').on('hide.bs.modal', function(e) {
		$("#myModal").css('z-index','1050');
	});

	$('.modal-buy').bind("click",function(){
		$("#buy-sound-btn-yes").hide();
		$("#btn-yes").show();
		$("#confirmModal").modal('show');
		$("#myModal").css('z-index','100');
	});

	$('.sound-source-buy').bind("click",function(){
		if(language == "EN"){
			$('.confirm-details').html(translations[10].replace(/%s/g, activePoint) + " " + content_point + " coins?");
			$('#buy-sound-btn-yes').html(translations[0]);
			$('#close').html(translations[2]);
		}else{
			$('.confirm-details').html( content_point + " "+translations[11].replace(/%s/g, activePoint));
			$('#buy-sound-btn-yes').html(translations[1]);
			$('#close').html(translations[3]);
		}
		$("#buy-sound-btn-yes").show();
		$("#btn-yes").hide();
		$("#confirmModal").modal('show');
		$("#myModal").css('z-index','100');
		$(".main_wrapper").css('position','relative');
		activeBuyButton = "sound_source";
	});

	$('#confirmModal').on('hide.bs.modal', function(e) {
		if(activeBuyButton == "sound_source"){
			$(".main_wrapper").css('position','fixed');
			activeBuyButton = "";
		}else{
			$(".main_wrapper").css('position','relative');
		}
	});

	$('#buy-sound-btn-yes').bind("click",function(){
		if(curr_points < content_point){
			setSnackbar("snackbar"); return false;
		}

		var today = new Date();
		var day = today.getFullYear()+'.'+(today.getMonth()+1)+'.'+today.getDate();
		var time = today.getHours() + "." + today.getMinutes() + "." + today.getSeconds();
		var use_log = "- time : " + day + " " + time +  "\n" +
					  "- title : " + content_title + "\n" +
					  "- points :" + content_point + "\n" +
					  "- contents_id : " + content_id;
			$( "#buy-sound-btn-yes").unbind( "click" );
			$.ajax({
				url: SITEURL + "/api/point_history",
				type: 'POST',
				headers: {
					"Authorization": token
				},
				data:{
					'params[user_id]' : id,
					'params[points]' : content_point,
					'params[use_log]' : use_log,
					'params[use_contents_id]' : content_id,
					'params[point_type]' : '4'
				},
				success: (data) => {
					isSoundPurchased = true;
					isExplanationPurchased = true;
					exp_modal = complete_explanation;
					$('#snackbar-front').html("");
					$('#btn-case-top').show();
					$('#close').trigger("click");
					$('#btn-point-'+activePoint).trigger("click");
					$('#snackbar-front').css('visibility', 'hidden');
					$('#content-explanation').html(exp_modal);
					$('.modal-buy').hide();
				},
				error: () => {
					console.log("error");
				}
			});
		
	});

	$('#btn-yes').bind("click",function(){
		if(curr_points < content_point){
			setSnackbar("snackbar"); return false;
		}

		var today = new Date();
		var day = today.getFullYear()+'.'+(today.getMonth()+1)+'.'+today.getDate();
		var time = today.getHours() + "." + today.getMinutes() + "." + today.getSeconds();
		var use_log = "- time : " + day + " " + time +  "\n" +
					  "- title : " + content_title + "\n" +
					  "- points :" + content_point + "\n" +
					  "- contents_id : " + content_id;
		$( "#btn-yes").unbind( "click" );
		$.ajax({
			url: SITEURL + "/api/point_history",
			type: 'POST',
			headers: {
				"Authorization": token
			},
			data:{
				'params[user_id]' : id,
				'params[points]' : content_point,
				'params[use_log]' : use_log,
				'params[use_contents_id]' : content_id,
				'params[point_type]' : '3'
			},
			success: (data) => {
				exp_modal = complete_explanation;
				$('#btn-case-top').show();
				$('#close').trigger("click");
				$('#content-explanation').html(exp_modal);
				$('.modal-buy').hide();
				isExplanationPurchased = true;
				isSoundPurchased = true;
				$('.main_wrapper').css('position','relative');
			},
			error: () => {
				console.log("error");
			}
		});
	});

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
                var orientation = "";
                if (window.matchMedia("(orientation: portrait)").matches) {
                	orientation = "portrait";
				}else{
                	orientation = "landscape";
				}
                //"easeOutQuad";
				if (true) {
	                if(orientation=="portrait" ? x<70 || x>window.innerWidth || y<30 || y>590 : x<22 || x>590 || y<4 || y>425){
						event.preventDefault();
					}else{
						scope.animate({left: x, top: y}, duration, easing,function(){
	                    	targetPlay(x, y);
		                });
	                    // setTenSecondsTimeout();
					}
				} else {
	                if(orientation=="portrait" ? x<70 || x>window.innerWidth || y<30 || y>590 : x<22 || x>590 || y<4 || y>425){
						event.preventDefault();
					}else{
	                	scope.css({left: x, top: y});
	                    targetPlay(x, y);
                    	// setTenSecondsTimeout();
	                }
				}
                

				lastX = pageX;
                lastY = pageY;

                return true;
		});

		$('#stethoscope').bind('mousedwn touchstart', function(event) {
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

function updateUnpurchasedViewedContents(){
	if(!exec){
		arr.push(c_id);
		if(hasUnpurchasedViewedContents){
			$.ajax({
				url: SITEURL + "/api/updateUnpurchasedViewedContents",
				type: 'POST',
				headers: {
					"Authorization": token
				},
				data:{
					'params[use_log]' : "- unpurchased_viewed_contents : "+arr.toString()
				},
				success: (data) => {

				},
				error: () => {
					console.log("error");
				}
			});
		}else{
			$.ajax({
				url: SITEURL + "/api/point_history",
				type: 'POST',
				headers: {
					"Authorization": token
				},
				data:{
					'params[user_id]' : id,
					'params[points]' : 0,
					'params[use_log]' : "- unpurchased_viewed_contents : "+arr.toString(),
					'params[point_type]' : '5'
				},
				success: (data) => {

				},
				error: () => {
					console.log("error");
				}
			});
		}
		
	}
}

function playToPoint(x,y){
	var scope = $('#stethoscope');
	var easing = "linear";
	var duration = 500;

	scope.animate({left: x, top: y}, duration, easing,function(){
		if(playedPoint == 0 || isSoundPurchased){
			targetPlay(x, y);
		}else{
			targetPlay(0, 0);
		}
    });
}

function setConditions(soundPoint){
	if(isSoundPurchased == false && playedPoint == 0){
		setTenSecondsTimeout();
	}else if(isSoundPurchased == false && playedPoint == 1){
		showSnackbarFront();
		$('#snackbar-front').css('visibility', 'visible');
	}else{
		hideSnackbarFront();
		$('#snackbar-front').css('visibility', 'hidden');
	}
}

function setSnackbar(snackValue){
	var x = document.getElementById(snackValue);
	x.classList.add("show");
	if(snackValue == "snackbar"){
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	}
}

function showSnackbarFront(){
	var x = document.getElementById('snackbar-front');
	x.classList.add("show");
}

function hideSnackbarFront(){
	var x = document.getElementById("snackbar-front");
	if(x.classList.contains('show')){
		x.className = x.className.replace("show", "");
	}
	$('#snackbar-front').css('visibility', 'hidden');
}


function setTenSecondsTimeout(){
	setTimeout(function() {
		playedPoint = 1;
	    targetPlay(0,0);
		updateUnpurchasedViewedContents();
		exec = true;
	}, 10000);

	setTimeout(function(){
		setSnackbar("snackbar-front");
	}, 11000);
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
	console.log("nonono",node_data)
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
		// console.log("nnodeData",current_node)
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
		var video_base = './body-contents/' + contents_type + '/video/';
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
                        var s = '<img class="viewable_image" src="' + src + '"/>';
                        s += '<div class="" style=""></div>'
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
			console.log("baseweqeqwewqewqe",BASIC_CONTENTS_AUDIO_PATH)
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
	return '';
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

	/*if(onStop){
    	x = 0;
    	y = 0;
    }*/
    if(x!=0 && y!=0){
		if(x > 271 && x < 412 && y >208 && y< 358){
			hideSnackbarFront();
			activePoint = "A";
			if(playedPoint == 1 && isSoundPurchased == false){
				x =0;y=0;
			}
			setConditions(activePoint);
		}else if(x > 415 && x < 598 && y > 213 && y < 343){
			hideSnackbarFront();
			activePoint = "P";
			if(playedPoint == 1 && isSoundPurchased == false){
				x =0;y=0;
			}
			setConditions(activePoint);
		}else if(x > 396 && x < 622 && y > 345 && y < 505){
			hideSnackbarFront();
			activePoint = "T";
			if(playedPoint == 1 && isSoundPurchased == false){
				x =0;y=0;
			}
			setConditions(activePoint);
		}else if(x > 520 && x < 678 && y > 412 && y < 562){
			hideSnackbarFront();
			activePoint = "M";
			if(playedPoint == 1 && isSoundPurchased == false){
				x =0;y=0;
			}
			setConditions(activePoint);
		}else{
			hideSnackbarFront();
			$('#snackbar-front').css('visibility', 'hidden');
		}
	}

	if(playedPoint == 1){
		x = 0;
		y = 0;
	}

	// if(playedPoint == 1){
	// 	x = 0;
	// 	y = 0;
	// }
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
			// console.log("distance: "  + distance);
			
            if (distance <= extended_target_radius ) {
                
                extended_hit = true;
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
                var volume_control = current_bodymap_param.sound[target].volume_control;
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
                        // var volume = 1 - distance / r2;
                        volume = Math.abs(volume - (1-volume_control)) + 0.05;
                        volume_control==0 ? volume=0 : volume;
                        newVolume = Math.min(volume, 1.0);
                    }
                } else {
                    //newVolume = 0.3;
                    var volume = (v1-v2) * (extended_target_radius - distance ) / (extended_target_radius - target_radius + scope_radius) + v2;
                    volume = Math.abs(volume - (1-volume_control)) + 0.05;
                    volume_control==0 ? volume=0 : volume;
                    newVolume = Math.min(volume, 1.0);
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
            }

            if (distance <= target_radius ) {
                hit = true;
                

//                $(this).css({opacity:1});
//                $(this).find('*').css({opacity:1});


            } else {
                if (!debug) {
                    $(this).css({opacity:0});
                    $(this).find('*').css({opacity:0});
                }
            }

            if (!extended_hit) {
        
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
                var orientation = "";
				//scope.css({left: x, top: y});
				lastX = pageX;
                lastY = pageY;
                
                if (window.matchMedia("(orientation: portrait)").matches) {
                	orientation = "portrait";
				}else{
                	orientation = "landscape";
				}

                if(orientation=="portrait" ? x<70 || x>window.innerWidth || y<30 || y>590 : x<22 || x>590 || y<4 || y>425){
					event.preventDefault();
				}else{
                	scope.css({left: x, top: y});
            	}
                targetPlay(x, y);
                // setTenSecondsTimeout();
                

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
				var video_base = './contents/' + contents_type + '/video/';
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
		var audio_base = SITEURL+'/audio/stetho_sounds/';
			
			bodymap_targets = {};
			bodymap_audios[0] = {};
            bodymap_audios[1] = {};
			//if (type!="respiratory")
			loaded_audio_count = 0;
			audio_count = Object.keys(param.sound).length;

			for(key in param.sound) {
				// console.log(key + ":" + param.sound[key]);
				var subdir = "";
				if (!isBasicStudy() && key != "bowel") {
					subdir = key + "/";
				}
				
                var path = audio_base + /*subdir +*/ param.sound[key].src;
                if (param.sound[key].src==undefined) {
                    path = audio_base + param.sound[key];
                }
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
								// console.log('Loaded! ' + this._src);
								loaded_audio_count++;
								if (loaded_audio_count==audio_count){
									$('.bodymap-frame .loading-wrapper').hide();
								}
								
                            },
                            onloaderror: function() {
                                console.log('Load error!!!!!!!!!!!!');
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
                        	sound.pause();
							sound.play();
							


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
        
        setTimeout(function(){
        	if (window.matchMedia("(orientation: portrait)").matches) {
		   		$('#stethoscope').css({left:"677px", top: "571px"});
			}
			if (window.matchMedia("(orientation: landscape)").matches) {
				$('#stethoscope').css({left:"584px", top: "389px"});
			}
        },400);
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
    if (audio_started) return;
    audio_started = true;
    
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
		
		// console.log("tetet",audio);
        
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