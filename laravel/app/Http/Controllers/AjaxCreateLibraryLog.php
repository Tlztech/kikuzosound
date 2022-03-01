<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\UseLog;

class AjaxCreateLibraryLog extends Controller
{
  /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
  public function create_library_log(Request $request)
  {
    
    $event = request('click');
    $prev_id = request('prev_id');
    $prev_log = request('prev_log');
    $lib_name = request('lib_name');
    $library_id = request('library_id');
    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
    $login_user = $request->session()->get('MEMBER_3SP_ACCOUNT_ID');
    $account_login = DB::table('stetho_sounds')->select('lib_type')->where('id', $library_id)->get();
    
    // UTC TIME
    $time = time();
    $check = $time+date("Z",$time);
    $time = gmdate("Y-m-d H:i:s", $check);
    
    if( $event == 'start' ) {
      $log = new UseLog();
      $log->type = 3;
      $log->university_id = $universityId;
      $log->lib_id = $account_login[0]->lib_type;
      $log->user_id = $login_user;
      $log->stt_time  = $time;
      $log->quiz_type = $lib_name;
      $log->lib_id = $library_id;
      $log->save();
    }
    if( $event == 'end_start' ) {
      $log = new UseLog();
      $log->type = 3;
      $log->university_id = $universityId;
      $log->lib_id = $account_login[0]->lib_type;
      $log->user_id = $login_user;
      $log->stt_time  = $time;
      $log->quiz_type = $lib_name;
      $log->lib_id = $library_id;
      $log->save();
      
      $end_log = UseLog::find(intval($prev_log));
      $end_log->end_time = $time;
      $end_log->save();
    }
    if( $event == 'end' ) {
      $log = UseLog::find(intval($prev_log));
      $log->end_time = $time;
      $log->save();
    }
    
    return  [
      'type'        => 3, 
      'log_id'      => $log->id,
      'sendlib_id'  => intval($library_id),
      'user_id'     => $login_user,
      'univ_id'     => $universityId, 
      'lib_id'      => $account_login[0]->lib_type, 
    ] ;
    
  }
}