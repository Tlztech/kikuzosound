<?php

namespace App\Http\Controllers;

use App\StethoSound;
use Input;
use DB;
use Illuminate\Support\Facades\App;
use Validator;
use Intervention\Image\Facades\Image;
use Session;            /* telemedica 20171031 */


// コンテンツ画面
class ContentsController extends Controller
{
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //.htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
    setcookie("audioaccess","true");    // .htaccessで許可

    // telemedica 20180220
    $show_on_page = Input::get('show','10');    // 1ページに表示する件数
    // audioタグへの読み込み指定。確実な制御ではないが
    if($show_on_page > 10) {
        $preload = "auto";
    } else {
        $preload = "auto";
    }
    // telemedica 20180220

    // telemedica 20171031
    // ログインアカウント
    $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');

    if(isset($account_id)) {     // ログイン中
      // お気に入りヘッダ(当面、1つ固定)
      $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');

      // お気に入り聴診音
//      $fid = \App\Favorites::where('pack_id',$fpid)->get();
      $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

      $params['aid'] = $account_id;
      $params['fpid'] = $fpid;
      $params['fid'] = $fid;
      $params['fmax'] = env('FAVORITE_MAX');
      $params['preload'] = $preload;    // telemedica 20180220
    } else {    // ログインしていない事はあり得ないが。
      $params['aid'] = $account_id;
      $params['fpid'] = 0;
      $params['fid'] = 0;
      $params['fmax'] = env('FAVORITE_MAX');
      $params['preload'] = $preload;    // telemedica 20180220
    }
    // telemedica 20171031

    // バリデーション
    $rules = [
      'sort'=>'in:asc,desc',
      'filter'=>'in:1,2,3,9',
      'keyword'=>'max:255',     //これを2にするとキーワードがなくても表示される?
      'lib_type' =>'in:0,1,2,3,4,5,6,7',
    ];
    $validator = Validator::make(Input::all(), $rules);

    if ($validator->fails()) {
      $stetho_sounds = StethoSound::publicAll()
        ->whereNull('deleted_at')
        ->groupAttribute($universityId)
        ->libType("0")
        ->orderBy('disp_order', 'desc')
        ->orderBy('id',"desc")
        ->paginate($show_on_page);
      return view('contents', compact('stetho_sounds','params'))->withErrors($validator);
    }

// DB::connection()->enableQueryLog();
    $lib_type = Input::get('lib_type');
    $sort = Input::get('sort','desc');
    $type = Input::get('filter','');
    $keyword = Input::get('keyword','');
    $isNormal = Input::get('isNormal','');
    //$keywords = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);

    if ($lib_type == null) {
      $stetho_sounds = StethoSound::publicAll()
      ->whereNull('deleted_at')
      ->groupAttribute($universityId)
      ->type($type)
      ->search($keyword)
      ->isNormal($isNormal)
      ->orderBy('disp_order', $sort)
      ->orderBy('id', $sort)
      ->where('lib_type', "!=", 1)
      ->paginate($show_on_page);
    } else {
      $stetho_sounds = StethoSound::publicAll()
      ->whereNull('deleted_at')
      ->groupAttribute($universityId)
      ->libType($lib_type)
      ->type($type)
      ->search($keyword)
      ->isNormal($isNormal)
      ->orderBy('disp_order', $sort)
      ->orderBy('id', $sort)
      ->paginate($show_on_page);
    }
    
// dd(DB::getQueryLog());

    // telemedica 20171031
    // $stetho_soundsの中に登録状態(0:非登録 1:登録中)を追加
    for($i = 0; $i < count($stetho_sounds); $i++) {
      $favo = 0;    // 0:非登録
      foreach($fid as $id) {
        if($id == $stetho_sounds[$i]['id']) {
          $favo = 1;    // 1:登録中
          break;
        }
      }
      $stetho_sounds[$i]['favo'] = $favo; // 追加
    }
    // telemedica 20171031

    return view('contents', compact('stetho_sounds','params'));
  }
}
