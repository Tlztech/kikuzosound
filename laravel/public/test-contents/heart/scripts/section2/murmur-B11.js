/*
* murmur
*/

var node_data_list = {

	
/*** cardio-murmur11 ***/
	"002":
	{
		step_id: "11",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case11.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑪】心房中隔欠損（Atrial septal defect:ASD）（肺高血圧なし）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward:null,

		menu: [
			{ text: "📢㉓心房中隔欠損を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case1(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case10(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case1(M).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case1(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},


}

CASE_LIST["murmur-B11"] = {
	type: TYPE_CASE,
	title: "症例⑪　心房中隔欠損（肺高血圧なし）",
	/*abstruct: "肺動脈弁領域でS1Ej＜ⅡaⅡp（daha-Ta）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

