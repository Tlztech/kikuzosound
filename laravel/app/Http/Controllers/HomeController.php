<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Controllers\MemberController;
use App\Http\Requests\HomeAjaxRequest;
use App\Information;
use App\Lib\GlobalFunction;
use App\Lib\IniFile;
use App\Lib\Member;
use App\OnetimeKey;
use App\StethoSound;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// ホーム画面のコントローラ
class HomeController extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */

  /* telemedica 20170615 */
  /* iniファイルを読み取り、設定 */
  private $adIni;

  // telemedica 20170615
  public function __construct()
  {
    /* iniファイルを読み取り、設定 */
    $this->adIni = new IniFile();
  }

  // telemedica 20180517
  // $p_soundsの中に登録状態(0:非登録 1:登録中)を追加
  private static function addFavo($p_sounds,$fid) {
    for($i = 0; $i < count($p_sounds); $i++) {
        $favo = 0;    // 0:非登録
        foreach($fid as $id) {
            if($id == $p_sounds[$i]['id']) {
            $favo = 1;    // 1:登録中
            break;
            }
        }
        $p_sounds[$i]['favo'] = $favo; // 追加
    }
  }

  public function index()
  {
    $news = Information::orderBy('updated_at','desc')->where('status', 1)->get();
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
    // 広告の有効(表示)・無効(非表示)
    $params['init_disp'] = $this->adIni->getConfig('init_disp','advertorial','false');

    // 広告の有効(表示)・無効(非表示)の判断
    if($params['init_disp'] === 'true') {
      // 広告のタイトル
      $params['init_title'] = $this->adIni->getConfig('init_title','advertorial','false');

      // 聴診音のID1。カンマで複数個の記述可能
      $init_id1 = $this->adIni->getConfig('init_id1','advertorial','false');
      $ids1 = explode(",",$init_id1);

      // 聴診音のID2。カンマで複数個の記述可能
      $init_id2 = $this->adIni->getConfig('init_id2','advertorial','false');
      $ids2 = explode(",",$init_id2);

      $params['init_subtitle1'] = $this->adIni->getConfig('init_subtitle1','advertorial','false');
      $params['init_text1'] = $this->adIni->getConfig('init_text1','advertorial','false');
      $params['init_subtitle2'] = $this->adIni->getConfig('init_subtitle2','advertorial','false');
      $params['init_text2'] = $this->adIni->getConfig('init_text2','advertorial','false');
      $params['init_img1'] = $this->adIni->getConfig('init_img1','advertorial','false');
      $params['init_alt1'] = $this->adIni->getConfig('init_alt1','advertorial','false');
      $params['init_img2'] = $this->adIni->getConfig('init_img2','advertorial','false');
      $params['init_alt2'] = $this->adIni->getConfig('init_alt2','advertorial','false');
    }

    // 音源トータル数(公開数)の取得
    $data = StethoSound::PublicAll()->get();
    $params['count_public'] =count($data);

    // telemedica 20171031
    // ログインアカウント
    $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');

    // 表示用データ
    $title = trans('home.auscultation_member_library');
   // 0:ログインお気に入りあり 1:ログインお気に入りなし 2:未ログイン
    $message = 0;

    // 認証確認
    $auth = Member::member_auth();

    $bookmarkCount = 0;

    // count all library
    $cardiac = StethoSound::where('type',2)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $pulmonary = StethoSound::where('type',1)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $bowel = StethoSound::where('type',3)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $etc = StethoSound::where('type',9)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();

    $total_ausc = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",1)->count();
    $total_palpation = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",2)->count();
    $total_ecg = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",3)->count();
    $total_inspection = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",4)->count();
    $total_xray =StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",5)->count();
    $total_ucg =StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",6)->count();

    $stethoSoundsCount = 
      $total_ausc + 
      $total_palpation + 
      $total_ecg + 
      $total_inspection + 
      $total_xray + 
      $total_ucg + 
      count($cardiac) + 
      count($pulmonary) +
      count($bowel) +
      count($etc);

    // $stethoSoundsCount = StethoSound::publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->count();
/*
    if(isset($account_id)) {     // ログイン中
*/
    if($auth == 1) {     // ログイン中
        setcookie("audioaccess","true");    // .htaccessで許可
        setcookie("trialaudio","true");    // 認証OK

        $sfpids = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',1)->get();
        // 表示のお気に入りヘッダ(当面、1つ固定)
        $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');

        // お気に入り聴診音
        $tmp = \App\Favorites::where('pack_id',$fpid)->first();
        $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

        $title = trans('home.favorite');

        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');

        // 何故か$fidが配列と見なされずis_array($fid)でfalseになる
        // お気に入りがあるかの判別ができなかったので$tmpで1つだけで判別した
        if(isset($fpid) && !empty($tmp)) {  // お気に入りがある場合
            // お気に入り登録順でソート
            $stetho_sounds = \App\StethoSound::select("favorites.*", 'stetho_sounds.*', 'favorites.id AS favorites_id')
              ->join('favorites','stetho_sounds.id','=','favorites.stetho_sound_id')
              ->whereNull('stetho_sounds.deleted_at')
              ->groupAttribute($universityId)
              ->where('favorites.pack_id',$fpid)
              ->orderBy('favorites.disp_order', 'asc')
              ->orderBy('favorites.id','asc')
              ->get();
//            $stetho_sounds = StethoSound::SelectID($fid)->orderBy('created_at','desc')->get();

            // $stetho_soundsの中に登録状態(0:非登録 1:登録中)を追加
            HomeController::addFavo($stetho_sounds,$fid);
        } else {    // お気に入りがない場合
            $stetho_sounds = array();
            $message = 1;
        }

        $bookmarkCount = count($stetho_sounds);

        $params['aid'] = $account_id;
        $params['fpid'] = $fpid;
        $params['fid'] = $fid;
        $params['title'] = $title;
        $params['message'] = $message;
        $params['fmax'] = env('FAVORITE_MAX');
        $params['fomax'] = env('FAVORITE_ORIGINAL_SET_MAX');
    } else {    // ログインしていない
        // 新着の聴診音
//        $stetho_sounds = StethoSound::publicNewAll()->orderBy('created_at','desc')->get();
        $stetho_sounds = StethoSound::publicNewAll()
            ->where(function ($groups) {
              $groups->Has('exam_groups', '<', 1); //has no group attribute
            })
            ->whereNull('deleted_at')
            ->orderBy('disp_order', 'desc')
            ->orderBy('id','desc')
            ->get();

        $bookmarkCount = 0;

        $message = 2;
        $params['aid'] = $account_id;
        $params['fpid'] = 0;
        $params['fid'] = 0;
        $params['title'] = $title;
        $params['message'] = $message;
        $params['fmax'] = env('FAVORITE_MAX');
        $params['fomax'] = env('FAVORITE_ORIGINAL_SET_MAX');
    }
    // telemedica 20171031

    // 広告の有効(表示)・無効(非表示)の判断
    if($params['init_disp'] === 'true') {
      // 聴診音の順番はこのorderByによる
      $ad_sounds1 = StethoSound::SelectID($ids1)->orderBy('created_at','asc')->get();
      $ad_sounds2 = StethoSound::SelectID($ids2)->orderBy('created_at','asc')->get();
    }

    // 薬局用　固定聴診音
    // 聴診音の順番はこのorderByによる
    // $spid = 1;
    // $p_sounds1 = \App\StethoSound::select('stetho_sounds.*')->join('set_sounds','stetho_sounds.id','=','set_sounds.stetho_sound_id')->where('set_sounds.pack_id',$spid)->orderBy('set_sounds.disp_order','asc')->get();
    // if($auth == 1) {     // ログイン中
    //     // $p_sounds1の中に登録状態(0:非登録 1:登録中)を追加
    //     HomeController::addFavo($p_sounds1,$fid);
    // }

    // $spid = 2;
    // $p_sounds2 = \App\StethoSound::select('stetho_sounds.*')->join('set_sounds','stetho_sounds.id','=','set_sounds.stetho_sound_id')->where('set_sounds.pack_id',$spid)->orderBy('set_sounds.disp_order','asc')->get();
    // if($auth == 1) {     // ログイン中
    //     // $p_sounds1の中に登録状態(0:非登録 1:登録中)を追加
    //     HomeController::addFavo($p_sounds2,$fid);
    // }

    // $spid = 3;
    // $p_sounds3 = \App\StethoSound::select('stetho_sounds.*')->join('set_sounds','stetho_sounds.id','=','set_sounds.stetho_sound_id')->where('set_sounds.pack_id',$spid)->orderBy('set_sounds.disp_order','asc')->get();
    // if($auth == 1) {     // ログイン中
    //     // $p_sounds1の中に登録状態(0:非登録 1:登録中)を追加
    //     HomeController::addFavo($p_sounds3,$fid);
    // }

    // $spid = 4;
    // $p_sounds4 = \App\StethoSound::select('stetho_sounds.*')->join('set_sounds','stetho_sounds.id','=','set_sounds.stetho_sound_id')->where('set_sounds.pack_id',$spid)->orderBy('set_sounds.disp_order','asc')->get();
    // if($auth == 1) {     // ログイン中
    //     // $p_sounds1の中に登録状態(0:非登録 1:登録中)を追加
    //     HomeController::addFavo($p_sounds4,$fid);
    // }

    // $spid = 5;
    // $p_sounds5 = \App\StethoSound::select('stetho_sounds.*')->join('set_sounds','stetho_sounds.id','=','set_sounds.stetho_sound_id')->where('set_sounds.pack_id',$spid)->orderBy('set_sounds.disp_order','asc')->get();
    // if($auth == 1) {     // ログイン中
    //     // $p_sounds1の中に登録状態(0:非登録 1:登録中)を追加
    //     HomeController::addFavo($p_sounds5,$fid);
    // }

    /* 試聴音登録利用 */
    $sample_status = 0; // 0:未ログイン 1:ログイン(3sp) 2:ログイン(試聴音)

    $sample_user = Session::get('SAMPLE-3SP-USER'); // 試聴音ユーザ

    if($sample_user != '') {   // セッションがある場合(ログイン中)
        $sample_type = Session::get('SAMPLE-USER-TYPE'); // 0:試聴音 1:3sp

        if($sample_type == 0) { // 試聴音登録者の場合
            $sample_status = 2; // 2:ログイン(試聴音)
        } else if($sample_type == 1) {  // 3spアカウントの場合
            $sample_status = 1; // 1:ログイン(3sp)
        }
    }

    $params['sample_user'] = Session::get('SAMPLE-EDIT-KEY');   // getパラメータ
    $params['sample_status'] = $sample_status;
    /* 試聴音登録利用 */

    // 特にここで削除する必要もないが、残っているのも気分が悪いので削除
    Session::forget('SAMPLE-USER-PASSWORD');     // 削除(パスワード)

    //call the method for
    // $checkBrowserToken = HomeController::checkIfBwtkExists();
    // $checkBrowserToken = ($account_id != 126 && $account_id != null)? HomeController::checkIfBwtkExists() : "OK";
    // //check if the browser token exists
    // if($checkBrowserToken == "OK"){
    //   //redirect the user to homepage
    //   return view('home', compact('stetho_sounds','ad_sounds1','ad_sounds2','params','sfpids','p_sounds1','p_sounds2','p_sounds3','p_sounds4','p_sounds5','auth'));
    // }

    return view('home', compact('stetho_sounds','ad_sounds1','ad_sounds2','params','sfpids','p_sounds1','p_sounds2','p_sounds3','p_sounds4','p_sounds5','auth', 'bookmarkCount', 'stethoSoundsCount','news'));
  }

  public function changeLanguage() {
    $previousRequest = app('request')->create(app('url')->previous());
    $routeName = app('router')->getRoutes()->match($previousRequest)->getName();
    $locale = trim($_SERVER["REQUEST_URI"], "\/");

    // check if lang is enabled
    // if (!env("lang_" . $locale)) {
    //   throw new NotFoundHttpException;
    // }

    Session::set('lang', $locale);
    return redirect()->route($routeName != null ? $routeName : "home");
  }

  public function reorderFavorites(Request $request) {
    $favorites = $request->favorites;
    $result = null;
    foreach($favorites as $favorite){
      DB::table("favorites")
        ->where("id", $favorite['favorites_id'])
        ->update([
          'disp_order' => $favorite['disp_order'],
          'updated_at'  => date('Y-m-d H:i:s')
        ]);
    }
    return $result;
  }

  // //create a function that will check if the browser token exists
  // public function checkIfBwtkExists(){

  //   //get the current date
  //   $dt_now = Carbon::now();

  //   //check if there is a cookie set
  //   if(isset($_COOKIE["bwtk"]) && isset($_COOKIE["laravel_session"]) && isset($_COOKIE["XSRF-TOKEN"])){
  //     $decrypted_onetime_key = GlobalFunction::custom_decryptor($_COOKIE["bwtk"], csrf_token());

  //     //get the customer id
  //     $current_customer_id = OnetimeKey::select('customer_id')->where('onetime_key', $decrypted_onetime_key)->get();
  //     $account_id = $current_customer_id[0]["customer_id"];

  //     //get the user field from the table accounts
  //     $current_user = Account::select('user')->where('id', $account_id)->get();
  //     $user = $current_user[0]["user"];

  //     //get the user field from the table accounts
  //     $current_mail_add = Account::select('email')->where('id', $account_id)->get();
  //     $mail_address = $current_mail_add[0]["email"];

  //     //call the function to set onetime and update
  //     GlobalFunction::setOnetimeAndUpdate($account_id);

  //     //then call the function to remove and udpate the session
  //     GlobalFunction::removeUpdateSession($dt_now, $account_id, $user, "", $mail_address);

  //     //THEN RETURN SUCCESS MESSAGE
  //     return "OK";
  //   }

  // }
}
