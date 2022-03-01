/*
* murmur
*/

var node_data_list = {
/*** cardio-murmur16 ***/
	"006":
	{
		step_id: "16",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case16.jpg"},
//			{ type: "video", src: "cardio/left_image/case15.mp4", poster:  "cardio/left_image/case16.png"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑯】僧帽弁狭窄（Mitral stenosis:MS）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
\
",

		back:null,
		forward: null,

		menu: [
			{ text: "📢⑯僧房弁狭窄（心尖部位）を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case16(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case16(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case16(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case16(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}        
			},

		],
	},
	

}

CASE_LIST["murmur-B16"] = {
	type: TYPE_CASE,
	title: "症例⑯　僧帽弁狭窄",
	/*abstruct: "僧帽弁部位でS1-S2-R-S1（fDa-TaRuuuu-fDa）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

