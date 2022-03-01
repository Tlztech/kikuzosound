<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StethoSound;
use DB;
use Illuminate\Support\Facades\App;
use Session;            /* telemedica 20171031 */

// ホーム画面のコントローラ
class ListController extends Controller
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
    // 全件
    $stetho_sounds = StethoSound::publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();

    // 心音・肺音・腸音・その他
    $cardiac = StethoSound::where('type',2)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $pulmonary = StethoSound::where('type',1)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $bowel = StethoSound::where('type',3)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $etc = StethoSound::where('type',9)->publicAll()->where("lib_type","=",0)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();

    $ausc = StethoSound::publicAll()->where("lib_type","=",1)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $palpation = StethoSound::publicAll()->where("lib_type","=",2)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $ecg = StethoSound::publicAll()->where("lib_type","=",3)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $inspection = StethoSound::publicAll()->where("lib_type","=",4)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $xray = StethoSound::publicAll()->where("lib_type","=",5)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();
    $ucg = StethoSound::publicAll()->where("lib_type","=",6)->whereNull('deleted_at')->orderBy('disp_order', 'desc')->orderBy('id',"desc")->get();

    $total_ausc = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",1)->count();
    $total_palpation = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",2)->count();
    $total_ecg = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",3)->count();
    $total_inspection = StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",4)->count();
    $total_xray =StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",5)->count();
    $total_ucg =StethoSound::publicAll()->whereNull('deleted_at')->where("lib_type","=",6)->count();

    // タイプ毎の結果
    $list['cardiac'] = $cardiac;
    $list['pulmonary'] = $pulmonary;
    $list['bowel'] = $bowel;
    $list['etc'] = $etc;

    // 音源数(公開数)の取得
    $params['count_public'] = count($stetho_sounds);
    $params['cardiac'] = count($cardiac);
    $params['pulmonary'] = count($pulmonary);
    $params['bowel'] = count($bowel);
    $params['etc'] = count($etc);

    $params["total_all_lib"] = 
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

    return view('list', compact('stetho_sounds','params','list','ausc','palpation','ecg','inspection','xray','ucg','total_ausc','total_palpation', 'total_ecg', 'total_inspection', 'total_xray', 'total_ucg'));
  }
}
