/*
* murmur
*/

var node_data_list = {

/*** cardio-murmurC21 ***/
	"003":
	{
		step_id: "21",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case21.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例㉑】動脈管開存（Patent ductus arteriosus:PDA）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back: null,
		forward:null,

		menu: [
			{ text: "📢㉑動脈管開存を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case21(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case21(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case21(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case21(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},

	
}

CASE_LIST["murmur-C21 "] = {
	type: TYPE_CASE,
	title: "症例㉑　動脈管開存",
	/*abstruct: "肺動脈弁部位でS1CrTaDecrS2（dgaaaTaaaadga/連続性雑音）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

