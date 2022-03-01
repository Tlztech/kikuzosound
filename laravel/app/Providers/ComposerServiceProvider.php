<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lib\Member;    /* telemedica 20170829 */

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // クロージャーベースのコンポーサーを使用する
        view()->composer('*', function ($view) {
            $login_status = Member::member_auth();
            $view->with('login_status',$login_status);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
