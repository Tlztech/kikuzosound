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
class AjaxDeleteSet extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_deleteset(Request $request)
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
    $fpid = $args['fpid'];  // 保存セットパックID

    DB::beginTransaction(); // トランザクション開始

    // 保存セットパックIDの登録している聴診音
    $fids = Favorites::where('pack_id',$fpid)->lists('id');

    foreach($fids as $fid) {
      try {
        $favorite = Favorites::findOrFail($fid);    // 例外ありレコード取得
        $favorite->delete();    // 削除
      } catch(Exception $e) {
        DB::rollback(); // ロールバック
        Log::error('お気に入り保存セットパックデータdelete失敗'); // ログ出力

        return(2);    // 失敗
      }
    }

    try {
      $favorite = FavoritePacks::find($fpid);
      $favorite->delete();    // 削除
    } catch(Exception $e) {
      DB::rollback(); // ロールバック
      Log::error('お気に入り保存セットパックdelete失敗'); // ログ出力

      return(2);    // 失敗
    }

    DB::commit();   // コミット

    return(1);    // 「削除」を表示
  }
}
