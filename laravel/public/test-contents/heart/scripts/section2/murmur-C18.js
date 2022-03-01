/*
* murmur
*/

var node_data_list = {

/*** cardio-murmur18 ***/
	"008":
	{
		step_id: "18",

		avator : "dr_tokunaga",
		avator_message : "",
		hide_step_buttons: true/*false*/,
		images : [
			{ type: "image", src: "cardio/left_image/case18.jpg"},
			{ type: "image", src: "cardio/Heart-1.jpg"},
		],

		text: 
"【症例⑱】三尖弁閉鎖不全（Tricuspid regurgitation:TR）\n\
\n\
右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
",

		back:null,
		forward: null,

		menu: [
			{ text: "📢⑱三尖弁閉鎖不全を聴く", action:"bodymap", param:{
																type: "cardio",
																sound: {
																	cardiacsoundA: { src:"case18(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	cardiacsoundP: { src:"case18(A).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundT: { src:"case18(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																	cardiacsoundM: { src:"case18(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																},message: "",
															}
			},
		],
	},

}

CASE_LIST["murmur-C18"] = {
	type: TYPE_CASE,
	title: "症例⑱　三尖弁閉鎖不全",
	/*abstruct: "胸骨左縁下部に全収縮期雑音が聴かれ、吸気時に減弱する",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

