<?php

namespace App\Http\Controllers\Auth;

use App\Accounts;
use App\BrowserResets;
use App\BrowserResetsHistory;
use App\OnetimeKey;
use App\Http\Controllers\Controller;
use App\PasswordResets;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Password;
use Mail;
use Session;
use App\Lib\GlobalFunction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReapplyBrowserController extends Controller
{
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        if (Session::get('lang')) {
            App::setLocale(Session::get('lang'));
        }
    }

    /**
     * Send a link to the given user that will update the browser to login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        //get the email that was being inputted earlier
        $email_input = $request->input('email');

        //if empty
        //use of Session because trans() only taking japanese
        if($email_input == null){
            return redirect()->back()->withErrors(['empty' => trans('reapply_browser.empty')]);
        }
        else{

            //validate the email request
           // $this->validate($request, ['email' => 'required|email']);

            //get the data from Account table where email request exists
            $user = Accounts::where('email', $email_input)->with("onetime_key")->first();
           
            if ($user && $user->onetime_key) {
                //check reset history
                $browser_resets = BrowserResetsHistory::where("onetime_key_id",$user->onetime_key->id)->latest()->first();
                if($browser_resets){ //if has reset recods
                    $last_reset = Carbon::createFromFormat('Y-m-d H:s:i', $browser_resets->created_at);
                    $today =Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());
                    $diff_in_days = $today->diffInDays($last_reset);
                    if($diff_in_days<=30){
                        return redirect()->back()->withErrors(['email' => trans('reapply_browser.user_reset_date')]);
                    }
                }
                //set the update link
                $customerLink = env('APP_URL') . "customer_admin?search_key=" . $user->email;

                //customer reapply browser function
                $onetimeKey = OnetimeKey::find($user->onetime_key->id);

                $browserResetHistory = new BrowserResetsHistory();
                $browserResetHistory->onetime_key_id = $onetimeKey->id;
                $browserResetHistory->prev_onetime_key = $onetimeKey->onetime_key;
                $browserResetHistory->save();

                $newLicenseKey = substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4).'-'.substr(mt_rand(), 0, 4);
                $onetimeKey->onetime_key = $newLicenseKey;
                $onetimeKey->status = 2;
                $onetimeKey->bwtk = null;
                $onetimeKey->save();

                $token = str_random(64);
                $url = env('APP_URL').'update_browser/'.$token;

                DB::table("browser_resets")->where("customer_id", $onetimeKey->customer_id)->delete();
                DB::table("browser_resets")->insert([
                    "customer_id" => $onetimeKey->customer_id,
                    "token" => $token,
                    "created_at" => date('Y-m-d H:i:s')
                ]);

                //send the mail to the recipient
                Mail::send('emails.reapplybrowser', 
                ['content1' => trans('reapply_browser.email_reapply_content1'),
                    'content2' => trans('reapply_browser.email_reapply_content2'),
                    'name' => $user->name,
                    'email' => $user->email, 
                    'newLicense' => $newLicenseKey, 
                    'url' => $url], function ($m) use ($user) {
                    $m->from(env('CONTACT_FORM_MAIL_TO'));
                    $m->to($user->email)->subject(trans('reapply_browser.email_subject'));
                });

                Mail::send('emails.reapplybrowser_admin', 
                [
                    'email' => $user->email,
                    'name'  => $user->name,
                    'customerLink'  => $customerLink,
                    'custId'    => $user->user
                ], 
                function ($m) use ($user) {
                    $m->from(env('CONTACT_FORM_MAIL_TO'));
                    $m->to(env('CONTACT_FORM_MAIL_TO'))->subject('Re-application for the browser');
                });
    
                //redirect the user
                return redirect()->back()->with('status', trans('reapply_browser.sent'))->withInput($request->old('email'));
            } else {
                //redirect the user back to the page
                return redirect()->back()->withErrors(['email' => trans('reapply_browser.user')]);
            }
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token) {
        $BrowserResets =  BrowserResets::where("token", $token)->first();

        //check if token exists
        if ($BrowserResets) {
            Session::set('browser_update_customer_id', $BrowserResets->customer_id);
            return view("update_browser", ['status' => 'token_ok']);
        } else {
            return view("errors.page_error", [
                'error_message' => trans('update_browser.update_browser_expire')
            ]);
        }
    }

    public function postReset(Request $request) {
        $customerId = Session::get("browser_update_customer_id");
        $oneTimePasswordInput = $request->input('oneTimePassword');

        if($customerId != null){ // check if customer_id is set
            if ($oneTimePasswordInput) { // check if license key empty
                $oneTimePasswordResult = OnetimeKey::where("onetime_key", $oneTimePasswordInput)->first();

                if($oneTimePasswordResult) { // check if license key exist
                    if ($oneTimePasswordResult->customer_id && $oneTimePasswordResult->customer_id == $customerId) { // check if its his license key
                        // generate new bwtk
                        $encrypted_bwtk = GlobalFunction::custom_encryptor($oneTimePasswordInput, csrf_token());

                        //0: issued 1: used 2: changed
                        // update status from changed to used
                        $updateBrowser = OnetimeKey::where("onetime_key", $oneTimePasswordInput)
                            ->update([
                                'customer_id' => $customerId,
                                'bwtk'  => $encrypted_bwtk,
                                'status' => 1
                            ]);

                        $updateAccount = DB::table('accounts')
                                ->where("id", $customerId)
                                ->update(['deleted_at' => NULL]);

                        if($updateBrowser){
                            session(['bwtk' => $encrypted_bwtk]);
                            //redirect the user to member login page
                            Session::forget('browser_update_customer_id');
                            BrowserResets::where("customer_id", $customerId)->delete();

                            return redirect()->route("update_browser_confirm"); 
                        }
                    } else {
                        return redirect()->back()->withErrors(['not_exist' => trans('update_browser.incorrect_license_key')]);
                    }
                } else {
                    return redirect()->back()->withErrors(['not_exist' => trans('update_browser.license_not_exist')]);
                }
            
            } else {
                return redirect()->back()->withErrors(['empty' => trans('update_browser.license_required')]);
            }
        }else{
            Session::forget('browser_update_customer_id'); 
            return view("errors.page_error", [
                'error_message' => trans('update_browser.update_browser_expire')
            ]);
        }
        
    }
}
