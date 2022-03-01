<?php

namespace App\Http\Middleware;

use Closure;
use App\Setting;
use Illuminate\Support\Facades\Session;

class UserRequest
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
        if(($request->isMethod('post') || $request->isMethod('put')) && count($request->file())>0 ){
            //update version on db if request is post with files
            $setting = Setting::firstOrCreate(['id' => 1],['version' => 0])->increment('version');
        }
        $version = Setting::find(1);
        $cache_version =  $version? $version->cache_version : "0";
        $version =  $version? $version->version.".0" : "0.0";
       
        Session::put('version', $version);
        Session::put('cache_version', $cache_version);
        return $next($request);
    }
}
