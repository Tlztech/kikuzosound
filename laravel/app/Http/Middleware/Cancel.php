<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Session;
use Carbon\Carbon;
use App\Lib\Member;    /* telemedica 20170829 */
use App\Account;    // telemedica 20180810
use App\Deletes;    // telemedica 20180810
use DB;
	
class Cancel {
	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
      $auth = Member::member_auth(); // 認証確認 0:未ログイン 1:ログイン中
      $usage_type = 0; // 0:購入 1:1年月払 2:1年年払 3:3年月払 4:3年年払 99:デモ
      $cancel = 0; // 0:ホームに戻る 1:処理継続

      if($auth) {   // ログイン中
        $account_id = Session::get('MEMBER_3SP_ACCOUNT_ID');    // アカウントid
        $account = Account::where('id', $account_id)->where('deleted_at', NULL)->first();   // DBから取得
        $delete = Deletes::where('account_id', $account_id)->where('deleted_at', NULL)->first();   // DBから取得

        if($account) {  // アカウントがある場合
          $usage_type = $account->usage_type;

          // ログイン中でサイト利用プランの場合は表示
          if($auth == 1 && ($usage_type == 1 || $usage_type == 2 || $usage_type == 3 || $usage_type == 4 || $usage_type == 5 || $usage_type == 6 || $usage_type == 7 || $usage_type == 8)) {
            if($delete) {   // 予約削除がある場合
              if(is_null($delete->cancel_at)) { // 解約申請日時がない
                $cancel = 1;		//処理を継続
              }
            }
          }
        }
      }

      if($cancel == 1) {
        return $next($request);		//処理を継続
      } else {
        return redirect('home');		//ホーム画面へ
      }
    }
}
