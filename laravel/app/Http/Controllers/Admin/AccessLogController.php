<?php

namespace App\Http\Controllers\Admin;

use App\AccessLog;
use App\Http\Controllers\Controller;
use Session;
use DB;

class AccessLogController extends Controller
{
    public function index() {
        $accessLog = AccessLog::orderBy("id", "DESC")->paginate(10);
        return view('admin.access_log.index', compact('accessLog'));
    }

    public function access_log_csv() {
        return $this->download_logs("csv");
    }

    public function access_log_excel() {
        return $this->download_logs("xlsx");
    }

    private function download_logs($type) {
        $data = DB::table('access_logs')->orderBy("id", "DESC")->get();

        $csv_file = [];

        foreach ($data as $log) {
            $csv_row = [
                'date/time' => $log->created_at,
                'IP address' => $log->ip_addr,
                'domain' => $log->domain,
                'japaniese school name' => $log->school_name,
                'user agent' => $log->user_agent,
                'session ID' => $log->session_id,
                'user ID' => $log->user_id,
                'Referrer' => $log->referrer,
            ];
            array_push($csv_file, $csv_row);
        }

        return \Excel::create('access-logs-'.date('Y-m-d'), function ($excel) use ($csv_file) {
            $excel->sheet('OnetimeKeyList', function ($sheet) use ($csv_file) {
                $sheet->fromArray($csv_file);
            });
        })->download($type);
    }

    public function store() {
        $ipAddr = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $domain = $_SERVER['SERVER_NAME'];

        $ua=$this->getBrowser();
        // $yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];

        $userAgent = $ua['name'] . " (" . $ua['version'] . ") on " .$ua['platform'];

        $sessionId = Session::getId();
        $userId = session("MEMBER_3SP_USER");

        // $save = DB::table('access_logs')->insert([
        //     'created_at'    => date('Y-m-d H:i:s'), 
        //     'ip_addr'       => $ipAddr,
        //     'domain'        => $domain,
        //     'user_agent'    => $userAgent,
        //     'session_id'    => $sessionId,
        //     'user_id'       => $userId
        // ]);

        // if ($save) return 1;
    }

    private function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
    
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
    
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
    
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
    
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
    
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
}
