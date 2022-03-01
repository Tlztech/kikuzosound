<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Traits\AuthenticatesUsers;
use App\ExamGroup;

class LoginController extends Controller
{
    use ThrottlesLogins;
    use AuthenticatesUsers;
    private $success = 'ng';
    private $message = '';
    private $result = null;

    protected $maxLoginAttempts = 5;
    protected $lockoutTime = 60;

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
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        // $this->clearLoginAttempts($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->message = "user locked";
            return $this->response();
        }

        $params = request('params');
        $this->message = "not authenticated";
        if (Auth::attempt(['email' => $params["email"], 'password' => $params["password"]], true)) {
            // Authentication passed...
            $user = Auth::user();
            if ($user->enabled === 0 || ($user->role !== 101 && $user->role !== 201)) {
                Auth::logout(); // normal logout
                $this->message = "not authenticated";
                return $this->response();
            }

            $group = ExamGroup::findOrFail($user->university_id);
            $this->clearLoginAttempts($request);
            $this->success = "ok";
            $this->message = "login successfully";
            $this->result = [
                "user" => $user, 
                "authorization" => $user->remember_token,
                "group" => $group
            ];
        } else {
            $this->incrementLoginAttempts($request);
        }
        return $this->response();
    }

    public function logout()
    {
        $authUser = request('auth_user');
        $user = User::findOrFail($authUser->id);
        $user->remember_token = null;
        $user->save(); // remove remember token

        Auth::logout(); // normal logout
        $this->success = "ok";
        $this->message = "success";

        return $this->response();
    }
}
