var debug = false;
//debug = true;

var DEFAULT_CONTENTS = 'heart';

var STETHOSCOPE_SPEED = 0.7; // 聴診器の移動速度
var DECAY_RADIUS = 0; // 音声減衰開始の半径[px] 
var EXTEND_AREA_RADIUS = 20; // 聴診音エリアの拡張半径(聴診エリアの半径にプラス)
var STETHO_AREA_MIN_VOLUME = 0.5; //聴診エリアの最小音量[0.0-1.0]
var EXTEND_AREA_MIN_VOLUME = 0.3; //拡張エリアの最小音量[0.0-1.0]
// 円の中心からDECAY_RADIUS[px]までは音量=1.0
// 円の最外周に向かうにつれDECAY_VOLUMEまで徐々に減衰する

// 聴診位置座標
var STETHO_POSITIONS = {
 "respiratory-front-1-1": {cx: 205, cy: 130},
 "respiratory-front-1-2": {cx: 500-205, cy: 130},
 "respiratory-front-2-1": {cx: 170, cy: 230},
 "respiratory-front-2-2": {cx: 500-170, cy: 230},
 "respiratory-front-3-1": {cx: 170, cy: 310},
 "respiratory-front-3-2": {cx: 500-170, cy: 310},
 "respiratory-front-4-1": {cx: 120, cy: 400},
 "respiratory-front-4-2": {cx: 500-120, cy: 400},

 "respiratory-back-1-1": {cx: 160, cy: 180},
 "respiratory-back-1-2": {cx: 500-160, cy: 180},
 "respiratory-back-2-1": {cx: 180, cy: 260},
 "respiratory-back-2-2": {cx: 500-180, cy: 260},
 "respiratory-back-3-1": {cx: 170, cy: 335},
 "respiratory-back-3-2": {cx: 500-170, cy: 335},
 "respiratory-back-4-1": {cx: 120, cy: 400},
 "respiratory-back-4-2": {cx: 500-120, cy: 400},
};

var USE_MP4 
		//= false;
		= true;
var MESSAGE_SPEED = 0;		// テキストの表速度。1.0:標準の早さ、0:一度に表示。
var HIDE_STEP_BUTTONS = false;	// 画面右パネル下のステップボタンの表示制御。 true:非表示、false:表示
var DEFAULT_AUTO_FORWARD_TIME = 0;//[秒]  // 自動で次に遷移するまでの時間[秒]のデフォルト値。 0で自動遷移しない
var START_TIME = 0.12;	// [秒] // Bodymap音声ループ再生開始位置
//var CASE_CONTENTS_FILE_URL = "../input/_files/";

// 各種ファイルの参照先
var AVATROR_IMAGE_PATH = "./images/avator/";			// アバター画像
var BACKGROUND_IMAGE_PATH = "./images/background/";		// 背景画像
var BASIC_CONTENTS_IMAGE_PATH = "./images/";//"./images/basic/";		// 基礎学習の画像ファイルパス
var CASE_CONTENTS_IMAGE_PATH =  "./images/";//"./images/case/";		// 症例学習の画像ファイルパス
var PATHOSIS_CONTENTS_IMAGE_PATH =  "./images/";//"./images/pathosis/";// 病態の画像ファイルパス
var BASIC_CONTENTS_AUDIO_PATH =  "./images/";//"./contents/audio/";	// 基礎学習の音声ファイルパス
var CASE_CONTENTS_AUDIO_PATH =  "./images/";//"./contents/case_audio/";// 症例学習の音声ファイルパス
var CONTENTS_VIDEO_PATH =  "./images/";//"./images/video/";			// 動画ファイル(基礎学習/症例共通)


// 履歴保存API
var API_URL = "../history/";

// コンテンツのタイプ
var BASIC_SLIDE = 0;	// 基礎学習
var TYPE_PATHOSIS = 3;	// 病態
var TYPE_CASE = 10;		// 症例

