/*
*normal
*/

var node_data_list = {
/*** cardio-normal ***/
	"001":
	{
		step_id: "7",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case7.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑦】S4・S3サメーションギャロップ（S4・S3 summation gallop）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back: null,
		forward: null,

		menu: [
			{ text: "📢⑦　 Summation Gallopを聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case7(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case7(A).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case7(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case7(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},

		],
	},
}

CASE_LIST["normal-A7"] = {
	type: TYPE_PATHOSIS,
	title: "症例⑦　S4・S3サメーションギャロップ",
	/*abstruct: "Summation（重合奔馬調律）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

