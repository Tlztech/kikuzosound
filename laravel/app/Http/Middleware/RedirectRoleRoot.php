<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class RedirectRoleRoot
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
    // 監修者：コンテンツ一覧画面を表示  
    if (\Auth::user()->role == User::$ROLE_SUPERINTENDENT ) {
        setcookie("audioaccess","true");    // .htaccessで許可
        return redirect('/admin/stetho_sounds');
    }
    // システム管理者：クイズパック一覧画面を表示
    else if (\Auth::user()->role == User::$ROLE_ADMIN ) {
        setcookie("audioaccess","true");    // .htaccessで許可
        return redirect('/admin/quiz_packs');
    }
    return $next($request);
  }
}
