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

class MyPageController extends Controller
{
    /* telemedica 20170615 */
    /* iniファイルを読み取り、設定 */
    private $adIni;

    // telemedica 20170615
    public function __construct()
    {
        /* iniファイルを読み取り、設定 */
        $this->adIni = new IniFile();
        if(Session::get('lang')) App::setLocale(Session::get('lang'));
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmarks = array();
        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        // 認証確認
        $auth = Member::member_auth();
        if($auth == 1) { 
            setcookie("audioaccess","true");    // .htaccessで許可
            setcookie("trialaudio","true");    // 認証OK

            $sfpids = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',1)->get();
            // 表示のお気に入りヘッダ(当面、1つ固定)
            $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');

            // お気に入り聴診音
            $tmp = \App\Favorites::where('pack_id',$fpid)->first();
            $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

            $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');

            $bookmarks = $this->getBookmarks($sfpids, $fpid, $tmp, $fid, $universityId);
            $bookmarkCount = count($bookmarks);

            $params['aid'] = $account_id;
            $params['fpid'] = $fpid;
            $params['fid'] = $fid;
            $params['fmax'] = env('FAVORITE_MAX');
            $params['fomax'] = env('FAVORITE_ORIGINAL_SET_MAX');
        }

        return view('mypage', compact('params','bookmarks',"bookmarkCount",'auth'));
    }

    /**
     * Display a listing of recommended libraries
     *
     * @return \Illuminate\Http\Response
     */
    public function getRecommendedList(Request $request)
    {
        setcookie("audioaccess","true"); 
        $lib_ids = $request->input("lib_ids");
        // return $lib_ids;
        $recommended_libs = array();

        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        $auth = Member::member_auth();
    
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
          $params['preload'] = 'auto';
        } else {    // ログインしていない事はあり得ないが。
          $params['aid'] = $account_id;
          $params['fpid'] = 0;
          $params['fid'] = 0;
          $params['fmax'] = env('FAVORITE_MAX');
          $params['preload'] = 'auto';
        }

        $oneTimeKey = DB::table("onetime_keys")->where("customer_id", $account_id)->first();
        $this_university_id = $oneTimeKey->university_id;

        $ids = implode(',', $lib_ids);
        $recommended_libs = StethoSound::whereIn("id",$lib_ids)
        ->whereNull('deleted_at')
        ->orderByRaw(DB::raw("FIELD(id, $ids)"))
        ->get();

        for($i = 0; $i < count($recommended_libs); $i++) {
            $favo = 0;    // 0:非登録
            foreach($fid as $id) {
              if($id == $recommended_libs[$i]['id']) {
                $favo = 1;    // 1:登録中
                break;
              }
            }
            $recommended_libs[$i]['favo'] = $favo; // 追加
        }
        // return $recommended_libs;

        return view('mypage.recommended_lib', compact('params','recommended_libs','auth'));
    }

    /**
     * Display recommended ausculaide
     *
     * @return \Illuminate\Http\Response
     */
    public function getRecommendedAusculaide(Request $request){
        $aus_id = $request->input("aus");

        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $params['aid'] = $account_id;

        $auth = Member::member_auth();
        $sound = StethoSound::find($aus_id);

        $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');
        $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');
        $favo = 0;    // 0:非登録
        foreach($fid as $id) {
            if($id == $sound['id']) {
            $favo = 1;    // 1:登録中
            break;
            }
        }

        $sound['favo'] = $favo;
        return view('mypage.recommended_aus', compact('sound','auth', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * get Bookmarks list
     *@param  $sfpids, $fpid, $tmp, $fid, $universityId
     * @return array
     */
    function getBookmarks($sfpids, $fpid, $tmp, $fid, $universityId){
        // お気に入りがあるかの判別ができなかったので$tmpで1つだけで判別した
        $stetho_sounds = array();
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

            MyPageController::addFavo($stetho_sounds,$fid);
        } else {    // お気に入りがない場合
            $stetho_sounds = array();
        }
        
        return $stetho_sounds;
    }
}
