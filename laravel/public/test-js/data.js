

/*
var basic_learning_list = [
		{ id:"stetho", title: "聴診器の使い方", action: "goto", param:"stetho-init" },
		{ text: "肺音聴取", action: "goto", param:"init-respstudy" },
		{ text: "心音聴取", action: "goto", param:"init-cardio" },
		{ text: "腸音聴取", action: "goto", param:"init-bowel" },
		{ text: "血圧測定", action: "goto", param:"init-bp" }
];
*/
var choices_map = {

	"basic-learning-list" : [
		{ text: "聴診器の使い方", action: "goto", param:"init-stetho" },
		{ text: "肺音聴取", action: "goto", param:"init-respstudy" },
		{ text: "心音聴取", action: "goto", param:"init-cardio" },
		{ text: "腸音聴取", action: "goto", param:"init-bowel" },
		{ text: "血圧測定", action: "goto", param:"init-bp" }
	],

	"severeAE-list" : [
		{ text: "スタチンと横紋筋融解症", action: "goto", param:"init-statin" },
		{ text: "ビスホスホネートと顎骨壊死", action: "goto", param:"init-bispho" },
		{ text: "第Xa因子阻害薬と出血傾向", action: "goto", param:"init-fXa" },
		{ text: "グリタゾンと心不全", action: "goto", param:"init-glitazone" },
		{ text: "抗コリン吸入薬とイレウス", action: "goto", param:"init-spiriva" }
	]

};


var CASE_LIST = {};


var THERMOMETER = 0;
var SPHYGMOMANOMETER = 1;
var PULSE_OXYMETERS = 2;
var ELECTROCARDIOGRAPH = 3;
var GLUCOSE_METER = 4;
var PUPILLARY_REFLEX = 5;
var URINE_TEST_PAPER = 6;

