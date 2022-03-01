<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\PasswordResets;
use Mail;
use Session;
use Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordResetControlller extends Controller
{
    private $success = 'ng';
    private $message = 'success';
    private $result = null;
    /**
     *
     * @return Response
     */

    function response()
    {
        $result = array(
            'success' => $this->success,
            'message' => $this->message,
            'result'  => $this->result,
        );
        return response()->json($result);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail()
    {
        $params = request("params");

        $user = User::Where('email', $params["email"])->first();
        if ($user) {
            $token = str_random(64);
            PasswordResets::updateOrCreate(
                ['email' => $params["email"]],
                ['email' => $params["email"], 'token' => $token]
            );

            $resetLink = env('APP_URL') . "group_admin/reset-password?"  . $token;
            Mail::send('emails.univ.password-reset', ['resetLink' => $resetLink], function ($m) use ($user) {
                $m->from(env('CONTACT_FORM_MAIL_TO'));
                $m->to($user->email)->subject(trans('passwords.subject'));
            });
            $this->success = "ok";
            $this->message = "Success! password reset sent";
            $this->result = $user->email;
        } else {
            Session::forget('frontend_url');
            $this->message = "Failed!! email not exists";
        }

        return $this->response();
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token)
    {
        $resetToken =  PasswordResets::where('token', $token)->get()->pluck("token")->first();
        if ($resetToken) {
            return view("auth.univ.reset", compact('token'));
        } else {
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
        $fromUniv = $request->input('fromUniv');
        if(!$fromUniv){
            $this->validate($request, [
                'password' => 'required|confirmed|min:6',
            ]);
        }
        $PasswordResets = PasswordResets::where('token', $request->input('token'))->first();
        $user = User::where('email', $PasswordResets->email)
            ->update(['password' => bcrypt($request->input('password'))]);

        if ($user) {
            PasswordResets::where('email', $PasswordResets->email)->delete();
            if($fromUniv){
                $this->success = "ok";
                $this->message = "Success! password reset sent";
                $this->result = env('APP_URL') . "group_admin";
                return $this->response();
            }
            else
                return redirect(env('APP_URL') . "group_admin"); // temp
        }
    }

    public function changePassword(Request $request)
    {
        $params = request("params");
        $email = $params['email'];
        $password = $params['current_password'];
        $new_password = $params['new_password'];
        $password_confirm = $params['confirm_password'];
        // $user = Auth::user();
        $user = User::where('email',$email)->first();
        if (!Hash::check($password, $user->password)) {
            $this->message = 'current_password_error';
        } else {
            if ($new_password != $password_confirm) {
                $this->message = 'password_not_match';
            } else {
                $updated_user = User::where('email', $user->email)
                    ->update(['password' => bcrypt($new_password)]);
                $this->success = "ok";
            }
        }
        $this->result = [
            "user" => $user
        ];
        return $this->response();
    }
}
