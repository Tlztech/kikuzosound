<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
/**
 * ページ遷移を伴わないで発生したユーザ操作のログを記録するコントローラ
 * WebAPI /log で Ajax で記録する
 * [注意]
 * ・ページ遷移を伴う場合は LogAfterController に記述している。
 * ・クイズ中のHTML部分ページ遷移は QuizPackController に記述している。
 */
class LogController extends Controller
{
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }
  /**
   * [POST] /log
   *
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // 記録日時
    date_default_timezone_set('Asia/Tokyo');
    $date = "%datetime%";
    // 接続元IPアドレス
    $ip = $request->ip();
    // ユーザエージェント
    $user_agent = $request->header('User-Agent');
    // セッションID
    $session_id = \Session::getId();
    // ユーザID
    $user_id = $request->session()->get('MEMBER_3SP_USER');
    if ( empty($user_id) ) {    // ログインしていない場合
        $user_id = $request->session()->get('SAMPLE-3SP-USER');
        if ( empty($user_id) ) {    // 試聴音登録でもログインしていない場合
            $user_id = '';
        } else {    // 試聴音登録でのユーザの場合(全角スペースを入れている)
            $user_id = '　'.$user_id;
        }
    }
    // リファラ
    $referer = $request->headers->get('referer');
    if ( is_null($referer) ) $referer = '';
    // 遷移元画面コード
    $from_screen_cd = $request->has('from_screen_code') ? $request->input('from_screen_code') : $request->input('screen_code');
    // 遷移先画面コード
    $screen_cd = $request->input('screen_code');
    // イベントコード
    $event_cd = $request->input('event_code');
    // HTTPメソッド
    $http_method = '';
    // HTTPパス
    $http_path = '';
    // HTTPボディ
    $http_body = $request->input('body');
    // 出力する変数の配列
    $keys = ['date','ip','user_agent','session_id','user_id','referer','from_screen_cd','screen_cd','event_cd','http_method','http_path','http_body'];
    // ログ書込
    $logger = \App::make('tracking_logger');
    $logger->info('', compact($keys));

    return \Response::json([]);
  }
}
