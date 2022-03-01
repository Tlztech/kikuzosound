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
																		cardiacsoundA: { src:"normal(A).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundP: { src:"normal(P).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundT: { src:"normal(T).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundM: { src:"normal(M).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },
																		
																		cardiacsoundH1: { src:"heart(1).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundH2: { src:"heart(2).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundH3: { src:"heart(3).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundH4: { src:"heart(4).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },

																		cardiacsoundTR1: { src:"lung(TR1).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundTR2: { src:"lung(TR2).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },

																		cardiacsoundBR1: { src:"lung(BR1).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundBR2: { src:"lung(BR2).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },

																		cardiacsoundVE1: { src:"lung(VE1).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundVE2: { src:"lung(VE2).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundVE3: { src:"lung(VE3).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundVE4: { src:"lung(VE4).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundVE5: { src:"lung(VE5).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundVE6: { src:"lung(VE6).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																	},
																	message: "",
																}
				},
			],
		},
	
	
	}
CASE_LIST["normal-A3"] = {
	type: TYPE_PATHOSIS,
	title: "症例③　S2異常分裂",
	/*abstruct: "肺動脈弁部位S1-Ⅱa-ⅡpS2（da TALA）",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

