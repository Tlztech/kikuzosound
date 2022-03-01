/*
* murmur
*/

var node_data_list = {
/*** cardio-murmurC19 ***/
	"001":
	{
		step_id: "19",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case19.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑲】肺動脈弁狭窄（Pulmonic valvular stenosis:PS）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back: null,
		forward:null,

		menu: [
			{ text: "📢⑲肺動脈弁狭窄を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case19(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case19(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case19(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case19(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},

	
}

CASE_LIST["murmur-C19 "] = {
	type: TYPE_CASE,
	title: "症例⑲　肺動脈弁狭窄",
	/*abstruct: "肺動脈部位でS1-EjS2（dagaaata/収縮後期駆出性雑音）",*/
	has_question: false,
	enable_memo:false,
	nodes: node_data_list

};

