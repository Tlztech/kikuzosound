<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\StethoSoundRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\StethoSound;
use App\FavoritePacks;  /* telemedica 20171031 */
use App\Favorites;      /* telemedica 20171031 */
use App\Lib\IniFile;    /* telemedica 20170615 */
use App\Lib\Member;     /* telemedica 20171205 */
use DB;                 /* telemedica 20171031 */
use Log;                /* telemedica 20171031 */
use Session;            /* telemedica 20171031 */
use Exception;          /* telemedica 20171031 */

// お気に入り登録・削除のコントローラ
class AjaxInitSet extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_initset(Request $request)
  {
    // ajax以外は対象外
    if(!($request->ajax())) {
      return(2);
    }

    // 認証確認
    // これを入れないと画面遷移がない場合に「1回だけ」は処理が行えてしまう為
    $auth = Member::member_auth();
    if($auth == 0) {    // 未ログインの場合
      return(2);
    }

    // 画面遷移を伴うログはログファサード(monolog)を使用
    // configに設定あり。～/storage/logs/laravel.logに出力
    // 何故かタイムゾーンが日本にならないので、設定
    date_default_timezone_set('Asia/Tokyo');

    $args = $request->all();  // 引数全て

    $aid = $args['aid'];  // アカウントID
    $set = $args['set'];  // セットID
    $fpid = $args['fpid'];  // 保存セットパックID

    if($set == 0) { // 保存セットパックIDが有効の場合
        $ids = Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');
    } else {    // 固定の提供するセットの場合
        if($set == 1) { // 正常心音心基部
          $ids = array('61','43','45','50','52');
        } else if($set == 2) {  // 正常心音心尖部
          $ids = array('63','64','42','44','49');
        } else if($set == 3) {  // 正常呼吸音
          $ids = array('19','21','22','23','24');
        } else if($set == 4) {  // 笛声音wheezes
          $ids = array('50','51','52','178','179');
        } else if($set == 5) {  // 正常グル音
          $ids = array('99','100','101','102','103');
        }
    }

    DB::beginTransaction(); // トランザクション開始

    // パック(favorite_packs)
    // パックid
    $fpid = FavoritePacks::where('account_id',$aid)->where('set_status',0)->value('id');

    if(empty($fpid)) {    // パックがない場合、作成
      $title = "タイトル";    // とりあえず全角スペース

      $fps = new FavoritePacks(); // モデル

      $fps->account_id = $aid;
      $fps->title = $title;
      $fps->set_status = 0;

      $status = $fps->save();   // insert

      if(!$status) {
        DB::rollback();
        Log::error('お気に入りパックsave失敗'); // ログ出力

        return(2);    // 失敗
      }

      $fpid = $fps->id; // パックid取得。将来的には引数($pid)を使用する
    }

    // お気に入りデータが既にある場合は削除
    // 登録している聴診音
    $fids = Favorites::where('pack_id',$fpid)->lists('id');

    foreach($fids as $fid) {
      try {
        $favorite = Favorites::findOrFail($fid);    // 例外ありレコード取得
        $favorite->delete();    // 削除
      } catch(Exception $e) {
        DB::rollback(); // ロールバック
        Log::error('お気に入りデータdelete失敗'); // ログ出力

        return(2);    // 失敗
      }
    }

    // お気に入りデータ登録(favorites)
    foreach($ids as $id) {
      $favorite = new Favorites(); // モデル

      $favorite->pack_id = $fpid; // パックid
      $favorite->stetho_sound_id = $id;   // 聴診音id

      $status = $favorite->save();   // insert

      if(!$status) {
        DB::rollback(); // ロールバック
        Log::error('お気に入りデータsave失敗'); // ログ出力

        return(2);    // 失敗
      }
    }

    DB::commit();   // コミット

    return(1);    // 「削除」を表示
  }
}
