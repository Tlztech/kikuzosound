<?php

namespace App\Http\Controllers;

use Session;
use App\TrialMembers;   // telemedica 20190228
use App\Account;
use Hash;
use Illuminate\Http\Request;

// お気に入り登録・削除のコントローラ
class AjaxSampleLogin extends Controller
{
  /*
    登録者削除(暫定版)
    確認作業が終わったら、削除する事
  */
  public function ajax_sampledelete(Request $request)
  {
    // ajax以外は対象外
    if(!($request->ajax())) {
      return(3);    // JS側で処理はしないが、一応
    }

    // 何故かタイムゾーンが日本にならないので、設定
    date_default_timezone_set('Asia/Tokyo');

    $mail = Session::get('SAMPLE-EDIT-KEY');  // 削除(getパラメータ)
    $mail = $this->decryptMail($mail);
    exec("/home/pharmaid/tools/3sp-trial/delete-trialmember.sh $mail");

    /*
    .htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
    セットしているのは、
    ajax_samplelogin(この下にある関数)
    QuizPacksController.php
    ContentsController.php
    TrialMember.php
    */
    setcookie("audioaccess","",time() - 1800);  // .htaccess用削除
    setcookie("trialaudio","",time() - 1800);  // 認証
    setcookie("imgaccess","",time() - 1800);  // 認証(個人確認の画像)

    $this->deleteSession(); // 関連セッション削除
  }

  /*
    ログアウト
  */
  public function ajax_samplelogout(Request $request)
  {
    // ajax以外は対象外
    if(!($request->ajax())) {
      return(3);    // JS側で処理はしないが、一応
    }

    // 何故かタイムゾーンが日本にならないので、設定
    date_default_timezone_set('Asia/Tokyo');

    /*
    .htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
    セットしているのは、
    ajax_samplelogin(この下にある関数)
    QuizPacksController.php
    ContentsController.php
    TrialMember.php
    */
    setcookie("audioaccess","",time() - 1800);  // .htaccess用削除
    setcookie("trialaudio","",time() - 1800);  // 認証
    setcookie("imgaccess","",time() - 1800);  // 認証(個人確認の画像)

    $this->deleteSession(); // 関連セッション削除
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function ajax_samplelogin(Request $request)
  {
    // ajax以外は対象外
    if(!($request->ajax())) {
        $array['status'] = 0;   // 0:NG 1:OK
        $array['edit'] = 0; // edit
        $array['type'] = 0; // 0:試聴音登録者 1:3spアカウント

        return response()->json($array); // JS側で処理はしないが、一応
    }

    // 何故かタイムゾーンが日本にならないので、設定
    date_default_timezone_set('Asia/Tokyo');

    $args = $request->all();  // 引数全て
    $user = $args['account'];  // アカウント(メアドor3sp正規ログイン)
    $edit = $args['account'];  // アカウント(getパラメータ用)
    $password = $args['password'];  // パスワード

    $auth = 0;  // 0:NG 1:OK
    $type = 0;  // 0:試聴音登録者 1:3spアカウント

    // 認証処理
    // 本登録にあるメアドでselect
    $TrialMembers = TrialMembers::where('mail',$user)->where('status_flag',1)->where('deleted_at', NULL)->first();

    if(empty($TrialMembers)) {  // 本登録されているメアドがない場合
        $auth = 0;  // NG
    } else {    // 本登録されているメアドがある場合
        // パスワードのハッシュ(ちょっと特殊)
        // [bcrypt laravel]で検索
        // https://www.messiahworks.com/archives/10584
        // https://readouble.com/laravel/5.1/ja/hashing.html

        if(Hash::check($password, $TrialMembers->password)){    // OKの場合
            $auth = 1;  // OK

            // 試聴音でOKだった場合、DBのidを暗号化(getパラメータで使用する為)
            $id = $TrialMembers->id;    // DBのid
            $user = $this->encryptID($id);  // DBのidを暗号化した物を入れ直す
        } else {
            $auth = 0;  // NG
        }
    }

    // 試聴音利用登録者ではない場合、3spアカウントを確認
    if($auth == 0) {
        $account = Account::where('user', $user)->where('deleted_at', NULL)->first();       //ユーザーアカウントでアカウントデータを取得

        if($account) {  //ユーザーアカウントOK
            $password_hash = $account->password;    // ハッシュされたパスワード

            if(password_verify($password, $password_hash)) {    //パスワードOK
                $auth = 1;  // OK
                $type = 1;  // 1:3spアカウント
            }
        }
    }

    // 認証OKの場合、セッションに登録
    if($auth == 1) {    // OK
        //.htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
        setcookie("audioaccess","true");    // .htaccessで許可
        setcookie("trialaudio","true");    // 認証OK

        // メアドの暗号化だが、今は特に意味はない(復号化もしていない)
        // もし、更にセキュリティをかけるなら復号化して比較
        $edit = $this->encryptMail($edit);  // getパラメータ

        $this->deleteSession(); // 関連セッション削除
        $this->setSession($user,$type,$edit);   // 関連セッション設定
    } else {    // NG
        setcookie("audioaccess","",time() - 1800);  // .htaccess用削除
        setcookie("trialaudio","",time() - 1800);  // 認証

        $this->deleteSession(); // 関連セッション削除
    }

    $array['status'] = $auth;   // 0:NG 1:OK
    $array['edit'] = $edit; // getパラメータ
    $array['type'] = $type; // 0:試聴音登録者 1:3spアカウント

    return response()->json($array);
  }

  /* セッション削除 */
  private function deleteSession() {
    Session::forget('SAMPLE-3SP-USER');     // 削除(認証)
    Session::forget('SAMPLE-USER-TYPE');    // 削除(試聴音or3spアカウント)
    Session::forget('SAMPLE-EDIT-KEY');     // 削除(getパラメータ)
    Session::forget('SAMPLE-USER-PASSWORD');    // 削除(パスワード)
  }

  /* セッション設定 */
  private function setSession($user,$type,$edit) {
    Session::put('SAMPLE-3SP-USER',$user);  // 登録(認証)
    Session::put('SAMPLE-USER-TYPE',$type); // 登録(試聴音or3spアカウント)
    Session::put('SAMPLE-EDIT-KEY',$edit);  // 削除(getパラメータ)
  }

  /* DBのidを暗号化 */
  // 復号化はRMailFormController.php
  private function encryptID($id) {
    $idsalt = 19661030; // idだけだと短すぎるのとソルトになるので
    $id = $id + $idsalt;    // idにソルトを足す(復号化では引く)
    $salt = 'TMI3sp045Salt875xyz1924'; // ソルト

    // 暗号化方式(ecbよりcbcの方が強固だがivが必要になる)
    // ecbとcbcの違い
    // http://yut.hatenablog.com/entry/20140228/1393543543
    $method = 'aes-128-ecb';

    $encrypted = openssl_encrypt($id,$method,$salt); // 暗号化

    // base64をURLSafeに
    $urlsafe = str_replace(array('+','=','/'),array('_','-','.'),$encrypted);

    return($urlsafe);
  }

  /* メアドの暗号化 */
  // getパラメータに使用するだけなので特に復号化は行わない
  private function encryptMail($mail) {
    $salt = 'before1926shouwa1989heisei2019reiwaafter'; // ソルト

    // 暗号化方式(ecbよりcbcの方が強固だがivが必要になる)
    // ecbとcbcの違い
    // http://yut.hatenablog.com/entry/20140228/1393543543
    $method = 'aes-128-ecb';

    $encrypted = openssl_encrypt($mail,$method,$salt); // 暗号化

    // base64をURLSafeに
    $urlsafe = str_replace(array('+','=','/'),array('_','-','.'),$encrypted);

    return($urlsafe);
  }

  /* メアドのidを復号化 */
  private function decryptMail($mail) {
    $method = 'aes-128-ecb'; // 暗号化方式
    $salt = 'before1926shouwa1989heisei2019reiwaafter'; // ソルト
    $encrypted = str_replace(array('_','-','.'),array('+','=','/'),$mail); // URLSafeをbase64に

    $mail = openssl_decrypt($encrypted,$method,$salt); // 復号

    return($mail);    // 復号化したメアド
  }
}
