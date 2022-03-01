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
																		cardiacsoundPA: { src:"pulse(A).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundPP: { src:"pulse(P).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundPT: { src:"pulse(T).mp3", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundPM: { src:"pulse(M).mp3", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundH1: { src:"heart(1).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },
																		cardiacsoundH2: { src:"heart(2).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundH3: { src:"heart(3).m4a", r1:10, v1:0.5, r2:40, v2:0.1 },
																		cardiacsoundH4: { src:"heart(4).m4a", r1:10, v1:0.5, r2:30, v2:0.1 },
																	},
																	message: "",
																}
				},
			],
		},
	
	
	}

CASE_LIST["normal-A2"] = {
	type: TYPE_PATHOSIS,
	title: "症例②　生理的S2分裂",
	/*abstruct: "肺動脈弁部位で呼吸性分裂（＋）※GIFアニメテスト",*/
	has_question: false,
	enable_memo: false,
	nodes: node_data_list

};

