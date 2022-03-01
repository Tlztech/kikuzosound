/*
* murmur
*/

var node_data_list = {

	
/*** cardio-murmur3 ***/
	"003":
	{
		step_id: "13",
		hide_step_buttons: true/*false*/,
		avator : "dr_tokunaga",
		avator_message : "",

		images : [
			{ type: "image", src: "cardio/left_image/case13.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑬】大動脈弁狭窄（早期）（Aortic stenosis:AS  early stage）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward: null,

		menu: [
			{ text: "📢⑬大動脈弁狭窄（早期症例）", action:"bodymap", param:{
																type: "cardio",
																sound: {  
																	cardiacsoundA: { src:"case13(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case1(A).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case1(M).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case1(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},


}

CASE_LIST["murmur-B13"] = {
	type: TYPE_CASE,
	title: "症例⑬　大動脈弁狭窄（早期）",
	/*abstruct: "大動脈弁部位に駆出性雑音を聴取",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

