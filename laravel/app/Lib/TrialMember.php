<?php

namespace App\Lib;

use Config;
use Session;
use Carbon\Carbon;
use DB;
use Hash;
use App\Lib\Member;

/* 認証処理 */
class TrialMember
{
    // 下記1.～3.の内、どれかがOKならOKを返す、全部確認版
    public static function trial_auth($ssid) {
        $auth = 0;  //認証（0:NG 1:OK 2:OK(試聴音登録者)、デフォルトは0:NG）

        $auth1 = TrialMember::trial_auth_ssid($ssid);
        $auth2 = TrialMember::trial_auth_reg();
        $auth3 = TrialMember::trial_auth_3splogin();

        if($auth2 == 1) {   // 試聴音登録者の場合
            return(2);  // 2を返す
        }

        $tmp = $auth1 + $auth2 + $auth3;    // 0以外があるか

        if($tmp != 0) { // 0以外がある場合
            $auth = 1;  // OK
        }

        return($auth);
    }

    // 1.許可ssidか?
    public static function trial_auth_ssid($ssid) {
        $auth = 0;  //認証（0:NG 1:OK、デフォルトは0:NG）
/****************************************************************/
/* 許可するssidが増えたり、頻繁にある場合はDBかIniFileに変更 */
/* あるかどうかも不明なので、とりあえずは固定で */
/****************************************************************/
        $ssid_OK = array(11,2); // 許可するssidを記述

        if(in_array($ssid,$ssid_OK)) {  // 許可ssidに含まれている場合
/* ここでcookieをonにすると削除する所がない。。。余り使いたくないな */
//.htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
            setcookie("audioaccess","true");    // .htaccessで許可
            $auth = 1;  // OK
        }

        return($auth);
    }

    // 2.試聴音登録者か?
    // セッションのみで判断。後勝ちは見ない
    public static function trial_auth_reg() {
        $auth = 0;  //認証（0:NG 1:OK、デフォルトは0:NG）

        $user = Session::get('SAMPLE-3SP-USER');    // ユーザ

        if($user != '') {   // セッションがある場合
            $auth = 1;  // OK
        }

        return($auth);
    }

    // 3.3spでログインしているか?
    public static function trial_auth_3splogin() {
        $auth = 0;  //認証（0:NG 1:OK、デフォルトは0:NG）

        $auth = Member::member_auth();

        return($auth);
    }
}

