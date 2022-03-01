<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// お気に入り登録・削除のコントローラ
class AjaxOutlink extends Controller
{
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_outlink(Request $request)
  {
    // ajax以外は対象外
    if(!($request->ajax())) {
      return(2);    // JS側で処理はしないが、一応
    }

    // 何故かタイムゾーンが日本にならないので、設定
    date_default_timezone_set('Asia/Tokyo');

    $args = $request->all();  // 引数全て
    $href = $args['href'];  // 外部リンクのURL

    $date = date("Y-m-d H:i:s");    // 日時

    $ip = $request->ip(); // 接続元IPアドレス
    $user_agent = $request->header('User-Agent'); // ユーザエージェント
    $session_id = \Session::getId(); // セッションID
    $user_id = $request->session()->get('MEMBER_3SP_USER'); // ユーザID
    if ( empty($user_id) ) $user_id = '';
    $referer = $request->headers->get('referer'); // リファラ
    if ( is_null($referer) ) $referer = '';

    // ログに出力する1行
    $json = '{"date":"'.$date.'","ip":"'.$ip.'","user_agent":"'.$user_agent.'","session_id":"'.$session_id.'","user_id":"'.$user_id.'","referer":"'.$referer.'","href":"'.$href.'"}';

    // ログファイルのパス
//    $path = base_path('outlink.log');
    $path = storage_path('logs/outlink.log');
    // 標準の改行コード
    $json = str_replace(array("\n", "\r"), '', $json) . PHP_EOL;
    // 書き込み(排他制御あり。他がロックしている場合は終了まで待つ)
    file_put_contents($path, $json, FILE_APPEND|LOCK_EX);

    return($path);  // JS側で処理はしないが、一応
  }
}
