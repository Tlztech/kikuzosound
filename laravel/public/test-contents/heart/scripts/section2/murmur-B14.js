/*
* murmur
*/

var node_data_list = {

/*** cardio-murmur4 ***/
	"004":
	{
		step_id: "14",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case14.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑭】大動脈弁閉鎖不全（Aortic regurgitation:AR）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward:null,

		menu: [
			{ text: "📢⑭大動脈弁閉鎖不全を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case14(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case14(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case14(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case14(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],
	},
	


}

CASE_LIST["murmur-B14"] = {
	type: TYPE_CASE,
	title: "症例⑭　大動脈弁閉鎖不全",
	/*abstruct: "大動脈弁部位でS1-Ej-S2（da-ha-Ta/相対性駆出性雑音）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

