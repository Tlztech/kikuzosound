/*
* murmur
*/

var node_data_list = {
/*** cardio-murmurC22 ***/
	"001":
	{
		step_id: "22",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case22.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例㉒】僧帽弁逸脱（Mitral valvular prolapse:MVP）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back: null,
		forward:null,

		menu: [
			{ text: "📢㉒僧帽弁逸脱を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case1(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case1(A).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case22(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case22(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},

	
}

CASE_LIST["murmur-C22 "] = {
	type: TYPE_CASE,
	title: "症例㉒　僧帽弁逸脱",
	/*abstruct: "心尖部で収縮中期クリック音＋収取後期雑音を聴取",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

