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
class AjaxSaveSet extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_saveset(Request $request)
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

    $aid = $args['aid'];  // アカウントid
    $fpid = $args['fpid'];  // 登録パックid
    $title = $args['title'];  // タイトル
    $max = $args['max'];  // タイトル最大文字数

    // 0も含め、最大文字数がないor30以上の場合(不正アクセスとか)
    // JSでのチェックもあるので念の為
    if(empty($max) || ($max >= 30)) {
      Log::error('お気に入りパック最大文字数不正により失敗'); // ログ出力

      return(2);    // 失敗
    }

    // タイトル未入力または最大文字数以上の場合
    // JSでのチェックもあるので念の為
    if($title == "" || (mb_strlen($title) > $max)) {
      Log::error('お気に入りパック文字数制限により失敗'); // ログ出力

      return(2);    // 失敗
    }

    // 保存しているセット数
    $fids = FavoritePacks::where('account_id',$aid)->where('set_status',1)->lists('id');
    $fc = count($fids);   // 登録聴診音数
    $fmax = env('FAVORITE_ORIGINAL_SET_MAX');  // 登録最大数

    if($fc >= $fmax) {
      return(3);  // 登録数オーバー
    }

    // 登録している聴診音
    // set_status=0は1つの前提としている
    $fids = Favorites::where('pack_id',$fpid)->lists('stetho_sound_id');

    DB::beginTransaction(); // トランザクション開始

    // パック(favorite_packs)をupdate
    $pack = FavoritePacks::find($fpid);

    $pack->title = $title;    // タイトル
    $pack->set_status = 1;    // セット保存

    $status = $pack->save();   // update

    if(!$status) {
      DB::rollback();
      Log::error('お気に入りパックupdate失敗'); // ログ出力

      return(2);    // 失敗
    }

    // 新規にset_status=0のパックを作成
    $title = "タイトル";    // とりあえず全角スペース

    $fps = new FavoritePacks(); // モデル

    $fps->account_id = $aid;
    $fps->title = $title;
    $fps->set_status = 0;

    $status = $fps->save();   // insert

    if(!$status) {
      DB::rollback();
      Log::error('セット保存時のお気に入りパックsave失敗'); // ログ出力

      return(2);    // 失敗
    }

    $fpid = $fps->id; // 新パックid取得

    // 保存したお気に入りデータコピー(favorites)
    foreach($fids as $id) {
      $favorite = new Favorites(); // モデル

      $favorite->pack_id = $fpid; // パックid
      $favorite->stetho_sound_id = $id;   // 聴診音id

      $status = $favorite->save();   // insert

      if(!$status) {
        DB::rollback(); // ロールバック
        Log::error('セット保存時のお気に入りデータsave失敗'); // ログ出力

        return(2);    // 失敗
      }
    }

    DB::commit();   // コミット

    return(1);    // 「削除」を表示
  }
}
