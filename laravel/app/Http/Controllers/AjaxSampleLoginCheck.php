<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Lib\TrialMember;

// お気に入り登録・削除のコントローラ
class AjaxSampleLoginCheck extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_samplelogincheck(Request $request)
  {
    // ajax以外は対象外
    if(!($request->ajax())) {
      return(3);    // JS側で処理はしないが、一応
    }

    // 何故かタイムゾーンが日本にならないので、設定
    date_default_timezone_set('Asia/Tokyo');

    $args = $request->all();    // 引数全て
    $ssid = $args['ssid'];      // 聴診音id(stetho_sound->id)

    // 認証
    $auth = TrialMember::trial_auth($ssid); // 0:NG 1:OK(3sp) 2:OK(試聴音)

    if($auth == 0) {    // ログインエラーの場合
        setcookie("audioaccess","",time() - 1800);  // .htaccess用削除
        setcookie("trialaudio","",time() - 1800);  // 認証
    }

    return($auth);  // JS側で処理
  }
}
