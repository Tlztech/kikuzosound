<?php
namespace App\Http\Middleware;

use Closure;  
use Illuminate\Contracts\Routing\TerminableMiddleware;  
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\LineFormatter;
use \URL;

/**
 * ページ遷移で発生したユーザ操作のログを記録するミドルウェア
 * [注意]
 * ・ページ遷移を伴わない場合は LogController に記述している。
 * ・クイズ中のHTML部分ページ遷移は QuizPackController に記述している。
 */
class LogAfterRequest implements TerminableMiddleware {

  public function handle($request, Closure $next)
  {
    return $next($request);
  }

  public function terminate($request, $response)
  { 
    // quizpacks/* の場合は QuizPackController にログ処理を記述しているので何もしない
    // ログ処理中に余分にDBアクセスしないようにするため、また、本コードの可読性のため、上記の方法を選択した
    $is_route_of_quiz = (strpos($request->path(), 'quizpacks/') !== false);
    if ( $is_route_of_quiz ) {
      return;
    }
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
    if ( empty($user_id) ) $user_id = '';
    // リファラ
    $referer = $request->headers->get('referer');
    if ( is_null($referer) ) $referer = '';
    // 遷移元画面コード
    $from_screen_cd = $this->fecthFromScreenCd($request, $referer);
    // 遷移先画面コード
    $screen_cd = $this->fecthScreenCd($request);
    // イベントコード
    $event_cd = $this->fecthEventCd($request, $screen_cd);
    // HTTPメソッド
    $http_method = $request->method();
    // HTTPパス
    $http_path = $request->path();
    // HTTPボディ
    $http_body = $this->createHttpBody($request, $screen_cd, $event_cd);
    // 出力する変数の配列
    $keys = ['date','ip','user_agent','session_id','user_id','referer','from_screen_cd','screen_cd','event_cd','http_method','http_path','http_body'];
    // ログ書込
    $logger = \App::make('tracking_logger');
    $logger->info('', compact($keys));
  }

  /**
   * 遷移元画面コードを引き当てます
   * @param $request         リクエストオブジェクト
   * @param $referer         リファラ
   * @return 遷移先画面コード
   */
  private function fecthFromScreenCd($request, $referer) {
    $from_screen_cd = "";
    // 外部ドメインからのアクセスは弾く
    if ( strpos(env('APP_URL'), $request->getHost()) ) {
      // Referer から前画面のコードを取得する
      $url_path = parse_url($referer, PHP_URL_PATH);
      if ( $url_path != FALSE ) {
        if ( $url_path == "/" ) {
          $last_path = "home";
        }
        else {
          $last_paths = array_slice(explode('/', $url_path), -1, 1);
          $last_path = ( count($last_paths) == 1 ) ? $last_paths[0] : "";
        }
        $from_screen_cd = $this->getScreenCdFromTable($last_path);
      }
    }
    return $from_screen_cd;
  }

  /**
   * 遷移先画面コードを引き当てます
   * @param $request         リクエストオブジェクト
   * @return 遷移先画面コード
   */
  private function fecthScreenCd($request) {
    $route_name = $request->route()->getName();
    // ルート名がnullの場合 home とする
    if ( is_null($route_name) ) $route_name = 'home';
    $screen_cd = $this->getScreenCdFromTable($route_name);
    return $screen_cd;
  }

  /**
   * イベントコードを引き当てます
   * @param $request         リクエストオブジェクト
   * @param $screen_cd       遷移先画面コード
   * @return イベントコード
   */
  private function fecthEventCd($request, $screen_cd) {
    $route_name = $request->route()->getName();
    $event_cd = 'INIT';
    // お問い合わせフォーム画面かつPOSTの場合は送信ボタン押下イベントとみなす
    if ( $request->method() == 'POST' && $screen_cd == 'CONTACT_FORM_CONFIRM' && $route_name == 'contact_form_send_mail' ) {
      $event_cd = 'SEND';
    }
    // 登録フォーム画面かつPOSTの場合は送信ボタン押下イベントとみなす
    if ( $request->method() == 'POST' && $screen_cd == 'REGISTER_FORM_CONFIRM' && $route_name == 'register_form_send_mail') {
      $event_cd = 'SEND';
    }
    // 申込フォーム画面かつPOSTの場合は送信ボタン押下イベントとみなす
    if ( $request->method() == 'POST' && $screen_cd == 'APPLI_FORM_CONFIRM' && $route_name == 'appli_form_send_mail') {
      $event_cd = 'SEND';
    }
    // 解約フォーム画面かつPOSTの場合は送信ボタン押下イベントとみなす
    if ( $request->method() == 'POST' && $screen_cd == 'CANCEL_FORM_CONFIRM' && $route_name == 'cancel_form_send_mail') {
      $event_cd = 'SEND';
    }
    // キーワードがセットされている場合、検索とみなす
    if ( ($request->exists('keyword') || $request->exists('filter') || $request->exists('sort')) && $screen_cd == 'CONTENTS' ) {
      $event_cd = 'SEARCH';
    }
    return $event_cd;
  }

  /**
   * 指定されたキーで画面コードを照会します。存在しない場合は空文字を返します。
   * @param $key         キー
   * @return 画面コード
   */
  private function getScreenCdFromTable($key) {
    $table = [
      'home'                      => 'HOME',
      'contents'                  => 'CONTENTS',
      'quizpacks'                 => 'QUIZ_PACKS',
      'speaker'                   => 'SPEAKER',
      'about'                     => 'ABOUT',
      'terms'                     => 'TERMS',
      'privacy'                   => 'PRIVACY',
      'news'                      => 'NEWS',
      'faq'                       => 'FAQ',
      'contact'                   => 'CONTACT',
      'contact_form'              => 'CONTACT_FORM',
      'contact_form_confirm'      => 'CONTACT_FORM_CONFIRM',
      'contact_form_send_mail'    => 'CONTACT_FORM_CONFIRM',
      'register'                  => 'REGISTER',
      'register_form'             => 'REGISTER_FORM',
      'register_form_confirm'     => 'REGISTER_FORM_CONFIRM',
      'register_form_send_mail'   => 'REGISTER_FORM_CONFIRM',
//      'appli'                     => 'APPLI',
      'appli_form'                => 'APPLI_FORM',
      'appli_form_confirm'        => 'APPLI_FORM_CONFIRM',
      'appli_form_send_mail'      => 'APPLI_FORM_CONFIRM',
      'cancel_form'               => 'CANCEL_FORM',
      'cancel_form_confirm'       => 'CANCEL_FORM_CONFIRM',
      'cancel_form_send_mail'     => 'CANCEL_FORM_CONFIRM',
      'vest'                      => 'VEST',
      'use'                       => 'USE',
      'member_login'              => 'MEMBER_LOGIN',
      'member_login_code'         => 'MEMBER_LOGIN_CODE',
      'member_login_post'         => 'MEMBER_LOGIN_POST',
      'member_logout'             => 'MEMBER_LOGOUT',
      'member_jump'               => 'MEMBER_JUMP',
      'video'                     => 'VIDEO',
      'videofree'                 => 'VIDEOFREE',
      'casestudy'                 => 'CASESTUDY',
      'nl_backnumber'             => 'BACKNUMBER',
      'nl_001'                    => 'NL_001',
      'nl_002'                    => 'NL_002',
      'nl_003'                    => 'NL_003',
      'nl_004'                    => 'NL_004',
      'list'                      => 'LIST',
      'private'                   => 'PRIVATE',
      'corporate'                 => 'CORPORATE',
    ];
    return isset($table[$key]) ? $table[$key] : "";
  }

  /**
   * ログに記録するHTTPボディの連想配列を生成します
   * @param $request         リクエストオブジェクト
   * @param $screen_cd       遷移先画面コード
   * @param $event_cd        イベントコード
   * @return HTTPボディの連想配列
   */
  private function createHttpBody($request, $screen_cd, $event_cd) {
    // 返却する連想配列
    $http_body = [];
    // 聴診音を検索した場合
    if ( $screen_cd == 'CONTENTS' && $event_cd == 'SEARCH' ) {
      /**
       * {
       *   "keyword": "入力された検索文字列",
       *   "filter": "聴診音種別", // ALL|心音|肺音|腸音|その他
       *   "sort": "desc"
       * }
       */
      $http_body['keyword'] = $request->has('keyword') ? $request->input('keyword') : "";
      $http_body['filter']  = $request->has('filter')  ? $request->input('filter')  : "ALL";
      $http_body['sort']    = $request->has('sort')    ? $request->input('sort')    : "desc";
      $http_body['page']    = $request->has('page')    ? $request->input('page')    : "1";
      // フィルタのIDを ALL|心音|肺音|腸音|その他 に置換
      if ( $http_body['filter'] == 2 ) $http_body['filter'] = '心音';
      if ( $http_body['filter'] == 1 ) $http_body['filter'] = '肺音';
      if ( $http_body['filter'] == 3 ) $http_body['filter'] = '腸音';
      if ( $http_body['filter'] == 9 ) $http_body['filter'] = 'その他';
      return $http_body;
    }
    // お問合わせ確認画面の初期表示、または、お問合わせフォーム確認画面で送信の場合
    if ( ( $screen_cd == 'CONTACT_FORM_CONFIRM' && $event_cd == 'INIT') || ( $screen_cd == 'CONTACT_FORM_CONFIRM' && $event_cd == 'SEND') ) {
      /**
       * {
       *   "name": "お名前",
       *   "mail": "メールアドレス",
       *   "tel": "電話番号",
       *   "kind": "デモ機貸出し(1)/ご購入について(2)/その他(3)",
       *   "group": "法人(施設)名",
       *   "question": "お問合わせ内容"
       * }
       */
      $http_body['name']     = $request->input('name');
      $http_body['mail']     = $request->input('mail');
      $http_body['tel']      = $request->input('tel');
      $http_body['kind']     = $request->input('kind');
      $http_body['group']    = $request->input('group');
      $http_body['question'] = $request->input('question');
      return $http_body;
    }
    // ログインor登録フォームor申込の場合は送信されたデータをすべて出力する
    if ( $request->method() == 'POST' && ( $screen_cd == 'REGISTER_FORM_CONFIRM' || $screen_cd == 'MEMBER_LOGIN_POST' || $screen_cd == 'APPLI_FORM_CONFIRM' || $screen_cd == 'CANCEL_FORM_CONFIRM')) {
      $http_body = $request->all();
      if ( !empty($http_body) ) {
        return $http_body;
      }
    }
    // 該当しない場合は空文字を返却する
    return "";
  }
}
