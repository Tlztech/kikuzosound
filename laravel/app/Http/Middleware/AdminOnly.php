<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class AdminOnly
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
    if (\Auth::user()->role != User::$ROLE_ADMIN ) {
      return redirect()->guest('admin/login');
    }
    return $next($request);
  }

}