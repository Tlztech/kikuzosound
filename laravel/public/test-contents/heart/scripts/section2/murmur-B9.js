/*
* murmur
*/

var node_data_list = {
/*** cardio-murmur ***/
	"001":
	{
		step_id: "9",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case9.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: /*⑧無害性雑音のP部位を差し替え*/
"【症例⑨】無害性（機能性）雑音（Innocent or functional murmur）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back: null,
		forward: null,

		menu: [
			{ text: "📢⑨無害性雑音（健常者）", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case1(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case9(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case1(M).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case1(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},

		],

	},
	


}

CASE_LIST["murmur-B9"] = {
	type: TYPE_CASE,
	title: "症例⑨　無害性（機能性）雑音",
	/*abstruct: "肺動脈弁部位で収縮早期に柔らかい駆出性雑音",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

