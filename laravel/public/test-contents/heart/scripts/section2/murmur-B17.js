/*
* murmur
*/

var node_data_list = {

/*** cardio-murmur17 ***/
	"007":
	{
		step_id: "17",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case17.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑰】肥大型心筋症(Hypertrophic cardiomyopathy : HCM = Idiopathic hypertrophic subaortic stenosis : IHSS)\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
\
",

		back:null,
		forward: null,

		menu: [
			{ text: "📢⑰大動脈弁下部狭窄", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case17(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case17(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case17(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case17(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],
	},
	

}

CASE_LIST["murmur-B17"] = {
	type: TYPE_CASE,
	title: "症例⑰　肥大型心筋症(HCM=IHSS)",
	/*abstruct: "三尖弁部位でS1-Ej-S2（DagAA-ta/収縮中期駆出性雑音）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

