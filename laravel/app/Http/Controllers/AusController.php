<?php

namespace App\Http\Controllers;

use App\StethoSound;
use Session;
use Input;
use DB;
use Illuminate\Support\Facades\App;
use Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class AusController extends Controller
{
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }

  public function index() 
  {
        // telemedica 20180220
        $show_on_page = Input::get('show','10');    // 1ページに表示する件数

        // バリデーション
        $rules = [
          'sort'=>'in:asc,desc',
          'filter'=>'in:1,2,3,9',
          'keyword'=>'max:255',     //これを2にするとキーワードがなくても表示される?
        ];
        $validator = Validator::make(Input::all(), $rules);
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        if ($validator->fails()) {
          $stetho_sounds = StethoSound::publicAll()
            ->whereNull('deleted_at')
            ->groupAttribute($universityId)
            ->where('lib_type',1) //for stethoscope lib
            ->orderBy('disp_order', 'desc')
            ->orderBy('id',"desc")
            ->paginate($show_on_page);
          return view('aus', compact('stetho_sounds')->withErrors($validator));
        }
    
    // DB::connection()->enableQueryLog();
        $sort = Input::get('sort','desc');
        $type = Input::get('filter','');
        $keyword = Input::get('keyword','');
        $isNormal = Input::get('isNormal','');
        //$keywords = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
    
        $stetho_sounds = StethoSound::publicAll()
          ->whereNull('deleted_at')
          ->groupAttribute($universityId)
          ->where('lib_type',1) //for stethoscope lib
          ->type($type)
          ->search($keyword)
          ->isNormal($isNormal)
          ->orderBy('disp_order', $sort)
          ->orderBy('id', $sort)
          ->paginate($show_on_page);

        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $params['aid'] = $account_id;

        $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');

        $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

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
    
        return view('aus', compact('stetho_sounds', 'params'));
      
  }

  public function indexOffline($id){
    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
    $sound = StethoSound::publicAll()
      ->where("id",$id)
      ->whereNull('deleted_at')
      ->groupAttribute($universityId)
      ->where('lib_type',1)->first();

    return view('aus_offline', compact('sound'));
  }

  public function getIpax($id){
    $sound = StethoSound::where("id",$id)
      ->whereNull('deleted_at')
      ->where('lib_type',1)->with("user")->first();
    //url switch off
    if(empty($sound->moodle_url) || is_null($sound->moodle_url)){
      return view('errors.unavailable');
    }
    //diabled in admin
    if($sound->user && !$sound->user->enabled){
      return view('errors.unavailable');
    }
    return view('aus_offline', compact('sound'));
  }

  public function getAjaxAus() 
  {
        // telemedica 20180220
        $show_on_page = Input::get('show','10');    // 1ページに表示する件数

        // バリデーション
        $rules = [
          'sort'=>'in:asc,desc',
          'filter'=>'in:1,2,3,9',
          'keyword'=>'max:255',     //これを2にするとキーワードがなくても表示される?
        ];
        $validator = Validator::make(Input::all(), $rules);
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        if ($validator->fails()) {
          $stetho_sounds = StethoSound::publicAll()
            ->whereNull('deleted_at')
            ->groupAttribute($universityId)
            ->where('lib_type',1) //for stethoscope lib
            ->orderBy('disp_order', 'desc')
            ->orderBy('id',"desc")
            ->paginate($show_on_page);
          return view('aus_ajax', compact('stetho_sounds')->withErrors($validator));
        }
    
    // DB::connection()->enableQueryLog();
        $sort = Input::get('sort','desc');
        $type = Input::get('filter','');
        $keyword = Input::get('keyword','');
        $isNormal = Input::get('isNormal','');
        //$keywords = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
    
        $stetho_sounds = StethoSound::publicAll()
          ->whereNull('deleted_at')
          ->groupAttribute($universityId)
          ->where('lib_type',1) //for stethoscope lib
          ->type($type)
          ->searchTitle($keyword)
          ->isNormal($isNormal)
          ->orderBy('disp_order', $sort)
          ->orderBy('id', $sort)
          ->paginate($show_on_page);

        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $params['aid'] = $account_id;

        $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');

        $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

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
    
        return view('aus_ajax', compact('stetho_sounds', 'params'));
      
  }

  public function getNewAus() 
  {
        // telemedica 20180220
        $show_on_page = Input::get('show','10');    // 1ページに表示する件数

        // バリデーション
        $rules = [
          'sort'=>'in:asc,desc',
          'filter'=>'in:1,2,3,9',
          'keyword'=>'max:255',     //これを2にするとキーワードがなくても表示される?
        ];
        $validator = Validator::make(Input::all(), $rules);
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        if ($validator->fails()) {
          $stetho_sounds = StethoSound::publicAll()
            ->whereNull('deleted_at')
            ->groupAttribute($universityId)
            ->where('lib_type',1) //for stethoscope lib
            ->orderBy('disp_order', 'desc')
            ->orderBy('id',"desc")
            ->paginate($show_on_page);
          return view('aus_iframe', compact('stetho_sounds')->withErrors($validator));
        }
    
    // DB::connection()->enableQueryLog();
        $sort = Input::get('sort','desc');
        $type = Input::get('filter','');
        $keyword = Input::get('keyword','');
        $isNormal = Input::get('isNormal','');
        //$keywords = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY);
    
        $stetho_sounds = StethoSound::publicAll()
          ->whereNull('deleted_at')
          ->groupAttribute($universityId)
          ->where('lib_type',1) //for stethoscope lib
          ->type($type)
          ->searchTitle($keyword)
          ->isNormal($isNormal)
          ->orderBy('disp_order', $sort)
          ->orderBy('id', $sort)
          ->paginate($show_on_page);

        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $params['aid'] = $account_id;

        $fpid = \App\FavoritePacks::where('account_id',$account_id)->where('set_status',0)->value('id');

        $fid = \App\Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

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
    
        return view('aus_new', compact('stetho_sounds', 'params'));
      
  }

}
