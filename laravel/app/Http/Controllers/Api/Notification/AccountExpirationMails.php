<?php

namespace App\Http\Controllers\Api\Notification;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\OnetimeKey;
use Carbon\Carbon;
use Mail;


class AccountExpirationMails extends Controller
{
    
    private $success = 'ng';
    private $message = '';
    private $result = null;
    
    /**
     *
     * @return JsonResponse
     */

    function response() {
        $result = array(
            'success' => $this->success,
            'message' => $this->message,
            'result'  => $this->result,
        );
        return response()->json($result);
    }
    
    /**
     * Send emails 2 and 4 weeks ago before account expiration
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return response
     */
     
    public function sendWarningEmails(Request $request)
    {       
        $accntsToNotify = OnetimeKey::where(function($query){
            $query->whereNotNull("customer_id")->whereDate("expiration_date",'=',Carbon::now()->addWeeks(2)->toDateString());//get all accounts 2 weeks expiration
        })
        ->orWhere(function($query2) {
            $query2->whereNotNull("customer_id")->whereDate("expiration_date",'=',Carbon::now()->addWeeks(4)->toDateString());//get all accounts 4 weeks expiration
        })
        ->with("accounts")->get();
        foreach($accntsToNotify as $r){
            Mail::send('emails.accnt_expiration', ['user_id' => $r->accounts->id, 'email_add' => $r->accounts->email, 'exp_date' => $r->expiration_date], function ($m) use ($r) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Kikuzosound.com');
                $m->to($r->accounts->email)->subject("Notice of license key expiration date / ライセンスキーの利用期限のお知らせ");
            });
        }

        $this->result = $accntsToNotify;
        $this->message = "success";
        $this->success = "ok";

        return $this->response();
    }
}