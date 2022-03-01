<?php

namespace App\Http\Controllers\Auth;

use App\Account;
use App\Http\Controllers\Controller;
use App\PasswordResets;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Mail;
use Session;
use DB;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request)
    {
        $lang = App::getLocale('lang');
        $this->validate($request, ['email' => 'required|email']);
        $user = DB::table("accounts")->where('email', $request->input('email'))->first();

        if ($user) {
            if ($user->deleted_at == NULL) {
                $token = str_random(64);
                PasswordResets::updateOrCreate(
                    ['email' => $request->input('email')],
                    ['email' => $request->input('email'), 'token' => $token]
                );

                $resetLink = env('APP_URL') . "password/reset/" . $token . "/" . $lang;

                Mail::send('emails.password', ['resetLink' => $resetLink], function ($m) use ($user) {
                    $m->from(env('CONTACT_FORM_MAIL_TO'));
                    $m->to($user->email)->subject(trans('passwords.subject'));
                });
                return redirect()->back()->with('status', trans('passwords.sent'))->withInput($request->old('email'));
            } else {
                session()->flashInput($request->input());
                return redirect()->back()->with('not_activated', trans('passwords.user_not_activated'));
            }
        } else {
            session()->flashInput($request->input());
            return redirect()->back()->with('error', trans('passwords.user'));
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null, $lang = "en")
    {
        Session::set('lang', $lang);
        $PasswordResets =  PasswordResets::where('token', $token)->first();
        if ($PasswordResets) {
            Session::set('password_reset_token', $PasswordResets->token);
            return redirect()->route('change_password');
        } else {
            Session::forget('password_reset_token');
            throw new NotFoundHttpException;
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $rules=[
            'password' => 'required|min:6',
            'password_confirmation' => 'required',
        ];
        $messages=[
            'password.required' => trans('passwords.required'),
            'password_confirmation.required' => trans('passwords.confirmation'),
            'password.confirmed' => trans('passwords.match'),
        ];

       $this->validate($request, $rules, $messages);

        $password = $request->input('password');
        $passwordConfirm = $request->input('password_confirmation');



        if ($password != $passwordConfirm) {
            session()->flashInput($request->input());
            return redirect()->back()->with('confirmation_err','');   
        }
        
        $PasswordResets = PasswordResets::where('token', Session::get('password_reset_token'))->first();
        
        $user = Account::where('email', $PasswordResets->email)
                        ->update(['password' => password_hash($request->input('password'), PASSWORD_DEFAULT)]);
        
        if ($user) {
            Session::forget('password_reset_token');
            PasswordResets::where('email', $PasswordResets->email)->delete();

            return redirect()->route('member_login');
        }
    }

}
