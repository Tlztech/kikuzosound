<?php

namespace App\Http\Controllers;

use App\Account;
use Input;
use DB;
use Mail;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\App;
use Session;
use App\Lib\GlobalFunction;
use App\OnetimeKey;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class RegisterFormController extends Controller
{
    public function __construct()
    {
        if (Session::get('lang')) {
            App::setLocale(Session::get('lang'));
        }
    }

    /**
     * お問合わせ内容の確認画面を表示する.
     */
    public function confirm(RegisterFormRequest $request)
    {
        $company = $request->input('company');
        $name = $request->input('name');
        $userId = $request->input('userId');
        $cust_mail_address = $request->input('cust_mail_address');

        $password = $request->input('password');
        $passwordConfirm = $request->input('passwordConfirm');
        $oneTimePassword = $request->input('oneTimePassword');

        $dealer = $request->input('dealer');
        $contact = $request->input('contact');
        $distr_mail_address = $request->input('distr_mail_address');
        $phone_number = $request->input('phone_number');

        // check the onetime password if exists from onetime_keys table
        $is_exists_onetime_passwrd = OnetimeKey::where('onetime_key',$oneTimePassword)->whereNull('customer_id')->first();
        // check the accounts userID if exists from accounts table
        $is_exists_userID = DB::select('SELECT * FROM accounts WHERE user = ?', [$userId]);

        // check the accounts mail address if exists from accounts table
        $is_exists_cust_mail_address = DB::select('SELECT * FROM accounts WHERE email = ? AND deleted_at IS NULL', [$cust_mail_address]);

        // check the contacts mail address if exists from contacts table
        $is_exists_distr_mail_address = DB::select('SELECT * FROM contacts WHERE email = ?', [$distr_mail_address]);

        $msg_error_userID = '';
        $msg_error_account_mail = '';
        $msg_error_contact_mail = '';
        $msg_error_pass = '';
        $msg_error_confirim_pass = '';
        $msg_error_onetime = '';
        $msg_error_terms = '';
        $hasError = 0;

        //if userID already exists
        if ($is_exists_userID) {
            $hasError = 1;
            $msg_error_userID = trans('customer_registration.user_already_exists');
        }

        // if (!checkEmail($cust_mail_address)['status']) {
        //     $hasError = 1;
        //     $msg_error_account_mail = trans('customer_registration.invalid_email');
        // }

        //if mail address already exists
        if ($is_exists_cust_mail_address) {
            $hasError = 1;
            $msg_error_account_mail = trans('customer_registration.exists_email');
        }
        // //if mail address already exists
        // if($is_exists_distr_mail_address){
        //   $hasError = 1;
        //   $msg_error_contact_mail = "* Mail Address already exists!";
        // }
        //if password is not equal to password confirmation
        if (strlen($password) < 6) {
            $hasError = 1;
            $msg_error_pass = trans('customer_registration.password_six_characters');
        }
        if (strlen($passwordConfirm) < 6) {
            $hasError = 1;
            $msg_error_confirim_pass = trans('customer_registration.password_six_characters');
        }
        //if password is not equal to password confirmation
        elseif ($password != $passwordConfirm) {
            $hasError = 1;
            $msg_error_confirim_pass = trans('customer_registration.pass_mismatch');
        }
        //if onetime password exists || if onetime password is expired
        if (!$is_exists_onetime_passwrd || ($is_exists_onetime_passwrd && $is_exists_onetime_passwrd->is_expired)) {
            $hasError = 1;
            $msg_error_onetime = trans('customer_registration.onetime_validation');
        }
        if (!$request->has('terms')) {
            $hasError = 1;
            $msg_error_terms = trans('customer_registration.terms_err');
        }
        //check if there is an error
        if (1 == $hasError) {
            //call back the requested input
            session()->flashInput($request->input());

            //return back to customer registration
            return view('customer_registration', compact('msg_error_userID', 'msg_error_account_mail', 'msg_error_contact_mail', 'msg_error_pass', 'msg_error_confirim_pass', 'msg_error_onetime', 'msg_error_terms'));
        }

        // 入力をフラッシュデータとして保存。{{ old('username') }}で取得が可能になる
        $request->flash();

        return view('register_form_confirm', compact('company', 'name', 'userId', 'cust_mail_address', 'password', 'passwordConfirm', 'oneTimePassword', 'dealer', 'contact', 'distr_mail_address', 'phone_number'));
    }

    /**
     * お問合わせの内容をメールで送信する.
     *
     * @return \Illuminate\Http\Response
     */
    public function send_mail(RegisterFormRequest $request)
    {
        // 宛先
        $to = env('CONTACT_FORM_MAIL_TO');
        $agent = new Agent();
        $browserUsed = $agent->browser()." on ".$agent->platform();
       // $browserUsed = $request->input('browser_used');
        $company = $request->input('company');
        $name = $request->input('name');
        $userId = $request->input('userId');
        $cust_mail_address = $request->input('cust_mail_address');
        $password = $request->input('password');
        $passwordConfirm = $request->input('passwordConfirm');
        $oneTimePassword = $request->input('oneTimePassword');
        // $browser = session("browser");
        // $device = session("device");
        // $terminal = GlobalFunction::custom_encryptor_2(session("terminal"));
        $bwtk = GlobalFunction::custom_encryptor($oneTimePassword, csrf_token());
        $dealer = $request->input('dealer');
        $contact = $request->input('contact');
        $distr_mail_address = $request->input('distr_mail_address');
        $phone_number = $request->input('phone_number');

        //registration logs
        $logs_array = array();
        $logs_array["onetime_key"] = $oneTimePassword;
        $logs_array["browser_used"] = $browserUsed;
        $logs_array["bwtk"] = $bwtk;
        $registration_log = json_encode($logs_array);

        $body = $this->createMailBodyText($company, $name, $userId, $cust_mail_address, $password, $oneTimePassword, $dealer, $contact, $distr_mail_address, $phone_number);
        $subject = trans('register.subject_detail');

        // メール送信
        $success = Mail::raw($body, function ($message) use ($to, $subject) {
            $message->from(env('MAIL_FROM_ADDRESS'), 'Kikuzosound.com');
            $message->to(env('CONTACT_FORM_MAIL_TO'))->subject($subject);
        });

        //hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //current date updated
        $updated_at = date('Y-m-d G:i:s');

        //insert the data into companys table and get the id inserted
        $insert_companys = DB::table('companys')->insertGetId(['company' => $company]);

        //insert the data into contacts table and get the id inserted
        $insert_contacts = DB::table('contacts')->insertGetId(['company_id' => $insert_companys, 'name' => $contact, 'email' => $distr_mail_address, 'tel' => $phone_number]);

        //insert the data into contracts table and get the id inserted
        $insert_contracts = DB::table('contracts')->insertGetId(['dealer_id' => $insert_contacts, 'customer_id' => 1]);

        //insert the data into accounts table
        $insert_accounts = DB::table('accounts')->insertGetId(['contract_id' => $insert_contracts, 'corporation_name' => $company, 'name' => $name, 'user' => $userId, 'email' => $cust_mail_address, 'password' => $hashed_password, 'auth' => 1, 'deleted_at' => '1', 'registration_log' => $registration_log]);

        //insert the data into accounts table
        // $update_onetime_pass = DB::select('UPDATE onetime_keys SET customer_id = ?, browser = ?, device = ?, terminal = ?, updated_at = ? WHERE onetime_key = ?', [$insert_accounts, $browser, $device, $terminal, $updated_at, $oneTimePassword]);
        //$update_onetime_pass = DB::select('UPDATE onetime_keys SET customer_id = ?, bwtk = ?, updated_at = ?, status = 1, browser_used = ? WHERE onetime_key = ?', [$insert_accounts, $bwtk, $updated_at, $browserUsed, $oneTimePassword]);

        // set 22 initial bookmarks
        $insert_packs = DB::table('favorite_packs')->insertGetId(['account_id' => $insert_accounts]);
        $initialBookmarks = [
            500, 499, 496, 495, 494, 492, 489, 488, 487, 486,
            523, 522, 521, 520, 517, 514, 508, 506, 504, 483,
            484, 573,
        ];

        for ($i = 0; $i < count($initialBookmarks); ++$i) {
            $stetho = DB::table("stetho_sounds")->where("id", $initialBookmarks[$i])->get();
            if(count($stetho) > 0) {
                DB::table('favorites')->insert([
                    'pack_id' => $insert_packs,
                    'stetho_sound_id' => $initialBookmarks[$i],
                    'disp_order' => $i,
                    'created_at' => date('Y-m-d G:i:s'),
                    'updated_at' => date('Y-m-d G:i:s'),
                ]);
            }
        }

        //set cookie to local browser
        session(['bwtk' => $bwtk, 'browser_used' => $browserUsed]);
        setcookie('bwtk', $bwtk, strtotime('+1 year'));

        // 画面に表示するメッセージ
        // $flush_message = $success ? trans('register.success_alert') : trans('register.failed_alert');
        // お問合わせ画面にリダイレクト
        if ($success) {
            //set the update link
            $activation_link = url('/').'/member_login/activate/'.$insert_accounts."/".$oneTimePassword;
            //send the mail to the recipient
            Mail::send('emails.register', ['activation_link' => $activation_link, 'browser_used' => $browserUsed], function ($m) use ($request) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Kikuzosound.com');
                $m->to($request->input('cust_mail_address'))->subject(trans('register.subject'));
            });

            return redirect()->back()->with('message', trans('register.success_alert'));
        }
    }

    public function send_email_verification(Request $request)
    {
        $email_input = $request->input('email');

        if ($email_input == null) {
            return redirect()->back()->withErrors(['empty' => trans('reapply_browser.empty')]);
        } else {
            $user = DB::table("accounts")->where('email', $email_input)->first();

            if ($user) {
                if ($user->deleted_at != null) {
                    $registration_log = $user->registration_log ? json_decode($user->registration_log) : null;
                    if(!$registration_log)//trying to resend emails on old accounts without logs
                        return redirect()->back()->withErrors(['already_activated' => trans('register.activation_expired')]);
                    
                    $oneTimeKey = OnetimeKey::Where('onetime_key', $registration_log->onetime_key)->whereNull("customer_id")->first();

                    if(!$oneTimeKey)//if onetime key is overide by other accounts / already used
                        return redirect()->back()->withErrors(['already_activated' => trans('register.activation_expired')]);
                    
                    $oneTimePassword = $oneTimeKey->onetime_key;
                    $activation_link = url('/').'/member_login/activate/'.$user->id."/".$oneTimePassword;
                    $browserUsed = $registration_log->browser_used ?  $registration_log->browser_used : $agent->browser()." on ".$agent->platform();
                    session(['browser_used' => $browserUsed]);

                    Mail::send('emails.register', ['activation_link' => $activation_link, 'browser_used' => $browserUsed], function ($m) use ($request) {
                        $m->from(env('MAIL_FROM_ADDRESS'), 'Kikuzosound.com');
                        $m->to($request->input('email'))->subject(trans('register.subject'));
                    });

                    return view("alert");
                } else {
                    return redirect()->back()->withErrors(['already_activated' => trans('register.email_activated')]);
                }
            } else {
                return redirect()->route("email_not_exist");
            }
        }
    }
    /**
     * メールで送信する内容をテキストにして返す.
     *
     * @param $name     お名前
     * @param $mail     メールアドレス
     * @param $tel      電話番号
     * @param $question お問合わせ内容
     * @param mixed $company
     * @param mixed $userId
     * @param mixed $cust_mail_address
     * @param mixed $password
     * @param mixed $oneTimePassword
     * @param mixed $dealer
     * @param mixed $contact
     * @param mixed $distr_mail_address
     * @param mixed $phone_number
     *
     * @return string メールで送信するテキスト
     */
    // private function createMailBodyText($company,$name,$mail,$tel,$dealer,$contact,$cmail,$ctel,$number,$kind,$year,$month,$date,$serial1,$question)
    private function createMailBodyText($company, $name, $userId, $cust_mail_address, $password, $oneTimePassword, $dealer, $contact, $distr_mail_address, $phone_number)
    {
        $data = [
            trans('register.corp_name') => $company,
            trans('register.name') => $name,
            trans('register.user_id') => $userId,
            trans('register.cust_email') => $cust_mail_address,
            trans('register.password') => $password,
            trans('register.onetimepass') => $oneTimePassword,
            trans('register.dealer') => $dealer,
            trans('register.contact') => $contact,
            trans('register.distr_email') => $distr_mail_address,
            trans('register.phone_num') => $phone_number,
        ];
        $text = '';
        foreach ($data as $key => $value) {
            // 改行は
            $text .= $key."\n".$value."\n\n";
        }
        return $text;
    }
}
