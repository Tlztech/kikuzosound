<?php

namespace App\Lib;

use Config;
use Session;
use Carbon\Carbon;
use App\AccountsProduct;
use Ramsey\Uuid\Uuid;

class Member
{
    /**
     * UUIDを取得
     * この関数は、MemberControllerとMemberOnlyで共通なのでLibへ統合
     */
    public static function member_uuid() {
        $uuid = Session::get('MEMBER_3SP_LOGIN_ID');        // Sessionから取得
        if ($uuid == '') {      //UUIDが未設定なら
//            $uuid = uniqid();       //UUIDを生成して
            //$uuid = uuid_create(UUID_TYPE_RANDOM); // 種がランダム
            $uuid = Uuid::uuid1()->toString();
            
            Session::put('MEMBER_3SP_LOGIN_ID', $uuid);     //Sessionに保存
        }

        return $uuid;
    }

    /**
     * 認証確認 20170829 telemedica
     * 戻り値　認証（0:NG 1:OK、デフォルトは0:NG）
     *
     * 旧：ログイン済みの会員ではない場合、ログイン画面を表示する
     * →ログイン済みでも、DBのログイン情報を参照し、UUIDが一致しなければ（ の端末から同一IDでログインした可能性があるため）、ログイン画面を表示する
     */
    public static function member_auth() {
        $auth = 0;  //認証（0:NG 1:OK、デフォルトは0:NG）

        // Session有効時間の設定と取得 20170829 telemedica
        $session_lifetime = Member::member_lifetime();

        // SessionからUUIDを取得(Libへ統合) 20170829 telemedica
        $uuid = Member::member_uuid();

        // UUID以外のSession情報を取得
        $LoginUser = Session::get('MEMBER_3SP_USER');
        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $product_id = Session::get('MEMBER_3SP_PRODUCT_ID');

        // DBからUUIDが一致するログイン情報を取得
        $ap1 = AccountsProduct::where('login_id', $uuid)->where('deleted_at', NULL)->first();
        if ($ap1) {     //DBにログイン情報が存在（存在しなければログインしていない）
            if ($account_id == $ap1->account_id) {      //アカウントIDが一致し
                $login_at = $ap1->login_at;      //最終ログイン日時

                // 有効期間確認 telemedica 20170829
                $duration = Member::member_check_time($login_at,$session_lifetime);
                if($duration == 1) {    // 0:有効期間外 1:有効期間内
                    $auth = 1;  // 0:NG 1:OK
                }
            }
        }
        // telemedica 20171205
        else {
            $LoginUser = Session::get('MEMBER_3SP_USER');
            $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');
            $account_auth = \App\Account::where('id',$account_id)->value('auth');
            if(($LoginUser != '') && ($account_auth == 1)) {
                $auth = 1;
            } else {
                //認証関連のsessionをクリア
                //「後勝ち」をされた時にセッションが残るので削除
                Session::forget('MEMBER_3SP_USER');
                Session::forget('MEMBER_3SP_LOGIN_ID');
                Session::forget('MEMBER_3SP_LOGIN_AT');
                Session::forget('MEMBER_3SP_ACCOUNT_ID');
                Session::forget('MEMBER_3SP_PRODUCT_ID');

// ここで「試聴音登録」を削除するとページ遷移だけでログアウトするので削除
//                setcookie("trialaudio","",time() - 1800);  // 認証
            }
        }
        // telemedica 20171205

        return $auth;
    }

    /**
     * 有効期間の設定と取得
     */
    public static function member_lifetime() {
        $valid_time = env('MEMBER_3SP_LOGIN_VALID_TIME');       //有効時間（単位：時間）
        if (!is_numeric($valid_time)) {
            $valid_time = 12;       //指定が無ければデフォルト値12時間
        }
        $session_lifetime = intval(60 * $valid_time);        //時間を分に変換

        Config::set('session.lifetime', $session_lifetime);      //Session有効時間（分）を設定

        return $session_lifetime;
    }

    /**
     * 有効期間確認 20170829 telemedica
     * 引数　login_at(最終ログイン日時) session_lifetime(分の有効時間)
     * 戻り値　0:有効期間外 1:有効期間内
     */
    public static function member_check_time($login_at,$session_lifetime) {
        date_default_timezone_set('Asia/Tokyo');    // タイムゾーン

        $duration = 0;

        if ($login_at !== null) {       //最終ログイン日時の記録があり
            $dt_now = new Carbon(Carbon::now());        //現在時刻
            $dt_login_at = Carbon::parse($login_at);
            $dt_valid = $dt_login_at->addMinute($session_lifetime);
            if ($dt_now < $dt_valid) {      //有効期間内なら
                $duration = 1;  // 0:有効期間外 1:有効期間内
            }
        }

        return $duration;
    }
}

