<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Session;
use Carbon\Carbon;
use App\AccountsProduct;
use App\Http\Controllers\MemberController;
use App\Lib\Member;    /* telemedica 20170829 */
use Illuminate\Support\Facades\Route;
use App\Lib\GlobalFunction;

class MemberOnly {
	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // 旧：ログイン済みの会員ではない場合、ログイン画面を表示する
        // →ログイン済みでも、DBのログイン情報を参照し、UUIDが一致しなければ（他の端末から同一IDでログインした可能性があるため）、ログイン画面を表示する
        
        // 認証確認 20170829 telemedica
        $auth = Member::member_auth();
        Session::set('previousRoute', Route::currentRouteName());
        if ($auth == 0) {		//認証NGなら
            if($request->path()=="home" || $request->path()=="")  return $next($request);
            return redirect('member_login');		//ログイン画面へ
            //return view('member_login');
        } else {		//ログイン済みで、UUIDとアカウントIDが一致し、有効期間内なら
            GlobalFunction::checkLoginAccountChange();
            GlobalFunction::checkExpiredLicense();
            return $next($request);		//処理を継続
        }
    }

}
