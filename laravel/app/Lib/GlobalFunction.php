<?php

namespace App\Lib;

use Config;
use Session;
use Carbon\Carbon;
use App\AccountsProduct;
use App\OnetimeKey;
use Ramsey\Uuid\Uuid;
use DB;
use App\Accounts;

class GlobalFunction
{
    /**
     * will remove the session and insert new session
     * and update AccountProduct the date for login_at
     */
    public static function removeUpdateSession($dt_now, $account_id, $user, $product_id, $mail_address){

        //ログイン成功
        //sessionにUSERを保存して  20170829 telemedica
        $session_lifetime = Member::member_lifetime();

        Session::forget('MEMBER_3SP_USER');
        Session::forget('MEMBER_3SP_LOGIN_ID');
        Session::forget('MEMBER_3SP_LOGIN_AT');
        Session::forget('MEMBER_3SP_ACCOUNT_ID');
        Session::forget('MEMBER_3SP_PRODUCT_ID');
        Session::forget('MEMBER_3SP_UNIVERSITY_ID');

        setcookie("trialaudio","",time() - 1800);  // 認証

        //DB の login_id と login_at を更新

        // SessionからUUIDを取得(Libへ統合) 20170829 telemedica
        $uuid = Member::member_uuid();
        //$uuid = $this->member_uuid();

        AccountsProduct::where('account_id', $account_id)->where('deleted_at', NULL)->update(['login_id' => $uuid, 'login_at' => $dt_now]);
        Session::put('MEMBER_3SP_USER', $user);
        Session::put('MEMBER_3SP_LOGIN_ID', $uuid);
        Session::put('MEMBER_3SP_LOGIN_AT', $dt_now);
        Session::put('MEMBER_3SP_ACCOUNT_ID', $account_id);
        Session::put('MEMBER_3SP_PRODUCT_ID', $product_id);
        Session::put('MEMBER_3SP_EMAIL_ADDRESS', $mail_address);
        $oneTimeKey = OnetimeKey::where("customer_id",  $account_id)->first();
        if ($oneTimeKey->is_exam_group == 1) {
            Session::put('MEMBER_3SP_UNIVERSITY_ID', $oneTimeKey->university_id);
        }
        
    }

    /**
     * set onetime key and update the onetime key from database
     */
    // public static function setOnetimeAndUpdate($account_id){

    //     //make a onetime_key that will be used to insert in onetime_keys table
    //     $onetime_key = substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4);

    //     //get the token from the request
    //     $_token = csrf_token();

    //     //exempt testing user for any updates
    //     if($account_id != 126){
    //         //THEN UPDATE THE TOKEN FROM THE TABLE
    //         $update_token = DB::select('UPDATE onetime_keys SET onetime_key = ?, status = 1 WHERE customer_id = ?', [$onetime_key, $account_id]);
    //     }

    //     //THEN ENCRYPT THE TOKEN AND STORE IT IN LOCAL BROWSER
    //     $encrypted_token = GlobalFunction::custom_encryptor($onetime_key, $_token);
        
    //     session(['bwtk' => $encrypted_token]);
    //     session(['current_account_id' => $account_id]);

    //     //set cookie to local browser
    //     setcookie( "bwtk", $encrypted_token, strtotime( '+1 year' ));

    // }

    /**
     * set onetime key and insert the onetime key from database if not exists
     */
    // public static function setOnetimeAndInsert($account_id){

        
    //     //make a onetime_key that will be used to insert in onetime_keys table
    //     $onetime_key = substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4);

    //     //get the token from the request
    //     $_token = csrf_token();

    //     //INSERT NEW TOKEN IN THE TABLE WITH USERID
    //     $insert_account_id_and_onetime_key = DB::table("onetime_keys")->insert(['customer_id' => $account_id, 'onetime_key' => $onetime_key, 'status' => 1]);

    //     //THEN ENCRYPT THE TOKEN AND STORE IT IN LOCAL BROWSER
    //     $encrypted_token = GlobalFunction::custom_encryptor($onetime_key, $_token);

    //     session(['bwtk' => $encrypted_token]);
    //     session(['current_account_id' => $account_id]);

    //     //set cookie to local browser
    //     setcookie( "bwtk", $encrypted_token, strtotime( '+1 year' ));

    // }

    //create a function that will encrypt the data without special characters
    public static function custom_encryptor($onetimekey, $token){

        //encrypt the data
        $encrypted_data = openssl_encrypt($onetimekey, 'AES-128-ECB', $token);
        $encoded_data = base64_encode($encrypted_data);

        //return the encypted data
        return $encoded_data;

    }
        
    //create a function that will decrypt the data without special characters
    public static function custom_decryptor($data, $token){

        //decrypt the data
        $decoded_data = base64_decode($data);
        $decrypted_token = openssl_decrypt($decoded_data, 'AES-128-ECB', $token);

        //return the decypted data
        return $decrypted_token;

    }

    //create a function that will encrypt the data without special characters
    public static function custom_encryptor_2($data){

        //encrypt the data
        $encoded_data = base64_encode($data);

        //return the encypted data
        return $encoded_data;

    }
        
    //create a function that will decrypt the data without special characters
    public static function custom_decryptor_2($data){

        //decrypt the data
        $decoded_data = base64_decode($data);

        //return the decypted data
        return $decoded_data;

    }

    //function that update accounts session when changes
    public static function checkLoginAccountChange(){
        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');

        $log_account = Accounts::where("id",$account_id)->with("onetime_key")->first();

        //reset session user id if changed
        if($log_account->user !== Session::get('MEMBER_3SP_USER')){
            Session::forget('MEMBER_3SP_USER');
            Session::put('MEMBER_3SP_USER', $log_account->user);
        }

        //reset session email if changed
        if($log_account->email !== Session::get('MEMBER_3SP_EMAIL_ADDRESS')){
            Session::forget('MEMBER_3SP_EMAIL_ADDRESS');
            Session::put('MEMBER_3SP_EMAIL_ADDRESS', $log_account->email);
        }
        
        //reset session university if changed
        $oneTimeKey = $log_account->onetime_key;
        if( $oneTimeKey->university_id !== Session::get('MEMBER_3SP_EMAIL_ADDRESS')){
            Session::forget('MEMBER_3SP_UNIVERSITY_ID');
            if ($oneTimeKey->is_exam_group == 1) {
                Session::put('MEMBER_3SP_UNIVERSITY_ID', $oneTimeKey->university_id);
            }
        }
    }

    //function that update expired license key
    public static function checkExpiredLicense(){
        OnetimeKey::where('expiration_date','<=', Carbon::now())->whereNotNull('university_id')->update(['university_id' => NULL]);
    }
}
