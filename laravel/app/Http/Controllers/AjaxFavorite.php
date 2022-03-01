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
class AjaxFavorite extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_favo(Request $request)
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
    $pid = $args['pid'];  // 【未使用】パックID(当面、1個固定なので未使用)
    $id = $args['id'];    // 聴診音ID
    $favo = $args['favo'];    // 【未使用　下記に記述有】　0:未登録(なので登録する) 1:登録中(なので削除する)

    // データがなければ登録、あれば削除、という処理にしている
    // 当初はfavoを使用していたが、連続してクリックされた時に対処できない為
    // 因みにJSのdata-の内容をdatasetで書き換えようとしたが、上手くいかなかった

    // パックid
    $fpid = FavoritePacks::where('account_id',$aid)->where('set_status',0)->value('id');

    // お気に入りデータid
    $fid = Favorites::where('pack_id',$fpid)->where('stetho_sound_id',$id)->value('id');

    if(empty($fid)) {   // データがない
      $favo = 0;    // 登録
    } else {    // データがある
      $favo = 1;    // 削除
    }

    if($favo == 0) {    // 未登録が送られてきたので登録する
      // Bookmark limit 10 sounds → unlimited
      // 登録している聴診音
      // $fids = Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');
      // $fc = count($fids);   // 登録聴診音数
      // $fmax = env('FAVORITE_MAX');  // 登録最大数

      // if($fc >= $fmax) {
      //   return(3);  // 登録数オーバー
      // }

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
//        $fps->created_at = date("Y-m-d H:i:s", time());
//        $fps->updated_at = date("Y-m-d H:i:s", time());

        $status = $fps->save();   // insert

        if(!$status) {
          DB::rollback();
          Log::error('お気に入りパックsave失敗'); // ログ出力

          return(2);    // 失敗
        }

        $fpid = $fps->id; // パックid取得。将来的には引数($pid)を使用する
      }

      // お気に入りデータ(favorites)
      $favorite = new Favorites(); // モデル

      $favorite->pack_id = $fpid;
      $favorite->stetho_sound_id = $id;
      $favorite->disp_order = Favorites::where("pack_id", $fpid)->max("disp_order") +1;
//      $favorite->created_at = date("Y-m-d H:i:s", time());
//      $favorite->updated_at = date("Y-m-d H:i:s", time());

      $status = $favorite->save();   // insert

      if(!$status) {
        DB::rollback(); // ロールバック
        Log::error('お気に入りデータsave失敗'); // ログ出力

        return(2);    // 失敗
      }

      DB::commit();   // コミット

      return(1);    // 「削除」を表示
    } elseif($favo == 1) {   // 登録が送られてきたので削除する
      DB::beginTransaction(); // トランザクション開始

      // パックid
      $fpid = FavoritePacks::where('account_id',$aid)->where('set_status',0)->value('id');

      // お気に入りデータid
      $fid = Favorites::where('pack_id',$fpid)->where('stetho_sound_id',$id)->value('id');

      try {
        $favorite = Favorites::findOrFail($fid);    // 例外ありレコード取得
        $favorite->delete();    // 削除
        DB::commit();   // コミット

        return(0);  // 「登録」を表示
      } catch(Exception $e) {
        DB::rollback(); // ロールバック
        Log::error('お気に入りデータdelete失敗'); // ログ出力

        return(2);    // 失敗
      }
    }
  }
}
