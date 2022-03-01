/*
* murmur
*/

var node_data_list = {

	
/*** cardio-murmur3 ***/
	"003":
	{
		step_id: "12",
		hide_step_buttons: true/*false*/,
		avator : "dr_tokunaga",
		avator_message : "",

		images : [
			{ type: "image", src: "cardio/left_image/case12.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑫】大動脈弁狭窄（Aortic stenosis:AS）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward: null,

		menu: [
			{ text: "📢⑫大動脈弁狭窄（大動脈弁部位）", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case12(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case12(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case12(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case12(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},


}

CASE_LIST["murmur-B12"] = {
	type: TYPE_CASE,
	title: "症例⑫　大動脈弁狭窄",
	/*abstruct: "大動脈弁部位から心尖部に粗い低音～中音の駆出性雑音を収縮中期～後期に聴取",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

