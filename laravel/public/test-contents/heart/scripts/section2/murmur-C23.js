/*
* murmur
*/

var node_data_list = {

/*** cardio-murmurC23 ***/
	"001":
	{
		step_id: "23",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case23.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例㉓】拡張型心筋症（Dilated cardiomyopathy:DCM）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward:null,

		menu: [
			{ text: "📢㉓拡張型心筋症を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case23(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case23(A).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case23(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case23(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],

	},
	
}

CASE_LIST["murmur-C23 "] = {
	type: TYPE_CASE,
	title: "症例㉓　拡張型心筋症(DCM)",
	/*abstruct: "僧帽弁部位でS4S1 RegS2 S3（uDAAAATada/Ⅳ音Ⅰ音全収縮期逆流雑音Ⅱ-3音）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

