/*
* murmur
*/

var node_data_list = {

/*** cardio-murmurC20 ***/
	"002":
	{
		step_id: "20",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case20.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑳】心室中隔欠損（Ventricular septal defect:VSD）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
\
",

		back: null,
		forward:null,

		menu: [
			{ text: "📢⑳心室中隔欠損を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case20(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case20(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case20(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case20(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},

	
}

CASE_LIST["murmur-C20 "] = {
	type: TYPE_CASE,
	title: "症例⑳　心室中隔欠損",
	/*abstruct: "Cardiophonetics　S1RegS2（dGAAAta）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

