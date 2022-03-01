<?php

namespace App\Http\Controllers;

use Input;
use DB;
use Validator;
use Closure;
//use Cookie;
use Config;
use Session;
use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\MemberLoginRequest;
use App\Account;
use App\Product;
use App\OnetimeKey;
use App\AccountsProduct;
use App\StethoSound;        //★home呼び出し用
use App\Lib\GlobalFunction;
use App\Lib\Member;    /* telemedica 20170829 */
use App\Lib\IniFile;    /* telemedica 20180109 */
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Agent;
//DS自動ログイン用の固定値（アカウント、製造番号など）
//define('USER_DS_SP', 'test');     //自動ログイン用の第一三共特別アカウント★テスト
//define('USER_DS_SP', 'USER_DS_SP');     //自動ログイン用の第一三共特別アカウント
/*
// DSへの提供終了の為、とりあえず入れないようにアカウント・パスを変更
define('USER_DS_SP', 'daiichisankyo.co.jp-1001');     //自動ログイン用の第一三共特別アカウント
define('PASSWORD_DS_SP', '141-1000-t8w0sj');     //自動ログイン用の第一三共特別アカウント
*/
define('USER_DS_SP', '2019-03-25');     //終了
define('PASSWORD_DS_SP', '20190325');     //終了
define('PRODUCT_NO_DS_SP', '141');     //第一三共特別アカウントの製造番号
//define('PRODUCT_NO_DS1', '21');     //★仮にtestアカウントの製造番号（21-23）の1つめ
define('LOGIN_AUTO_RETURN_URL', env('TMN_URL') . '');     //自動ログイン後の戻り先＝テレメディカネット（.envに記述）

class MemberController extends Controller {
    private $msg_err_user;
    private $msg_err_mail;
    private $msg_err_pass;
    function __construct() {
        date_default_timezone_set('Asia/Tokyo');
        // telemedica 20180109
        /* iniファイルを読み取り、設定 */
        $this->adIni = new IniFile();
        if(Session::get('lang')) App::setLocale(Session::get('lang'));
    }

    // telemedica 20180109
    // 【暫定】慶應大学用の学生番号入力
    private function make_account_user($user,$identity) {
        if(empty($identity)) {  // 入力データがない場合
            return($user);  // 入力値そのまま
        }

        // セパレータ,個人データ配列添字,0:個人データ非必須 1:必須,認証文字列
        list($sep,$id,$status,$auth) = explode(",",$identity);

        $auths = explode($sep,$auth);   // 認証文字列のみ
        $parts = explode($sep,$user);   // 送られてきたユーザID

        $userAuth = $parts; // 認証部分とID部分に分ける為にコピー
        $userIDs = array_splice($userAuth,$id);  // $idは要素数以上でも正常処理
        $userID = implode($sep,$userIDs);    // 個人データ中にセパレータが使われる可能性がある為、文字列として連結して復元

        if($auths === $userAuth) {  // (配列として)認証文字列が同じ場合
            if($status == 1) {  // 個人データ必須の場合
                if(empty($userID)) {    // 個人データが空の場合
                    return(""); // 絶対に認証エラーになるリターン
                } else {
                    // とりあえず、今は何かがあればOK
                    return($auth);
                }
            } else {    // 個人データ非必須の場合
                return($auth);
            }
        } else {    // 認証文字列とは別のユーザIDの場合
            return($user);  // 入力値そのまま
        }
    }

    /**
     * ログイン認証
     * @param $user		ユーザーアカウント
     * @param $password	パスワード
     * @param $product_no	製造番号
     *
     */
    // public function member_login_chk($request, $user, $password, $product_no) {
    public function member_login_chk($request, $user, $password, $product_no, $mail_address) {

        // 現在日時
        $dt_now = Carbon::now();
        //$now = Carbon::now();
		//$dt_now = new Carbon(Carbon::now());

/*
        $hyphen = explode("-",$user);

        if(($hyphen[0] == "kkz") && ($hyphen[1] == "test")) {
            $account_user = "kkz-test";
        } else {
            $account_user = $user;    //通常は入力されたユーザー名でログイン
        }
*/

        $user_id = Account::where('user',$user)->where('deleted_at', NULL)->pluck('user');
        $user_mail = Account::where('email',$mail_address)->where('deleted_at', NULL)->pluck('email');
        //getting the password of corresponsing email
        $pass = Account::where('email',$mail_address)->where('deleted_at', NULL)->pluck('password');

        if(!$user_id){
            $this->msg_err_user = 1;
        }
        if(!$user_mail){
            if(!strpos($mail_address,'@')){
                $this->msg_err_mail = 2;
            }
            else{
                $this->msg_err_mail = 1;
            }
        }
        if($pass != $password){
            $this->msg_err_pass = 1;
        }

        // telemedica 20180109
        // 【暫定】慶應大学用の学生番号入力
        $identity = $this->adIni->getConfig('id_data','identity','false');
        $account_user = $this->make_account_user($user,$identity);

/*
        $account_user = $user;    //通常は入力されたユーザー名でログイン
*/

        $msg = '';
        $msg .= 'user=[' . $user . ']<br>';
        $msg .= 'password=[' . $password . ']<br>';

        $auth = 0;  // 0:NG 1:OK

        $account = Account::where('user', $account_user)->where('email', $mail_address)->where('deleted_at', NULL)->first();       //ユーザーアカウントでアカウントデータを取得
        if ($account) {
            //ユーザーアカウントOK
            $account_id = $account->id;
            $password_hash = $account->password;
            $account_auth = $account->auth;        //重複の可否（0=非許可,1=許可）
            $msg .= 'account_id=[' . $account_id . ']<br>';
            $msg .= 'password_verify=[' . password_verify($password, $password_hash) . '], password_hash=[' . $password_hash . ']<br>';
            $msg .= 'account_auth=[' . $account_auth . ']<br>';
            if (password_verify($password, $password_hash)) {
                //パスワードOK
                //ユーザーアカウントとパスワードの認証はOK
                $int_product_no = intval($product_no);      //シリアル番号
                $product = ($product_no == "") ? "" : Product::where('product_no', $product_no)->where('deleted_at', NULL)->first();
                if ($product != "") {
                	//シリアル番号OK
                    $product_id = $product->id;      //製品ID
                    $msg .= 'product_id=[' . $product_id . ']<br>';

                    //AccountsProductsで、アカウントIDと製品IDが一致するデータを調べて
                    $ap1 = \App\AccountsProduct::where('account_id', $account_id)->where('product_id', $product_id)->where('deleted_at', NULL)->first();
//                    $ap1 = \App\AccountsProduct::where('account_id', $account_id)->where('product_id', $product_id)->first();
                    if (!$ap1) {     //存在しなければNG
                        $auth = 0;  // 0:NG 1:OK

                    } else {
						//存在すればOK

						//$account_auth = 1;        //★テスト　重複の可否（0=非許可,1=許可）
                        if ($account_auth === 1) {
                            //重複許可なら
                            //認証OK（重複チェックは行わない）
                            $auth = 1;  // 0:NG 1:OK
                        } else {
/* 後勝ち確認 telemedica 20170829 */
/*
$login_id = $ap1->login_id;      //ログインID（UUID）
$login_at = $ap1->login_at;      //最終ログイン日時
// Session有効時間の設定と取得 20170829 telemedica
$slt = Member::member_lifetime();
// 有効期間確認 0:有効期間外 1:有効期間内 telemedica 20170829
$duration = Member::member_check_time($login_at,$slt);

// *** 引数に「ログインOK」があった場合、下記のifはせずに$auth=1とする処理 ***

if($login_id != NULL && $duration == 1) { // ログイン中で有効期間内
    $msg_err = 1;
    return $msg_err;
}
*/
/* 後勝ち確認 telemedica 20170829 */

$auth = 1;  // 0:NG 1:OK
/***
 ★後勝ちにしたので、以下の判定は不要。
                            //重複チェック
                            //$valid_time = 12;   //有効時間（単位：時間）
                            //$valid_time = 12;
                            $valid_time = env('MEMBER_3SP_LOGIN_VALID_TIME');
                            $session_lifetime = $valid_time * 60;       //時間を分に変換
                            $login_id = $ap1->login_id;      //ログインID（UUID）
                            $login_at = $ap1->login_at;      //最終ログイン日時
                            $dt_login_at = Carbon::parse($login_at);
                            $dt_valid = $dt_login_at->addMinute($session_lifetime);
                            //$dt_valid = $dt_login_at->addHour($valid_time);

                            $msg .= 'now=[' . $dt_now . ']<br>';
                            $msg .= 'login_at=[' . $login_at . ']<br>';
                            $msg .= 'valid=[' . $dt_valid . ']<br>';

                            if ($login_at === null) {       //最終ログイン日時がNULL
                                //認証OK
                                $auth = 1;  // 0:NG 1:OK
                            } elseif ($dt_now > $dt_valid) {      //最終ログイン日時から指定時間（12時間）以上経過していれば、
                                //認証OK
                                $auth = 1;  // 0:NG 1:OK
                            } else {
                                $auth = 0;  // 0:NG 1:OK
                            }
***/
                        }
                    }
                }
                else{
                    $auth = 1; //OK, since email exists
                    $product_id = "";
                }
            }
        }

        $msg .= 'auth=[' . $auth . ']<br>';

        if ($auth === 0) {
            //NG
            $msg_err = $msg;
        } else {

            $msg_err = '';

            //excempt test_gs_002 user for login security
            if($request->user == 'test_gs_002'){

                //return success message
                $msg_err = "OK";

                //then call the function to remove and udpate the session
                GlobalFunction::removeUpdateSession($dt_now, $account_id, $user, $product_id, $mail_address);

            }else{

                //check if there is token in local stored
                if(isset($_COOKIE["bwtk"])){

                    //check if there is a cookie stored in local
                    $local_bwtk = isset($_COOKIE["bwtk"]) ? $_COOKIE["bwtk"] : "";

                    //get current account id of the user
                    $current_account_id = Account::select('id')->where('user', $request->input("user"))->get();
                    $account_id = $current_account_id[0]["id"];

                    //check if the cookie stored exists in db
                    $isExistsBwtk = OnetimeKey::select("bwtk", "status")
                        ->where("customer_id", $account_id)
                        ->where("bwtk", $local_bwtk)
                        ->get();

                    //if local token is matched to db token
                    if(count($isExistsBwtk) > 0){
                        if ($isExistsBwtk[0]->status == 3) {
                            $msg_err = "Your license key has been stopped";
                        } else {
                            $msg_err = "OK";

                            //then call the function to remove and udpate the session
                            GlobalFunction::removeUpdateSession($dt_now, $account_id, $user, $product_id, $mail_address);
                        }
                    }else{

                        //return an error message
                        $msg_err = trans('login.token');
                    }

                }else{

                    //return an error message
                    $msg_err = trans('login.token');

                }

            }
        }

        return $msg_err;
//        return $auth;
    }

    /**
     * ログインコードで認証する（ログイン画面を表示しない）
     *
     */
    //public function getMemberLoginCode($hash) {
    public function getMemberLoginCode($code) {

        $msg_err = '';

        //★ここでデコード処理
        //$code = str_replace('/', '／', $hash);        //TMN側での処理
        $hash = str_replace('／', '/', $code);      //元のhash値に戻す
        $today = date("Ymd");
        $text = 'USER_DS_SP,' . date("Ymd");        //認証文字列（同日は有効）
        //$hash =  password_hash($code, PASSWORD_DEFAULT);
        if (password_verify($text, $hash)) {
            //if ($login_code === $code) {
            $user = USER_DS_SP;
            $password = PASSWORD_DS_SP;
            $product_no = PRODUCT_NO_DS_SP;
            //$product_no = 1;
        } else {
            $user = '';
            $password = '';
            $product_no = 0;
        }

        $msg_err = MemberController::member_login_chk("", $user, $password, $product_no, "");       //認証
        // もし自動ログインで第一三共アカウントなら
        if ($msg_err !== '') {        //認証NG
            $msg = '認証情報を入力してください。<br>';
            //$msg .= 'login_code=' . $login_code . '<br>';

            //removed for the meantime
            // $msg_err .= 'code=' . $code . '<br>';
            // $msg_err .= 'hash=' . $hash . '<br>';

            $view = 'member_login';    // NGならログイン画面へ
            return view($view, compact('msg', 'msg_err'));
        } else {
            // 自動ログインOK
            if ($user === USER_DS_SP) {     //第一三共なら
                // ログイン画面を表示せずテレメディカネットにリダイレクト
                //              header('Location: ' . LOGIN_AUTO_RETURN_URL);
                //              exit;
                $view = 'member_jump';    // ★jump画面へ
                return view($view, compact('msg', 'msg_err'));
            } else {
                // telemedica 20170615
                return redirect()->action('HomeController@index');

                //3SPのホーム画面へ
                // 新着の聴診音
/*
                $stetho_sounds = StethoSound::publicNewAll()->orderBy('created_at', 'desc')->get();
                return view('home', compact('stetho_sounds'));
*/
            }
        }
        exit;
    }

    /**
     * ログイン画面を表示する
     *
     */
    public function getMemberLogin() {
        if (Session::get('bwtk')) {
            // set cookie to local browser
            setcookie( "bwtk", Session::get('bwtk'), strtotime( '+1 year' ));
        }

        $msg = '';
        $msg_err = '';
        $id_err = 0;
        $mail_err = 0;
        $pass_err = 0;

        $login_user = Session::get('MEMBER_3SP_USER');
        if ($login_user == '') {
            $msg = '';
        } else {
            $msg_err = '★' . $login_user . trans('login.login_with_err');
        }

        $view = 'member_login';    // ログイン画面
        return view($view, compact('msg', 'msg_err', 'id_err', 'mail_err', 'pass_err'));
    }

    //public function postMemberLogin(MemberLoginRequest $request, Closure $next) {
    public function postMemberLogin(MemberLoginRequest $request) {
        $id_err = 0;
        $mail_err = 0;
        $pass_err = 0;
        $msg_err = '';

        //入力されたリクエスト値を取得
        $user = $request->input('user');
        $password = $request->input('password');
        // $product_no = $request->input('product_no');
        $mail_address = $request->input('mail_address');

        // ★認証処理
        $msg_err = MemberController::member_login_chk($request, $user, $password, "", $mail_address);

        $login_user = $request->session()->get('MEMBER_3SP_USER');

        if ($msg_err != 'OK') {
            if($this->msg_err_user == 1){
                $id_err = trans('login.user_id_err');
            }

            if($this->msg_err_mail == 1){
                $mail_err = trans('login.user_mail_err');
            }
            if($this->msg_err_mail == 2){
                $mail_err = trans('login.email_validate');
            }

            if($this->msg_err_pass == 1 && $msg_err != trans('login.token')){
                $pass_err = trans('login.user_pass_err');
            }
            /* 後勝ち確認 telemedica 20170829 */
            $request->flash();
            $msg = trans('login.error_login');
            $msg = ($msg_err == trans('login.token') ? "<a style='color:red' href='". route("reapply_browser") ."'>" . trans('login.token') . "</a>" : trans('login.error_login'));
            $view = 'member_login';    // NGならログイン画面へ
            return view($view, compact('id_err','mail_err','pass_err','msg', 'msg_err'));


        } else {
            return redirect()->action('HomeController@index');
        }
    }

    /**
     * ログアウト
     *
     */
    //    public function getLogout(Request $request) {
    public function getMemberLogout() {

        $msg = '';
        $msg_err = '';
        $id_err = 0;
        $mail_err = 0;
        $pass_err = 0;
        //get the current account id
        $current_account_id = session("current_account_id");

        if($current_account_id){
            //THEN UPDATE THE TOKEN FROM THE TABLE
            $not_logged_id = DB::select('UPDATE onetime_keys SET status = 0 WHERE customer_id = ?', [$current_account_id]);
        }

        // //check if the cookie for browser token exists
        // if(isset($_COOKIE["bwtk"])){
        //     //then remove the cookie for browser token
        //     setcookie("bwtk", "", time() - 3600);
        // }

        //認証関連のsessionを取得
        $login_user = Session::get('MEMBER_3SP_USER');
        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $product_id = Session::get('MEMBER_3SP_PRODUCT_ID');

        // 認証確認 20170829 telemedica
/*
ログインしている「正規ユーザ」の場合はlogin_idをNULLにupdateし、ログインしていない「元・ログインユーザ」の場合はupdateを行わない。また、ログインしてもしていなくてもセッション関連はクリアする。

要は「ログインしている場合は正規ユーザとしてDBの処理をし、ログインに関係なく、ログアウトに来た場合はセッションはブラウザ毎なのでクリアする」という仕様。
*/
        $auth = Member::member_auth();

        //login_userが空でも以下のクリア処理は実行する
        //対象product_idのUUIDとログイン日時をクリア
        if($auth == 1) {    //認証OK(ログインしている正規ユーザ)の場合 20170829 telemedica
            AccountsProduct::where('account_id', $account_id)->where('product_id', $product_id)->where('deleted_at', NULL)->update(['login_id'=>NULL]);
        }

        //認証関連のsessionをクリア
        Session::forget('MEMBER_3SP_USER');
        Session::forget('MEMBER_3SP_LOGIN_ID');
        Session::forget('MEMBER_3SP_LOGIN_AT');
        Session::forget('MEMBER_3SP_ACCOUNT_ID');
        Session::forget('MEMBER_3SP_PRODUCT_ID');
        Session::forget('current_account_id');

        setcookie("trialaudio","",time() - 1800);  // 認証

        if ($login_user == '') {
            $msg = '';
        } else {
            $msg_err = $login_user. trans('login.logged_out');
        }
        $view = 'member_login';    // ログイン画面を表示
        return view($view, compact('msg', 'msg_err', 'id_err','mail_err','pass_err'));
    }

    /**
     * Activate Login User.
     *
     * @param s $code [description]
     *
     * @return [type] [description]
     */
    public function activateLoginUser($user_id,$code)
    {
        $agent = new Agent();
        $updated_at = date('Y-m-d G:i:s');
        $browserUsed = $agent->browser()." on ".$agent->platform();
        $bwtk = GlobalFunction::custom_encryptor($code, csrf_token());
        $onetime_key_accts = new OnetimeKey();
        
        $available_key = $onetime_key_accts->where('onetime_key', $code)->where('status', 0)->whereNull('customer_id')->first();
        if($available_key){
            $available_key->customer_id = $user_id;
            $available_key->bwtk = $bwtk;
            $available_key->status = 1;
            $available_key->browser_used = $browserUsed;
            $available_key->save();

            $updateAccount = DB::table('accounts')
                ->where("id", $user_id)
                ->update(['deleted_at' => NULL]);
            return redirect('member_login');
        }else{
            return view('verify_alert');
        }
        
    }

}
