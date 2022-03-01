<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Lib\GlobalFunction;
class CustomerAdminOnly
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
    // システム管理者ではない場合、403エラーにする
    if (\Auth::user()->role != User::$ROLE_CUSTOMER_ADMIN ) {
        return redirect()->guest('customer_admin/login');
    }
    GlobalFunction::checkExpiredLicense();
    return $next($request);
  }

}