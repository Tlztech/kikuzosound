var node_data_list = {
	/*** cardio-normal ***/
		"001":
		{
			step_id: "1",
			background_image: "bg_green.png",
			avator : "dr_tokunaga",
			avator_message : "",
			hide_step_buttons: true/*false*/,
			images : [
				{ type: "image", src: "cardio/left_image/case1.jpg"},
				{ type: "image", src: "cardio/Heart-1.jpg"},
			],
	
			text: 
	"【症例①】正常心音（Normal heart sounds）\n\
	\n\
	右下の聴診器アイコンを躯幹イラストの聴診部位に移動させて音を聴いてください。\
	",
	
			back: null,
			forward:null,
	
			menu: [
				{ text: "📢①Normal Heart Soundを聴く", action:"bodymap", param:{
																	type: "cardio",
																	sound: {
																		cardiacsoundBR3: { src:"lung(BR3).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundBR4: { src:"lung(BR4).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },

																		cardiacsoundVE7: { src:"lung(VE7).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundVE8: { src:"lung(VE8).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundVE9: { src:"lung(VE9).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundVE10: { src:"lung(VE10).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundVE11: { src:"lung(VE11).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundVE12: { src:"lung(VE12).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	},
																	message: "",
																}
				},
			],
		},
	
	
	}


CASE_LIST["normal-A6"] = {
	type: TYPE_PATHOSIS,
	title: "症例⑥　S4+S3ギャロップ",
	/*abstruct: "心尖部S1＞S2-S3（u-Da ta-da）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

