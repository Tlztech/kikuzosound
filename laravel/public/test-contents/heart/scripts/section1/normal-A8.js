/*
*normal
*/

var node_data_list = {
/*** cardio-normal ***/
	"001":
	{
		step_id: "8",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case8.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑧】肺動脈駆出音（Pulmonary ejection sounds）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back: null,
		forward: null,

		menu: [
			{ text: "📢⑧　肺動脈駆出音を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case1(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case8(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case1(M).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case1(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},

		],
	},
}

CASE_LIST["normal-A8"] = {
	type: TYPE_PATHOSIS,
	title: "症例⑧　肺動脈駆出音",
	/*abstruct: "肺動脈弁領域でdaK! Ta",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

