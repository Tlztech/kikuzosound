<?php

namespace App\Http\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;
use Auth;
use App\User;

class ApiAuthMiddleware extends BaseVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // set csrf
        $request->headers->set('X-CSRF-TOKEN', csrf_token());

        $rememberToken = $request->header('Authorization');
        $userRemembered = User::where("remember_token",$rememberToken)->first();
        $user = Auth::user();

        if ($this->isReading($request) || $this->shouldPassThrough($request) || $this->tokensMatch($request)) {
            if(Auth::check() || $user || $userRemembered) {
                $authUser = $user ?? $userRemembered;
                $university_id = $authUser ? $user->university_id ?? $userRemembered->university_id : false;
                if (!$university_id || ($authUser->role !== 101 && $authUser->role !== 201) || $authUser->enabled === 0)  {
                    $result = array(
                        'success' => "ng",
                        'message' => "Unauthorized",
                        'result'  => null,
                    );

                    return response()->json($result);
                }
                $request->request->add(['university_id' => $university_id, 'auth_user' => $authUser]); //add reques
                $userAccess = ['university_id' => $university_id, 'auth_user' => $authUser];
                $request->request->add($userAccess); //add request

                return $this->addCookieToResponse($request, $next($request));
            }
        }

        $result = array(
            'success' => "ng",
            'message' => "Unauthorized",
            'result'  => null,
        );

        return response()->json($result);

    }
}
