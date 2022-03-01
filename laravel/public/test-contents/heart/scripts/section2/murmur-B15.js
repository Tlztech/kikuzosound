/*
* murmur
*/

var node_data_list = {

/*** cardio-murmur15 ***/
	"005":
	{
		step_id: "15",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case15.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑮】僧帽弁閉鎖不全（Mitral regurgitation:MR）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward: null,

		menu: [
			{ text: "📢⑮僧房弁閉鎖不全", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case1(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case1(A).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case15(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case15(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],
	},
	


}

CASE_LIST["murmur-B15"] = {
	type: TYPE_CASE,
	title: "症例⑮　僧帽弁閉鎖不全",
	/*abstruct: "僧帽弁部位でS1RegmS2（DHaaaata）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

