<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\ExamResults;
use App\UseLog; 
use DateTime;
use App\Quiz;
use App\User;
use App\QuizPack;
use App\Accounts;
use App\Exams;
use App\OnetimeKey;
use Carbon\Carbon;
use App\StethoSound;
use App\Universities;
use App\Traits\AnalyticsTraits;
use App\Http\Common\UnivAnalytics;
use App\Http\Controllers\Api\Lib\Ranking;
use App\Http\Controllers\Api\Lib\Analytics;

class LogAnalyticsController extends Controller
{
    
    use AnalyticsTraits;

    private $success = 'ng';
    private $message = '';
    private $result = null;
    private $total_page = null;
    
	protected $auth_user = null;
    protected $university_id = null;

    public function __construct(Request $request)
    {
		$this->auth_user = $request->input("auth_user");
        $this->university_id = $request->input("university_id");
    }

    /**
     *
     * @return JsonResponse
     */

    function response()
    {
        $result = array(
            'success' => $this->success,
            'message' => $this->message,
            'total_page'  => $this->total_page,
            'result'  => $this->result,
        );
        return response()->json($result);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
    */
   
   public function index(Request $request)
    {
        $params = request("params");
        $page = $params['page'];
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        $exam_id = $params['exam_id'];
        $univ_id = $params['univ_id'];
        $user_id = $params['user_id'];
        $quiz_id = $params['quiz_id'];
        $type = $params['exam_type'];
        $library_id = $params['library_id'];

        if($date_from > $date_to ){
            $this->message = "fail start date not accepted";
            $this->success = "ok";
            return $this->response();
        }
        if ($date_from && $date_to){
            $date_from = date( "Y-m-d", strtotime( $date_from))." 00:00:00";
            $date_to = date( "Y-m-d", strtotime( $date_to))." 23:59:59";
        }
        $all_exam = [];
        $all_quiz = [];
        $all_lib  = [];
        $all_logs = [];
        $all_exam_logs = [];
        $all_quiz_logs = [];
        
        $all_exam =  UseLog::select(
            [
                "use_logs.*",
                "use_logs.id as log_id",
                "use_logs.type as type",
                "use_logs.quiz_type as library_type",
                "use_logs.parent_id",
                
                "exams.name as exam_name",
                "exams.id as exam_id",
                
                "user.name as author",
                "user.id as author_id",
                
                "university.name as univ_name",
                "university.id as univ",
                
                "quiz.title_en as quiz_name",
                "quiz.id as quiz_id",
                
                DB::raw("sum(is_correct) as num_correct_exam"),
                DB::raw("count(use_logs.is_correct) as count"),
                DB::raw("SEC_TO_TIME(sum(time_to_sec(TIMEDIFF(end_time, stt_time)))) as used_time"),
                DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as rate"),
                DB::raw("GROUP_CONCAT( use_logs.question_log ) as lib_id"),
                
            ]) 
            ->join('quizzes as quiz', 'quiz.id', '=', 'use_logs.quiz_id')
            ->join('exam as exams', 'exams.id', '=', 'use_logs.exam_id')
            ->join('universities as university', 'university.id', '=', 'use_logs.university_id')
            ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
            ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
            ->where(['use_logs.type' => 1])
            ->where('user.disable_analytics',0)
            ->whereNotNull("use_logs.is_correct")
            ->whereNotNull("use_logs.parent_id")
            ->where('use_logs.updated_at', '>=', $date_from)->where('use_logs.updated_at', '<=', $date_to)
            ->groupBy('parent_id');
        
        if($exam_id)
            $all_exam =  $all_exam->where("use_logs.exam_id",$exam_id);
        
        if($user_id)
            $all_exam =  $all_exam->where("use_logs.user_id",$user_id);

        if($univ_id)
            $all_exam =  $all_exam->where("use_logs.university_id",$univ_id);

        $all_quiz =  UseLog::select(
            [
                "use_logs.*",
                "use_logs.id as log_id",
                "use_logs.type as type",
                "use_logs.quiz_type as library_type",
                "use_logs.parent_id",
                
                "quiz.title_en as quiz_name",
                "quiz.id as quiz_id",
                
                "user.id as author_id",
                "user.name as author",
                
                "university.name as univ_name",
                "university.id as univ",
                
                "exams.title_en as exam_name",
                "exams.id as exam_id",
                
                DB::raw("sum(is_correct) as num_correct_exam"),
                DB::raw("count(use_logs.is_correct) as count"),
                DB::raw("SEC_TO_TIME(sum(time_to_sec(TIMEDIFF(end_time, stt_time)))) as used_time"),
                DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as rate"),
                DB::raw("GROUP_CONCAT( use_logs.question_log ) as lib_id"),

            ]) 
            ->join('quizzes as quiz', 'quiz.id', '=', 'use_logs.quiz_id')
            ->join('universities as university', 'university.id', '=', 'use_logs.university_id')
            ->join('quiz_packs as exams', 'exams.id', '=', 'use_logs.quiz_pack_id')
            ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
            ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
            ->where(['use_logs.type' => 2])
            ->whereNotNull("use_logs.is_correct")
            ->whereNotNull("use_logs.parent_id")
            ->where('user.disable_analytics',0)
            ->where('use_logs.updated_at', '>=', $date_from)->where('use_logs.updated_at', '<=', $date_to)
            ->groupBy('parent_id');
        
        if($quiz_id)
            $all_quiz =  $all_quiz->where("use_logs.quiz_pack_id",$quiz_id);

        if($user_id)
            $all_quiz =  $all_quiz->where("use_logs.user_id",$user_id);
        
        if($univ_id)
            $all_quiz =  $all_quiz->where("use_logs.university_id",$univ_id);
            
        $all_library =  UseLog::select(
            [
                "use_logs.id as log_id",
                "use_logs.type as type",
                "use_logs.quiz_type as library_type",
                
                "library.id as id",
                "library.title as name",
                
                "user.name as author",
                "user.id as author_id",
                
                "university.id as univ",
                "university.name as univ_name",
                DB::raw("SEC_TO_TIME(sum(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time)))) as used_time"),
            ])
            ->join('stetho_sounds as library', 'library.id', '=', 'use_logs.lib_id')
            ->join('universities as university', 'university.id', '=', 'use_logs.university_id')
            ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
            ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
            ->whereNotNull('use_logs.end_time')
            ->where('use_logs.type', 3)
            ->where('user.disable_analytics',0)
            ->groupBy("use_logs.user_id","use_logs.lib_id")
            ->where('use_logs.updated_at', '>=', $date_from)->where('use_logs.updated_at', '<=', $date_to);
        
        if($library_id)
            $all_library =  $all_library->where("use_logs.lib_id",$library_id);

        if($user_id)
            $all_library =  $all_library->where("use_logs.user_id",$user_id);

        if($univ_id)
            $all_library =  $all_library->where("use_logs.university_id",$univ_id);

        if($this->auth_user->role === 201){
            $all_library = $all_library->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $all_exam = $all_exam->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $all_quiz = $all_quiz->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
        }
        $all_logs = [
            'library' => (!$type || $type==3)? $all_library->get() : [],
            'exam' => (!$type || $type==1)? $all_exam->get() : [],
            'quiz' => (!$type || $type==2)? $all_quiz->get() : []
        ];
        $this->result = $all_logs;
        $this->message = "success";
        $this->success = "ok";

        return $this->response();
    }

    /**
     * get analytics --refactor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getNewLogAnalytics(Request $request)
    {
        $params = request("params"); 
        $Analytics = new Analytics($this->auth_user,$this->university_id,$params);
        $userUsage = $Analytics->getTotalUsageTime();
        $correct_rate = $Analytics->getCorrectRate();
        $exam_usage = $Analytics->getExamUsageTime();
        $quiz_usage = $Analytics->getQuizUsageTime();
        $lib_usage = $Analytics->getLibraryUsageTime();
        $result=[
            "Number of lines" => $Analytics->getNumberOfData(),
            "Number of groups" => $Analytics->getNumberOfGroups(),
            "Number of users" => $Analytics->getNumberOfUsers(),
            "Total usage time" => $userUsage["usage_time"],
            "Average usage time" => $userUsage["avg_usage_time"],
            "Number of tests" => $Analytics->getNumberOfExams(),
            "Exam Correct answer rate" => $correct_rate["exam_correct_rate"],
            "Total test usage time" => $exam_usage["usage_time"],
            "Test average usage time" =>$exam_usage["avg_usage_time"],
            "Number of quizzes" => $Analytics->getNumberOfQuizes(),
            "Quiz correct answer rate" => $correct_rate["quiz_correct_rate"],
            "Quiz total usage time" => $quiz_usage["usage_time"],
            "Quiz average usage time" => $quiz_usage["avg_usage_time"],
            "Number of libraries" => $Analytics->getNumberOfLibraries(),
            "Total library usage time" => $lib_usage["usage_time"] ,
            "Average library usage time" => $lib_usage["usage_time"]  
        ];
        $this->result = $result;
        $this->message = "success";
        $this->success = "ok";

        return $this->response();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getLogAnalytics(Request $request)
    {
        $params = request("params"); 
        $exam_id = $params['exam_id'];
        $univ_id = $params['univ_id'];
        $user_id = $params['user_id'];
        $quiz_id = $params['quiz_id'];
        $exam_type = $params['exam_type'];
        $library_id = $params['library_id'];
        $user_univ_id = $params['user_univ_id'];
        $user_login_id = $params['user_login_id'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        
        $UnivAnalytics = new UnivAnalytics();
        
        $count_total_time = "00:00:00";
        $count_own_time = "00:00:00";
        $count_filter_time = "00:00:00";
        
        if ($start_date && $end_date){
            $start_date = date( "Y-m-d", strtotime( $start_date))." 00:00:00";
            $end_date = date( "Y-m-d", strtotime( $end_date))." 23:59:59";
        }
        
        // =========== New Query ====================
        
        $university = $univ_id ? $univ_id : '000' ; 
        $user_univ = $this->university_id ? $this->university_id : '000' ;
        $role = $this->auth_user->role  == 201 ? 'teacher' : 'admin' ;
        $get_university = $univ_id ? 'university_id = ' . $univ_id : true ; 
        $get_exam_id = $params['exam_id'] ? 'exam_id = '.$params['exam_id'] : true;
        $get_univ_id = $params['univ_id'] ? 'university_id = '.$params['univ_id'] : true;
        $get_user_id = $params['user_id'] ? 'user_id = '.$params['user_id'] : true;
        $get_quiz_id = $params['quiz_id'] ? 'quiz_pack_id = '.$params['quiz_id'] : true;
        $get_library_id = $params['library_id'] ? ' lib_id = '.$params['library_id'] : true;
        
        
        $count_group = Universities::select('id')->get()->count();
        
        $all_rate = UseLog::select(
            DB::raw(" ceil( ( count( IF( type = 1 AND is_correct = 1, is_correct, null ) ) /  count( IF( type = 1, is_correct, null ) ) ) * 100 ) as exam_rate"),
            DB::raw(" ceil( ( count( IF( type = 2 AND is_correct = 1, is_correct, null ) ) /  count( IF( type = 2, is_correct, null ) ) ) * 100 ) as quiz_rate"),
            
            DB::raw(" ceil( ( count( IF( type = 1 AND university_id = $user_univ AND is_correct = 1, is_correct, null ) ) /  count( IF( type = 1 AND university_id = $user_univ , is_correct, null ) ) ) * 100 ) as qroup_exam_rate"),
            DB::raw(" ceil( ( count( IF( type = 2 AND university_id = $user_univ AND is_correct = 1, is_correct, null ) ) /  count( IF( type = 2 AND university_id = $user_univ , is_correct, null ) ) ) * 100 ) as qroup_quiz_rate")
        )
        ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
        ->where('user.disable_analytics',0)
        ->get();
        
        $filter_rate = UseLog::select(
            DB::raw(" ceil( ( count( 
                IF( type = 1 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct = 1, is_correct, null )
            ) /  count( 
                IF( type = 1 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id, is_correct, null ) 
            ) ) * 100 ) as filter_exam_rate"),
            
            DB::raw(" ceil( ( count( 
                    IF( type = 1 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct = 1, is_correct, null )
                ) /  count( 
                    IF( type = 1 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id, is_correct, null ) 
                ) ) * 100 ) as teacher_filter_exam_rate"),
                
            DB::raw(" ceil( ( count( 
                    IF( type = 2 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct = 1, is_correct, null ) 
                ) /  count( 
                    IF( type = 2 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id, is_correct, null ) 
                ) ) * 100 ) as filter_quiz_rate"),
        
            DB::raw(" ceil( ( count( 
                IF( type = 2 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct = 1, is_correct, null ) 
            ) /  count( 
                IF( type = 2 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id, is_correct, null ) 
            ) ) * 100 ) as teacher_filter_quiz_rate")
        )
        ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
        ->where('user.disable_analytics',0);
        
        if($exam_type)
            $filter_rate = $filter_rate->where('use_logs.type', $exam_type);
        if ($start_date && $end_date){ 
            // $end_date = date( "Y-m-d h:m:s", strtotime( "$end_date" ) );
            // $start_date = date( "Y-m-d h:m:s", strtotime( "$start_date " ) );
            $filter_rate = $filter_rate->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
        }
        
        $filter_rate = $filter_rate->get();
        
        $count_time = Uselog::select(
            DB::raw("
                IF(type <= 2 AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s') , 
                IF(type = 3, TIME_FORMAT( TIMEDIFF( end_time, stt_time ), '%H:%i:%s' ) , '00:00:00s') ) as all_used_time"
            ),
            DB::raw("
                IF(type <= 2 AND  university_id = $user_univ AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s') , 
                IF(type = 3 AND  university_id = $user_univ, TIME_FORMAT( TIMEDIFF( end_time, stt_time ), '%H:%i:%s' ) , '00:00:00s') ) as own_used_time"
            ),
            DB::raw("
                IF(type = 1 AND  university_id = $user_univ AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as own_exam_used_time"
            ),
            DB::raw("
                IF(type = 1 AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as all_exam_used_time"
            ),
            DB::raw("
                IF(type = 2 AND  university_id = $user_univ AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as own_quiz_used_time"
            ),
            DB::raw("
                IF(type = 2 AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as all_quiz_used_time"
            ),
            DB::raw("
                IF(type = 3 AND  university_id = $user_univ, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as own_library_used_time"
            ),
            DB::raw("
                IF(type = 3, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as all_library_used_time"
            )
        )->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
        ->where('user.disable_analytics',0)
        ->get();
        $count_timeGF = Uselog::select(
            DB::raw("
                IF(type <= 2 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s') , 
                IF(type = 3 AND university_id = $user_univ AND $get_library_id AND $get_user_id, TIME_FORMAT( TIMEDIFF( end_time, stt_time ), '%H:%i:%s' ) , '00:00:00s') ) as teacher_filter_used_time "
            ),
            DB::raw("
                IF(type <= 2 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s') , 
                IF(type = 3 AND $get_university AND $get_library_id AND $get_user_id, TIME_FORMAT( TIMEDIFF( end_time, stt_time ), '%H:%i:%s' ) , '00:00:00s') ) as filter_used_time"
            ),
            DB::raw("
                IF(type = 1 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as techer_filter_exam_used_time"
            ),
            DB::raw("
                IF(type = 1 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as  filter_exam_used_time"
            ),
            DB::raw("
                IF(type = 2 AND university_id = $user_univ AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as techer_filter_quiz_used_time"
            ),
            DB::raw("
                IF(type = 2 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id AND is_correct IS NOT NULL, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as filter_quiz_used_time"
            ),
            DB::raw("
                IF(type = 3 AND university_id = $user_univ AND $get_library_id AND $get_quiz_id, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as techer_filter_library_used_time"
            ),
            DB::raw("
                IF(type = 3 AND $get_university AND $get_exam_id AND $get_user_id AND $get_quiz_id, TIME_FORMAT( TIMEDIFF ( end_time, stt_time ), '%H:%i:%s'), '00:00:00s') as filter_library_used_time"
            )
        )->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
        ->where('user.disable_analytics',0);
        
        if($exam_type)
            $count_timeGF = $count_timeGF->where('use_logs.type', $exam_type);
        if ($start_date && $end_date){ 
            // $end_date = date( "Y-m-d h:m:s", strtotime( "$end_date" ) );
            // $start_date = date( "Y-m-d h:m:s", strtotime( "$start_date " ) );
            $count_timeGF = $count_timeGF->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
        }
        $count_timeGF = $count_timeGF->get();
        
        // =============
        $all_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'all_used_time' );
        $all_used_time = array_filter( $all_used_time, function( $v ){ return $v != '00:00:00s'; });
        $own_totoal_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'own_used_time' );
        $own_totoal_time = array_filter( $own_totoal_time, function( $v ){ return $v != '00:00:00s'; });
        $filter_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'filter_used_time' );
        $filter_used_time = array_filter( $filter_used_time, function( $v ){ return $v != '00:00:00s'; });
        $teacher_filter_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'teacher_filter_used_time' );
        $teacher_filter_used_time = array_filter( $teacher_filter_used_time, function( $v ){ return $v != '00:00:00s'; });
        // ===========
         
        // ===========
        $all_exam_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'all_exam_used_time' );
        $all_exam_used_time = array_filter( $all_exam_used_time, function( $v ){ return $v != '00:00:00s'; });
        $own_exam_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'own_exam_used_time' );
        $own_exam_used_time = array_filter( $own_exam_used_time, function( $v ){ return $v != '00:00:00s'; });
        $filter_exam_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'filter_exam_used_time' );
        $filter_exam_used_time = array_filter( $filter_exam_used_time, function( $v ){ return $v != '00:00:00s'; });
        $techer_filter_exam_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'techer_filter_exam_used_time' );
        $techer_filter_exam_used_time = array_filter( $techer_filter_exam_used_time, function( $v ){ return $v != '00:00:00s'; });
        // ===========
         
        // ===========
        $all_quiz_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'all_quiz_used_time' );
        $all_quiz_used_time = array_filter( $all_quiz_used_time, function( $v ){ return $v != '00:00:00s'; });
        $own_quiz_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'own_quiz_used_time' );
        $own_quiz_used_time = array_filter( $own_quiz_used_time, function( $v ){ return $v != '00:00:00s'; });
        $filter_quiz_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'filter_quiz_used_time' );
        $filter_quiz_used_time = array_filter( $filter_quiz_used_time, function( $v ){ return $v != '00:00:00s'; });
        $techer_filter_quiz_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'techer_filter_quiz_used_time' );
        $techer_filter_quiz_used_time = array_filter( $techer_filter_quiz_used_time, function( $v ){ return $v != '00:00:00s'; });
        // ===========
        
        // ===========
        $all_library_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'all_library_used_time' );
        $all_library_used_time = array_filter( $all_library_used_time, function( $v ){ return $v != '00:00:00s'; });
        $own_library_used_time = array_column( $UnivAnalytics->object_to_array( $count_time ), 'own_library_used_time' );
        $own_library_used_time = array_filter( $own_library_used_time, function( $v ){ return $v != '00:00:00s'; });
        $filter_library_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'filter_library_used_time' );
        $filter_library_used_time = array_filter( $filter_library_used_time, function( $v ){ return $v != '00:00:00s'; });
        $techer_filter_library_used_time = array_column( $UnivAnalytics->object_to_array( $count_timeGF ), 'techer_filter_library_used_time' );
        $techer_filter_library_used_time = array_filter( $techer_filter_library_used_time, function( $v ){ return $v != '00:00:00s'; });
        // ===========

        $univ_arr  = Universities::select('id')->get();
        $univ_arr = array_column( $UnivAnalytics->object_to_array( $univ_arr ), 'id' );
        $exam_with_univ  = Exams::select('exam_pivot.exam_id', 'quiz_pack_id')
        ->leftJoin( 'pivot_exam_exam_group as exam_pivot',  'exam_pivot.exam_id', '=', 'exam.id' )
        ->distinct('exam_pivot.exam_id')
        ->whereNull('exam.deleted_at')
        ->whereIn('exam_pivot.exam_group_id', $univ_arr)
        ->get();
        $exam_with_univ = array_column( $UnivAnalytics->object_to_array( $exam_with_univ ), 'exam_id' );
        
        $quiz_with_univ  = QuizPack::select('quiz_packs.id')
        ->leftJoin( 'pivot_exam_group_quiz_pack as quiz_pivot', 'quiz_pivot.quiz_pack_id', '=', 'quiz_packs.id')
        ->distinct('quiz_pivot.quiz_pack_id')
        ->whereNull('quiz_packs.deleted_at')
        ->whereIn('quiz_pivot.exam_group_id', $univ_arr)
        ->get();
        $quiz_with_univ = array_column( $UnivAnalytics->object_to_array( $quiz_with_univ ), 'quiz_pack_id' );
        
        $lib_with_univ  = StethoSound::select('stetho_sounds.id')
        ->leftJoin( 'pivot_exam_group_stetho_sound as stetho_pivot', 'stetho_pivot.stetho_sound_id', '=', 'stetho_sounds.id')
        ->distinct('stetho_pivot.stetho_sound_id')
        ->whereNull('stetho_sounds.deleted_at')
        ->whereIn('stetho_pivot.exam_group_id', $univ_arr)
        ->get();
        $lib_with_univ = array_column( $UnivAnalytics->object_to_array( $lib_with_univ ), 'stetho_sound_id' );
        
        // Count Libraries  
        $all_data = Exams::select(
            DB::raw("
                IF( $university = 000 , COUNT(  DISTINCT( IF( exam.deleted_at IS NULL, exam.id, null) ) )  , 
                    COUNT( DISTINCT( IF( exam_pivot.exam_group_id = $university AND exam.deleted_at IS NULL, exam.id , NUlL ) ) ) + 
                    COUNT( DISTINCT( IF( exam_pivot.exam_group_id = $university AND 
                    exam.deleted_at IS NULL AND exam_pivot.exam_id IS NULL OR 
                    exam_pivot.exam_group_id NOT IN ( '" . implode( "', '" , $univ_arr ) . "' ) AND
                    exam_pivot.exam_id NOT IN ( '" . implode( "', '" , $exam_with_univ ) . "' ),
                    exam_pivot.exam_id , NUlL ) ) )
                )
                as filter_exam "),
                
            DB::raw(" COUNT( DISTINCT( IF( exam_pivot.exam_group_id = $user_univ AND exam.deleted_at IS NULL, exam.id , NUlL ) ) ) + 
                    COUNT( DISTINCT( IF( exam_pivot.exam_group_id = $user_univ AND 
                    exam.deleted_at IS NULL AND exam_pivot.exam_id IS NULL OR 
                    exam_pivot.exam_group_id NOT IN ( '" . implode( "', '" , $univ_arr ) . "' ) AND
                    exam_pivot.exam_id NOT IN ( '" . implode( "', '" , $exam_with_univ ) . "' ),
                    exam_pivot.exam_id , NUlL ) ) 
                )
                as group_exam "),
            DB::raw(" COUNT( DISTINCT( IF( exam.deleted_at IS NULL, exam.id, null) ) ) as all_exam ")
        )
        ->leftJoin( 'pivot_exam_exam_group as exam_pivot',  'exam_pivot.exam_id', '=', 'exam.id' )
        ->get();
        
        
        $all_data_quiz = QuizPack::select(
            DB::raw("
                IF( $university = 000 ,COUNT(  DISTINCT( IF( quiz_packs.deleted_at IS NULL, quiz_packs.id, null) ) ) , 
                COUNT( DISTINCT( IF( quiz_pivot.exam_group_id = $university AND quiz_packs.deleted_at IS NULL, quiz_packs.id , NUlL ) ) ) + 
                COUNT( DISTINCT( IF( quiz_pivot.exam_group_id = $university AND 
                quiz_packs.deleted_at IS NULL OR 
                quiz_pivot.exam_group_id NOT IN ( '" . implode( "', '" , $univ_arr ) . "' ) AND
                quiz_pivot.quiz_pack_id NOT IN ( '" . implode( "', '" , $quiz_with_univ ) . "' ),
                quiz_pivot.quiz_pack_id , NUlL ) ) ))
                as filter_quizzes "),
      
            DB::raw(" COUNT( DISTINCT( IF( quiz_pivot.exam_group_id = $user_univ AND quiz_packs.deleted_at IS NULL, quiz_packs.id , NUlL ) ) ) + 
                COUNT( DISTINCT( IF( quiz_pivot.exam_group_id = $user_univ AND 
                quiz_packs.deleted_at IS NULL OR 
                quiz_pivot.exam_group_id NOT IN ( '" . implode( "', '" , $univ_arr ) . "' ) AND
                quiz_pivot.quiz_pack_id NOT IN ( '" . implode( "', '" , $quiz_with_univ ) . "' ),
                quiz_pivot.quiz_pack_id , NUlL ) ) )
                as group_quizzes "),
            
            DB::raw(" COUNT( DISTINCT( IF( quiz_packs.deleted_at IS NULL, quiz_packs.id, null) ) ) as all_quizzes ")
        )
        ->leftJoin( 'pivot_exam_group_quiz_pack as quiz_pivot', 'quiz_pivot.quiz_pack_id', '=', 'quiz_packs.id')
        ->get();
        
        $all_data_library = StethoSound::select(
            DB::raw("
                IF( $university = 000 , COUNT( DISTINCT( IF( stetho_sounds.deleted_at IS NULL, stetho_sounds.id, null) ) ) , 
                    COUNT( DISTINCT( IF( stetho_pivot.exam_group_id = $university AND stetho_sounds.deleted_at IS NULL, stetho_sounds.id , NUlL ) ) ) + 
                    COUNT( DISTINCT( IF( stetho_pivot.exam_group_id = $university AND 
                    stetho_sounds.deleted_at IS NULL OR 
                    stetho_pivot.exam_group_id NOT IN ( '" . implode( "', '" , $univ_arr ) . "' ) AND
                    stetho_pivot.stetho_sound_id NOT IN ( '" . implode( "', '" , $lib_with_univ ) . "' ),
                    stetho_pivot.stetho_sound_id , NUlL ) ) )
                )
                as filter_library "),
                
            DB::raw(" COUNT( DISTINCT( IF( stetho_pivot.exam_group_id = $user_univ AND stetho_sounds.deleted_at IS NULL, stetho_sounds.id , NUlL ) ) ) + 
                COUNT( DISTINCT( IF( stetho_pivot.exam_group_id = $user_univ AND 
                stetho_sounds.deleted_at IS NULL OR 
                stetho_pivot.exam_group_id NOT IN ( '" . implode( "', '" , $univ_arr ) . "' ) AND
                stetho_pivot.stetho_sound_id NOT IN ( '" . implode( "', '" , $lib_with_univ ) . "' ),
                stetho_pivot.stetho_sound_id , NUlL ) ) )
                as group_library "),
                
            DB::raw(" COUNT( DISTINCT( IF( stetho_sounds.deleted_at IS NULL, stetho_sounds.id, null) ) )  as all_library ")
        )
        ->leftJoin( 'pivot_exam_group_stetho_sound as stetho_pivot', 'stetho_pivot.stetho_sound_id', '=', 'stetho_sounds.id')
        ->get();
        
        $accounts_id = $params['user_id'] ? 'accounts.id = '.$params['user_id'] : true;
        
        $user_data = Accounts::select( 
            DB::raw(" IF( $university = 000 , IF( $accounts_id, COUNT(accounts.id) ,1 )  , SUM( IF( univ.id = $university AND $accounts_id, 1, 0 ) ) )  as filter_user"),
            DB::raw(" SUM( IF( univ.id = $user_univ, 1, 0 ) )  as group_user"),
            DB::raw(" COUNT(accounts.id)  as all_user"),
            
            DB::raw(" IF( $university = 000 , SUM( IF( univ.id = $user_univ AND $accounts_id, 1, 0 ) )  , SUM( IF( univ.id = $university AND $accounts_id, 1, 0 ) ) )  as teacher_filter_user"),
            DB::raw(" SUM( IF( univ.id = $user_univ, 1, 0 ) )  as teacher_group_user"),
            DB::raw(" SUM( IF( univ.id = $user_univ, 1, 0 ) )   as teacher_all_user")
            
        )
        ->leftJoin('onetime_keys as key', 'accounts.id', '=', 'key.customer_id')
        ->leftJoin('universities as univ', 'univ.id', '=', 'key.university_id')
        ->where('disable_analytics',0)
        ->where('status', 1)
        //->orWhere('status', 3)
        ->get();
        
        $numberOfLinesGF = UseLog::select(
            DB::raw(" (
                COUNT( IF(type = 3 AND university_id = $user_univ AND  end_time IS NOT NULL, id, null) )  +
                COUNT( DISTINCT( IF( type = 1 AND university_id = $user_univ AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) )  +
                COUNT( DISTINCT( IF( type = 2 AND university_id = $user_univ AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) ) 
            )   as all_group_line "),
            DB::raw("(
                COUNT( IF(type = 3 AND end_time IS NOT NULL, id, null) ) +
                COUNT( DISTINCT( IF( type = 1 AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) ) +
                COUNT( DISTINCT( IF( type = 2 AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) )
            )   as all_line "),
            DB::raw(" (
                COUNT( IF(type = 3 AND university_id = $user_univ AND end_time IS NOT NULL, id, null) )  +
                COUNT( DISTINCT( IF( type = 1 AND university_id = $user_univ AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) )  +
                COUNT( DISTINCT( IF( type = 2 AND university_id = $user_univ AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) ) 
            )   as  all_group_teacher_line"),
            DB::raw(" (
                COUNT( IF(type = 3 AND university_id = $user_univ AND  end_time IS NOT NULL, id, null) ) +
                COUNT( DISTINCT( IF( type = 1 AND university_id = $user_univ AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) ) +
                COUNT( DISTINCT( IF( type = 2 AND university_id = $user_univ AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) )
            )   as all_teacher_line ")
        )
        ->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date)
        ->whereHas('account', function ($query){
            $query->where('disable_analytics',0);
        })
        ->get();

        $numberOfLines = UseLog::select(
            DB::raw("( 
                COUNT( IF(type = 3 AND $get_university AND $get_exam_id AND $get_univ_id AND $get_user_id AND $get_quiz_id AND $get_library_id AND end_time IS NOT NULL, id, null) )   +
                COUNT( DISTINCT( IF( type = 1 AND $get_university AND $get_exam_id AND $get_univ_id AND $get_user_id AND $get_quiz_id AND $get_library_id AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) )  +
                COUNT( DISTINCT( IF( type = 2 AND $get_university AND $get_exam_id AND $get_univ_id AND $get_user_id AND $get_quiz_id AND $get_library_id AND  parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) ) 
            )   as all_filter_line "),
            DB::raw(" (
                COUNT( IF(type = 3 AND university_id = $user_univ AND $get_exam_id AND $get_univ_id AND $get_user_id AND $get_quiz_id AND $get_library_id AND end_time IS NOT NULL, id, null) )  +
                COUNT( DISTINCT( IF( type = 1 AND university_id = $user_univ AND $get_exam_id AND $get_univ_id AND $get_user_id AND $get_quiz_id AND $get_library_id AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) )  +
                COUNT( DISTINCT( IF( type = 2 AND university_id = $user_univ AND $get_exam_id AND $get_univ_id AND $get_user_id AND $get_quiz_id AND $get_library_id AND parent_id IS NOT NULL AND is_correct IS NOT NULL, parent_id, null ) ) ) 
            )   as all_filter_teacher_line ")
        )
        ->with("univ_account")
        ->with("univ_account.onetime_key")
        ->whereHas('univ_account', function ($query){
            $query->where('disable_analytics',0);
        });
        if($this->auth_user->role == "201"){
            $numberOfLines = $numberOfLines->whereHas('univ_account.onetime_key', function ($query2) use($user_univ){
                $query2->where('university_id', $user_univ);
            });
        }
        
        if($exam_type)
            $numberOfLines = $numberOfLines->where('type', $exam_type);
        
        if ($start_date && $end_date){ 
            // $end_date = date( "Y-m-d h:m:s", strtotime( "$end_date" ) );
            // $start_date = date( "Y-m-d h:m:s", strtotime( "$start_date " ) );
            $numberOfLines = $numberOfLines->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date);
        }
        
        $numberOfLines = $numberOfLines->get();
        
        $exam_id_data = $params['exam_id'] ? 1 : false ;
        $quiz_id_data = $params['quiz_id'] ? 1 : false ;
        $library_id_data = $params['library_id'] ? 1 : false ;
        $all_quiz_pack = count(DB::table("quiz_packs")->whereNull("deleted_at")->get());

        if($role == 'admin'){
        
            $count_group_filter = $univ_id ? 1 : $count_group ;
            $count_group_own = 1;
            $count_group_all = $count_group;
            
            $all_line = $numberOfLinesGF[0]->all_line;
            $group_line = $numberOfLinesGF[0]->all_group_line;
            $filter_line = $numberOfLines[0]->all_filter_line;
            
            $used_time = $all_used_time;
            $filter_time = $filter_used_time;
            
            $user_data_filter = $user_data[0]->filter_user;
            $user_data_group = $user_data[0]->group_user;
            $user_data_all = $user_data[0]->all_user;
            
            $all_exam_data = $all_data[0]->all_exam;

            $filterExam = UseLog::where("user_id", $user_id)
                                    ->leftJoin('accounts as user', 'user.id', '=', 'user_id')
                                    ->where('user.disable_analytics',0)
                                    ->distinct()
                                    ->count("exam_id");

            $all_exam_filter_data = $user_id ? $filterExam : $all_data[0]->filter_exam;
            $all_exam_group_data = $all_data[0]->group_exam;
            
            $all_quiz_data = $all_data_quiz[0]->all_quizzes;

            $filterQuizzes = UseLog::where("user_id", $user_id)
                                    ->leftJoin('accounts as user', 'user.id', '=', 'user_id')
                                    ->where('user.disable_analytics',0)
                                    ->distinct()
                                    ->count("quiz_pack_id");
                                    

            $all_quiz_filter_data = $user_id ? $filterQuizzes : $all_data_quiz[0]->filter_quizzes;
            $all_quiz_group_data = $all_data_quiz[0]->group_quizzes;
            
            $all_lib_data = $all_data_library[0]->all_library;

            $filterLibrary = UseLog::where("user_id", $user_id)
                                    ->leftJoin('accounts as user', 'user.id', '=', 'user_id')
                                    ->where('user.disable_analytics',0)
                                    ->distinct()
                                    ->count("lib_id");

            $all_lib_filter_data = $user_id ? $filterLibrary : $all_data_library[0]->filter_library;
            $all_lib_group_data = $all_data_library[0]->group_library;

            $all_exam_time = $all_exam_used_time;
            $all_exam_filter_time = $filter_exam_used_time;
            $all_exam_group_time = $own_exam_used_time;
            
            $all_quiz_time = $all_quiz_used_time;
            $all_quiz_filter_time = $filter_quiz_used_time;
            $all_quiz_group_time = $own_quiz_used_time;
            
            $all_lib_time = $all_library_used_time;
            $all_lib_filter_time = $filter_library_used_time;
            $all_lib_group_time = $own_library_used_time;
            
            $all_rate_exam = $all_rate[0]->exam_rate ? $all_rate[0]->exam_rate . '%' : '0%';
            $all_rate_filter_exam = $filter_rate[0]->filter_exam_rate ? $filter_rate[0]->filter_exam_rate. '%': '0%';
            $all_rate_group_exam = $all_rate[0]->qroup_exam_rate ? $all_rate[0]->qroup_exam_rate. '%': '0%';
            
            $all_rate_quiz = $all_rate[0]->quiz_rate. '%';
            $all_rate_filter_quiz = $all_rate[0]->qroup_exam_rate ?  $filter_rate[0]->filter_quiz_rate. '%': '0%';
            $all_rate_group_quiz = $all_rate[0]->qroup_exam_rate ?  $all_rate[0]->qroup_quiz_rate. '%': '0%';
            
        }
        else{
        
            $count_group_filter = 1;
            $count_group_own = 1;
            $count_group_all = $count_group;
            
            $all_line = $numberOfLinesGF[0]->all_line;
            $group_line = $numberOfLinesGF[0]->all_group_teacher_line;
            $filter_line = $numberOfLines[0]->all_filter_teacher_line;
            
            $used_time = $all_used_time;
            $filter_time = $teacher_filter_used_time;
            
            $user_data_filter = $user_data[0]->teacher_filter_user;
            $user_data_group = $user_data[0]->teacher_group_user;
            $user_data_all = $user_data[0]->all_user;
            
            $all_exam_data = $all_data[0]->all_exam;
            $all_exam_filter_data =  $exam_id_data ? $exam_id_data : $all_data[0]->group_exam;
            $all_exam_group_data = $all_data[0]->group_exam;

            $all_quiz_data = $all_data_quiz[0]->all_quizzes;
            $all_quiz_filter_data = $quiz_id_data ? $quiz_id_data : $all_data_quiz[0]->group_quizzes;
            $all_quiz_group_data = $all_data_quiz[0]->group_quizzes;
            
            $all_lib_data = $all_data_library[0]->all_library;
            $all_lib_filter_data = $library_id_data ? $library_id_data : $all_data_library[0]->filter_library;
            $all_lib_group_data = $all_data_library[0]->group_library;
            
            $all_exam_time = $all_exam_used_time;
            $all_exam_filter_time = $techer_filter_exam_used_time;
            $all_exam_group_time = $own_exam_used_time;
            
            $all_quiz_time = $all_quiz_used_time;
            $all_quiz_group_time = $own_quiz_used_time;
            $all_quiz_filter_time = $techer_filter_quiz_used_time;
            
            $all_lib_time = $all_library_used_time;
            $all_lib_filter_time = $techer_filter_library_used_time;
            $all_lib_group_time = $own_library_used_time;
            
            $all_rate_exam = $all_rate[0]->exam_rate ? $all_rate[0]->exam_rate. '%' : '0%';
            $all_rate_filter_exam = $filter_rate[0]->teacher_filter_exam_rate? $filter_rate[0]->teacher_filter_exam_rate."%" : '0%';
            $all_rate_group_exam = $all_rate[0]->qroup_exam_rate ? $all_rate[0]->qroup_exam_rate . '%': '0%';
            
            $all_rate_quiz = $all_rate[0]->quiz_rate ?  $all_rate[0]->quiz_rate. '%' : '0%';
            $all_rate_filter_quiz = $filter_rate[0]->teacher_filter_quiz_rate ? $filter_rate[0]->teacher_filter_quiz_rate. "%" : "0%";
            $all_rate_group_quiz = $all_rate[0]->qroup_quiz_rate ?   $all_rate[0]->qroup_quiz_rate. '%' : '0%';
            
        }
        

        $useLogLibrary = UseLog::select(
            [
                "use_logs.id",
                "use_logs.type",
                "use_logs.university_id",
                "use_logs.lib_id",
                "library.title_en",
                "library.title",
                "library.id",
                
                DB::raw("sum(TIMEDIFF(end_time, stt_time)) as used_time"),
                DB::raw("count(*) as total")
            ]
        ) 
        ->join('stetho_sounds as library', 'library.id', '=', 'use_logs.lib_id')
        ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
        ->where('user.disable_analytics',0)
        ->where(['use_logs.type' => 3])
        ->groupBy('lib_id')
        ->whereNotNull('end_time');

        if($this->auth_user->role == "201"){
            $useLogLibrary = $useLogLibrary->where("use_logs.university_id", $this->university_id);
		}

        $UnivAnalytics = new UnivAnalytics();

        $allPopularLibrary = $useLogLibrary->get();
        $allTimeLibrary = $UnivAnalytics->object_to_array_all($allPopularLibrary);

        usort($allTimeLibrary, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });

    $sec = 0;
        foreach ($allTimeLibrary as $lib) {
            $sec += $lib->used_time;
        }

        $totalLibraryUsageTime = gmdate("H:i:s", $sec);

        $targetTotal = [
            "Number of lines" => [
                "Filtered" => $filter_line, 
                "Own Data" => $group_line,
                "System Data" => $all_line,
            ],
            "Number of groups" => [
                "Filtered" => $count_group_filter,
                "Own Data" => $count_group_own,
                "System Data" => $count_group_all,
            ],
            "Number of users" =>  [
                "Filtered" => $user_data_filter,
                "Own Data" => $user_data_group,
                "System Data" => $user_data_all,
            ],
            "Total usage time" =>  [
                "Filtered" => $UnivAnalytics->sum_time($filter_time),
                "Own Data" => $UnivAnalytics->sum_time($own_totoal_time),
                "System Data" => $UnivAnalytics->sum_time($used_time),
            ],
            "Average usage time" =>  [
                "Filtered" => $UnivAnalytics->average_hourly($filter_time),
                "Own Data" => $UnivAnalytics->average_hourly($own_totoal_time),
                "System Data" => $UnivAnalytics->average_hourly($used_time),
            ],
            
            "Number of tests" =>  [
                "Filtered" => $all_exam_filter_data,
                "Own Data" => $all_exam_group_data,
                "System Data" => $all_exam_data,
            ],
            
            "Exam Correct answer rate" =>  [
                "Filtered" => $all_rate_filter_exam,
                "Own Data" => $all_rate_group_exam,
                "System Data" => $all_rate_exam
            ],
            
            "Total test usage time" =>  [
                "Filtered" => $UnivAnalytics->sum_time_hourly($all_exam_filter_time),
                "Own Data" => $UnivAnalytics->sum_time_hourly($all_exam_group_time),
                "System Data" => $UnivAnalytics->sum_time_hourly($all_exam_time),
            ],
            
            "Test average usage time" =>  [
                "Filtered" => $UnivAnalytics->average_hourly($all_exam_filter_time),
                "Own Data" => $UnivAnalytics->average_hourly($all_exam_group_time),
                "System Data" => $UnivAnalytics->average_hourly($all_exam_time),
            ],
            
            "Number of quizzes" =>  [
                "Filtered" => $all_quiz_filter_data, 
                "Own Data" => $all_quiz_group_data,
                "System Data" => $all_quiz_data,
            ],
            
            "Quiz correct answer rate" =>  [
                "Filtered" =>$all_rate_filter_quiz,
                "Own Data" => $all_rate_group_quiz,
                "System Data" => $all_rate_quiz
            ],
            
            "Quiz total usage time" =>  [
                "Filtered" => $UnivAnalytics->sum_time_hourly($all_quiz_filter_time),
                "Own Data" => $UnivAnalytics->sum_time_hourly($all_quiz_group_time),
                "System Data" => $UnivAnalytics->sum_time_hourly($all_quiz_time),
            ],
            
            "Quiz average usage time" =>  [
                "Filtered" => $UnivAnalytics->average_hourly($all_quiz_filter_time),
                "Own Data" => $UnivAnalytics->average_hourly($all_quiz_group_time),
                "System Data" => $UnivAnalytics->average_hourly($all_quiz_time),
            ],
            
            "Number of libraries" =>  [
                "Filtered" => $all_lib_filter_data, 
                "Own Data" => $all_lib_group_data,
                "System Data" => $all_lib_data,
            ],
            
            "Total library usage time" =>  [
                "Filtered" => $UnivAnalytics->sum_time_hourly($all_lib_filter_time),
                "Own Data" => $UnivAnalytics->sum_time_hourly($all_lib_group_time),
                "System Data" => gmdate("H:i:s", $sec),
            ],
            
            "Average library usage time" =>  [
                "Filtered" =>  $UnivAnalytics->average_hourly($all_lib_filter_time),
                "Own Data" => $UnivAnalytics->average_hourly($all_lib_group_time),
                "System Data" => $UnivAnalytics->average_hourly($all_lib_time),
            ]
            
        ];

        // if ($result) {
            $this->result = $targetTotal;
            $this->message = "success";
            $this->success = "ok";
        // }
// 
        return $this->response();
    }

     /**
     * Get Log Analytics Ranking 
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function getRanking (Request $request)
    {
        $params = request("params"); 

        //user info
        $user_univ_id = $params['user_univ_id'];
        $user_login_id = $params['user_login_id'];

        $rankingLib = new Ranking();
        
        $useLogExamRes = $rankingLib->getRankingQuery("useLogExamRes");
        $useLogQuizRes = $rankingLib->getRankingQuery("useLogQuizRes");
        $useLogUserExamRes = $rankingLib->getRankingQuery("useLogUserExamRes");
        $useLogQuiz = $rankingLib->getRankingQuery("useLogQuiz");
        $useLogLibrary = $rankingLib->getRankingQuery("useLogLibrary");
        $useLogLearning = $rankingLib->getRankingQuery("useLogLearning");

        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        if ($date_from && $date_to){
            $start_date = date( "Y-m-d", strtotime( $date_from))." 00:00:00";
            $end_date = date( "Y-m-d", strtotime( $date_to))." 23:59:59";
        }
        //date filter
        if ($date_from && $date_to){
            $useLogExamRes = $useLogExamRes->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
            $useLogQuizRes = $useLogQuizRes->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
            $useLogUserExamRes = $useLogUserExamRes->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
            $useLogQuiz = $useLogQuiz->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
            $useLogLibrary = $useLogLibrary->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
            $useLogLearning = $useLogLearning->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
        }

		if($this->auth_user->role == "201"){
            $useLogExamRes = $useLogExamRes->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $useLogQuizRes = $useLogQuizRes->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $useLogUserExamRes = $useLogUserExamRes->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $useLogQuiz = $useLogQuiz->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $useLogLibrary = $useLogLibrary->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
            $useLogLearning = $useLogLearning->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
		}
    
        $UnivAnalytics = new UnivAnalytics();
        
        // ==================================================
        //Popular Quiz Ranking
        $alluseLogQuiz = $useLogQuiz->get();
        $popularQuiz = $UnivAnalytics->object_to_array_all($alluseLogQuiz);
        
        usort($popularQuiz, function($a, $b) {
            if($a['total']==$b['total']) return 0;
            return $a['total'] < $b['total']?1:-1;
        });
        // Popular Goup Quiz Ranking
        $groupUseLogQuiz = $useLogQuiz->where(['use_logs.university_id' => $user_univ_id])->get();
        $gruoupPopularQuiz = $UnivAnalytics->object_to_array_all($groupUseLogQuiz);
        
        usort($gruoupPopularQuiz, function($a, $b) {
            if($a['total']==$b['total']) return 0;
            return $a['total'] < $b['total']?1:-1;
        });

        //Popular Filtered Quiz Ranking
        $allfilteredUseLogQuiz = $rankingLib->rankingFilter($useLogQuiz,$params,"popularQuiz");
        $filteredPopularQuiz = $UnivAnalytics->object_to_array_all($allfilteredUseLogQuiz);
        
        usort($filteredPopularQuiz, function($a, $b) {
            if($a['total']==$b['total']) return 0;
            return $a['total'] < $b['total']?1:-1;
        });

        //Popular Library Ranking
        $allPopularLibrary = $useLogLibrary->get();
        $popularLibrary = $UnivAnalytics->object_to_array_all($allPopularLibrary);
        $allTimeLibrary = $UnivAnalytics->object_to_array_all($allPopularLibrary);
        
        usort($popularLibrary, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });
        
        usort($allTimeLibrary, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });
        
        // Popular Goup Library Ranking
        $groupUseLogLibrary = $useLogLibrary->where(['use_logs.university_id' => $user_univ_id])->get();
        $gruoupPopularLibrary = $UnivAnalytics->object_to_array_all($groupUseLogLibrary);
        
        usort($gruoupPopularLibrary, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });
        

        //Popular Filtered Library Ranking
        $allfilteredPopularLibrary = $rankingLib->rankingFilter($useLogLibrary,$params,"popularLibray");
        $filteredPopularLibrary = $UnivAnalytics->object_to_array_all($allfilteredPopularLibrary);
        
        usort($filteredPopularLibrary, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });
        
        // return array(
        //     "data"=>$filteredPopularLibrary
        // );
        
        // Exam Result User Ranking
        $allUserExamRes = $useLogUserExamRes->groupBy('use_logs.user_id')->get();
        $allUserExamRes = $UnivAnalytics->object_to_array_all($allUserExamRes);
        usort($allUserExamRes, function($a, $b) {
            if($a['res']==$b['res']) return 0;
            return $a['res'] < $b['res']?1:-1;
        });
        
        // Exam Group Result User Ranking
        $getGroupUserExamRes = $useLogUserExamRes->groupBy('use_logs.user_id')->where(['use_logs.university_id' => $user_univ_id])->get();
        $gruoupUserExamRes = $UnivAnalytics->object_to_array_all($getGroupUserExamRes);
        usort($gruoupUserExamRes, function($a, $b) {
            if($a['res']==$b['res']) return 0;
            return $a['res'] < $b['res']?1:-1;
        });

        // Filtered Exam Result User Ranking
        $allfilteredUserExamRes = $rankingLib->rankingFilter($useLogUserExamRes,$params,"userExamRes");
        $filteredUserExamRes = $UnivAnalytics->object_to_array_all($allfilteredUserExamRes);
        usort($filteredUserExamRes, function($a, $b) {
            if($a['res']==$b['res']) return 0;
            return $a['res'] < $b['res']?1:-1;
        });
        
        // Exam Result Ranking correct`
        $allExamRes = $useLogExamRes->get();
        $allExamResCorrect = $UnivAnalytics->object_to_array_all($allExamRes);
        $sort = array();
        foreach($allExamResCorrect as $k=>$v) {
            $sort['rate'][$k] = $v['rate'];
            $sort['res'][$k] = $v['res'];
        }
        count($allExamResCorrect) > 0 ? array_multisort($sort['res'], SORT_DESC, $sort['rate'], SORT_DESC,$allExamResCorrect) : '';
        
        // Exam Grop Result Ranking correct`
        $allGroupExamRes = $useLogExamRes->where(['use_logs.university_id' => $user_univ_id])->get();
        $groupExamResCorrect = $UnivAnalytics->object_to_array_all($allGroupExamRes);
        $sort = array();
        foreach($groupExamResCorrect as $k=>$v) {
            $sort['rate'][$k] = $v['rate'];
            $sort['res'][$k] = $v['res'];
        }
        count($groupExamResCorrect) > 0 ? array_multisort($sort['res'], SORT_DESC, $sort['rate'], SORT_DESC,$groupExamResCorrect) : '';
        
        // Filtered Exam Result Ranking correct`
        $allFilterdExamResCorrect = $rankingLib->rankingFilter($useLogExamRes,$params,"examResCorrect");
        $filterdExamResCorrect = $UnivAnalytics->object_to_array_all($allFilterdExamResCorrect);
        $sort = array();
        foreach($filterdExamResCorrect as $k=>$v) {
            $sort['rate'][$k] = $v['rate'];
            $sort['res'][$k] = $v['res'];
        }
        count($filterdExamResCorrect) > 0 ? array_multisort($sort['res'], SORT_DESC, $sort['rate'], SORT_DESC,$filterdExamResCorrect) : '';

        // Quiz Result
        $allGroupQuizRes = $useLogQuizRes->get();
        $allQuizResCorrect = $UnivAnalytics->object_to_array_all($allGroupQuizRes);
        $sort = array();
        foreach($allQuizResCorrect as $k=>$v) {
            $sort['rate'][$k] = $v['rate'];
            $sort['res'][$k] = $v['res'];
        }
        count($allQuizResCorrect) > 0 ? array_multisort($sort['res'], SORT_DESC, $sort['rate'], SORT_DESC,$allQuizResCorrect) : '';
        
        // Quiz Group Result
        $groupGroupQuizRes = $useLogQuizRes->where(['use_logs.university_id' => $user_univ_id])->get();
        $groupQuizResCorrect = $UnivAnalytics->object_to_array_all($groupGroupQuizRes);
        $sort = array();
        foreach($groupQuizResCorrect as $k=>$v) {
            $sort['rate'][$k] = $v['rate'];
            $sort['res'][$k] = $v['res'];
        }
        count($groupQuizResCorrect) > 0 ? array_multisort($sort['res'], SORT_DESC, $sort['rate'], SORT_DESC,$groupQuizResCorrect) : '';
        
        // Filtered Quiz Group Result
        $allFilterdQuizResCorrect = $rankingLib->rankingFilter($useLogQuizRes,$params,"quizResCorrect");
        $filterdQuizResCorrect = $UnivAnalytics->object_to_array_all($allFilterdQuizResCorrect);
        $sort = array();
        foreach($filterdQuizResCorrect as $k=>$v) {
            $sort['rate'][$k] = $v['rate'];
            $sort['res'][$k] = $v['res'];
        }
        count($filterdQuizResCorrect) > 0 ? array_multisort($sort['res'], SORT_DESC, $sort['rate'], SORT_DESC,$filterdQuizResCorrect) : '';

        // Learning Time Ranking
        
        $groupUseLogLearning = $useLogLearning->where(['use_logs.university_id' => $user_univ_id])->get();
        $gruoupLearningTime = $UnivAnalytics->object_to_array_all($groupUseLogLearning);
        
        usort($gruoupLearningTime, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });
        
        //Learning Time Ranking
        $allfilteredUseLogLearning = $rankingLib->rankingFilter($useLogLearning,$params,"learningTime");
        $filteredLearningTime = $UnivAnalytics->object_to_array_all($allfilteredUseLogLearning);
        
        usort($filteredLearningTime, function($a, $b) {
            if($a['used_time']==$b['used_time']) return 0;
            return $a['used_time'] < $b['used_time']?1:-1;
        });
        // ==================================================
        
        $ranking = [
            //Popular Quiz Ranking
            "popular_quiz_rank" => [          
                "own_data" => [
                    'all_data' => $gruoupPopularQuiz,
                    'rank_1' => count($gruoupPopularQuiz) > 0 ? [ 'id' => $gruoupPopularQuiz[0]['quiz_pack_id'] , "name" => $gruoupPopularQuiz[0]['title_en'], "total_browsed" => $gruoupPopularQuiz[0]['total'], "total_time" => $gruoupPopularQuiz[0]['used_time'], ] : null,
                    'rank_2' => count($gruoupPopularQuiz) > 1 ? [ 'id' => $gruoupPopularQuiz[1]['quiz_pack_id'] , "name" => $gruoupPopularQuiz[1]['title_en'], "total_browsed" => $gruoupPopularQuiz[1]['total'], "total_time" => $gruoupPopularQuiz[0]['used_time'], ] : null,
                    'rank_3' => count($gruoupPopularQuiz) > 2 ? [ 'id' => $gruoupPopularQuiz[2]['quiz_pack_id'] , "name" => $gruoupPopularQuiz[2]['title_en'], "total_browsed" => $gruoupPopularQuiz[2]['total'], "total_time" => $gruoupPopularQuiz[0]['used_time'], ] : null,
                    'rank_4' => count($gruoupPopularQuiz) > 3 ? [ 'id' => $gruoupPopularQuiz[3]['quiz_pack_id'] , "name" => $gruoupPopularQuiz[3]['title_en'], "total_browsed" => $gruoupPopularQuiz[3]['total'], "total_time" => $gruoupPopularQuiz[0]['used_time'], ] : null,
                    'rank_5' => count($gruoupPopularQuiz) > 4 ? [ 'id' => $gruoupPopularQuiz[4]['quiz_pack_id'] , "name" => $gruoupPopularQuiz[4]['title_en'], "total_browsed" => $gruoupPopularQuiz[4]['total'], "total_time" => $gruoupPopularQuiz[0]['used_time'], ] : null, 
                ],
                "filtered" => [
                    'all_data' => $filteredPopularQuiz,
                    'rank_1' => count($filteredPopularQuiz) > 0 ? [ 'id' => $filteredPopularQuiz[0]['quiz_pack_id'] , "name" => $filteredPopularQuiz[0]['title_en'], "total_browsed" => $filteredPopularQuiz[0]['total'],  "total_time" => $filteredPopularQuiz[0]['used_time'],] : null,
                    'rank_2' => count($filteredPopularQuiz) > 1 ? [ 'id' => $filteredPopularQuiz[1]['quiz_pack_id'] , "name" => $filteredPopularQuiz[1]['title_en'], "total_browsed" => $filteredPopularQuiz[1]['total'],  "total_time" => $filteredPopularQuiz[0]['used_time'],] : null,
                    'rank_3' => count($filteredPopularQuiz) > 2 ? [ 'id' => $filteredPopularQuiz[2]['quiz_pack_id'] , "name" => $filteredPopularQuiz[2]['title_en'], "total_browsed" => $filteredPopularQuiz[2]['total'],  "total_time" => $filteredPopularQuiz[0]['used_time'],] : null,
                    'rank_4' => count($filteredPopularQuiz) > 3 ? [ 'id' => $filteredPopularQuiz[3]['quiz_pack_id'] , "name" => $filteredPopularQuiz[3]['title_en'], "total_browsed" => $filteredPopularQuiz[3]['total'],  "total_time" => $filteredPopularQuiz[0]['used_time'],] : null,
                    'rank_5' => count($filteredPopularQuiz) > 4 ? [ 'id' => $filteredPopularQuiz[4]['quiz_pack_id'] , "name" => $filteredPopularQuiz[4]['title_en'], "total_browsed" => $filteredPopularQuiz[4]['total'],  "total_time" => $filteredPopularQuiz[0]['used_time'],] : null,
                ],
                // "system_data" => [
                //     'all_data' => $popularQuiz,
                //     'rank_1' => count($popularQuiz) > 0 ? [ 'id' => $popularQuiz[0]['quiz_pack_id'] , "name" => $popularQuiz[0]['title_en'], "total_browsed" => $popularQuiz[0]['total'],  "total_time" => $popularQuiz[0]['used_time'],] : null,
                //     'rank_2' => count($popularQuiz) > 1 ? [ 'id' => $popularQuiz[1]['quiz_pack_id'] , "name" => $popularQuiz[1]['title_en'], "total_browsed" => $popularQuiz[1]['total'],  "total_time" => $popularQuiz[0]['used_time'],] : null,
                //     'rank_3' => count($popularQuiz) > 2 ? [ 'id' => $popularQuiz[2]['quiz_pack_id'] , "name" => $popularQuiz[2]['title_en'], "total_browsed" => $popularQuiz[2]['total'],  "total_time" => $popularQuiz[0]['used_time'],] : null,
                //     'rank_4' => count($popularQuiz) > 3 ? [ 'id' => $popularQuiz[3]['quiz_pack_id'] , "name" => $popularQuiz[3]['title_en'], "total_browsed" => $popularQuiz[3]['total'],  "total_time" => $popularQuiz[0]['used_time'],] : null,
                //     'rank_5' => count($popularQuiz) > 4 ? [ 'id' => $popularQuiz[4]['quiz_pack_id'] , "name" => $popularQuiz[4]['title_en'], "total_browsed" => $popularQuiz[4]['total'],  "total_time" => $popularQuiz[0]['used_time'],] : null,
                // ],
            ],
            // //Popular Library Ranking
            "popular_library_rank" => [
                "own_data" => [
                    'all_data' => $gruoupPopularLibrary,
                    'rank_1' => count($gruoupPopularLibrary) > 0 ? [ 'id' => $gruoupPopularLibrary[0]['lib_id'] , "name" => $gruoupPopularLibrary[0]['title_en'], "total_browsed" => $gruoupPopularLibrary[0]['total'],  "total_time" => $gruoupPopularLibrary[0]['used_time'],] : null,
                    'rank_2' => count($gruoupPopularLibrary) > 1 ? [ 'id' => $gruoupPopularLibrary[1]['lib_id'] , "name" => $gruoupPopularLibrary[1]['title_en'], "total_browsed" => $gruoupPopularLibrary[1]['total'],  "total_time" => $gruoupPopularLibrary[1]['used_time'],] : null,
                    'rank_3' => count($gruoupPopularLibrary) > 2 ? [ 'id' => $gruoupPopularLibrary[2]['lib_id'] , "name" => $gruoupPopularLibrary[2]['title_en'], "total_browsed" => $gruoupPopularLibrary[2]['total'],  "total_time" => $gruoupPopularLibrary[2]['used_time'],] : null,
                    'rank_4' => count($gruoupPopularLibrary) > 3 ? [ 'id' => $gruoupPopularLibrary[3]['lib_id'] , "name" => $gruoupPopularLibrary[3]['title_en'], "total_browsed" => $gruoupPopularLibrary[3]['total'],  "total_time" => $gruoupPopularLibrary[3]['used_time'],] : null,
                    'rank_5' => count($gruoupPopularLibrary) > 4 ? [ 'id' => $gruoupPopularLibrary[4]['lib_id'] , "name" => $gruoupPopularLibrary[4]['title_en'], "total_browsed" => $gruoupPopularLibrary[4]['total'],  "total_time" => $gruoupPopularLibrary[4]['used_time'],] : null,
                ],
                "filtered" => [
                    'all_data' => $filteredPopularLibrary,
                    'rank_1' => count($filteredPopularLibrary) > 0 ? [ 'id' => $filteredPopularLibrary[0]['lib_id'] , "name" => $filteredPopularLibrary[0]['title_en'], "total_browsed" => $filteredPopularLibrary[0]['total'],  "total_time" => $filteredPopularLibrary[0]['used_time'],] : null,
                    'rank_2' => count($filteredPopularLibrary) > 1 ? [ 'id' => $filteredPopularLibrary[1]['lib_id'] , "name" => $filteredPopularLibrary[1]['title_en'], "total_browsed" => $filteredPopularLibrary[1]['total'],  "total_time" => $filteredPopularLibrary[1]['used_time'],] : null,
                    'rank_3' => count($filteredPopularLibrary) > 2 ? [ 'id' => $filteredPopularLibrary[2]['lib_id'] , "name" => $filteredPopularLibrary[2]['title_en'], "total_browsed" => $filteredPopularLibrary[2]['total'],  "total_time" => $filteredPopularLibrary[2]['used_time'],] : null,
                    'rank_4' => count($filteredPopularLibrary) > 3 ? [ 'id' => $filteredPopularLibrary[3]['lib_id'] , "name" => $filteredPopularLibrary[3]['title_en'], "total_browsed" => $filteredPopularLibrary[3]['total'],  "total_time" => $filteredPopularLibrary[3]['used_time'],] : null,
                    'rank_5' => count($filteredPopularLibrary) > 4 ? [ 'id' => $filteredPopularLibrary[4]['lib_id'] , "name" => $filteredPopularLibrary[4]['title_en'], "total_browsed" => $filteredPopularLibrary[4]['total'],  "total_time" => $filteredPopularLibrary[4]['used_time'],] : null,
                ],
                // "system_data" => [
                //     'all_data' => $popularLibrary,
                //     'rank_1' => count($popularLibrary) > 0 ? [ 'id' => $popularLibrary[0]['lib_id'] , "name" => $popularLibrary[0]['title_en'], "total_browsed" => $popularLibrary[0]['total'],  "total_time" => $popularLibrary[0]['used_time'],] : null,
                //     'rank_2' => count($popularLibrary) > 1 ? [ 'id' => $popularLibrary[1]['lib_id'] , "name" => $popularLibrary[1]['title_en'], "total_browsed" => $popularLibrary[1]['total'],  "total_time" => $popularLibrary[1]['used_time'],] : null,
                //     'rank_3' => count($popularLibrary) > 2 ? [ 'id' => $popularLibrary[2]['lib_id'] , "name" => $popularLibrary[2]['title_en'], "total_browsed" => $popularLibrary[2]['total'],  "total_time" => $popularLibrary[2]['used_time'],] : null,
                //     'rank_4' => count($popularLibrary) > 3 ? [ 'id' => $popularLibrary[3]['lib_id'] , "name" => $popularLibrary[3]['title_en'], "total_browsed" => $popularLibrary[3]['total'],  "total_time" => $popularLibrary[3]['used_time'],] : null,
                //     'rank_5' => count($popularLibrary) > 4 ? [ 'id' => $popularLibrary[4]['lib_id'] , "name" => $popularLibrary[4]['title_en'], "total_browsed" => $popularLibrary[4]['total'],  "total_time" => $popularLibrary[4]['used_time'],] : null,
                // ],
            ],
            
            // Exam Result User Ranking
            "exam_result_user_rank" => [
                "own_data" => [
                    'all_data' => $gruoupUserExamRes,
                    'rank_1' => count($gruoupUserExamRes) > 0 ? [ 'id' => $gruoupUserExamRes[0]['id'] , "name" => $gruoupUserExamRes[0]['name'], "result" => $gruoupUserExamRes[0]['res']."%", ] : null,
                    'rank_2' => count($gruoupUserExamRes) > 1 ? [ 'id' => $gruoupUserExamRes[1]['id'] , "name" => $gruoupUserExamRes[1]['name'], "result" => $gruoupUserExamRes[1]['res']."%", ] : null,
                    'rank_3' => count($gruoupUserExamRes) > 2 ? [ 'id' => $gruoupUserExamRes[2]['id'] , "name" => $gruoupUserExamRes[2]['name'], "result" => $gruoupUserExamRes[2]['res']."%", ] : null,
                    'rank_4' => count($gruoupUserExamRes) > 3 ? [ 'id' => $gruoupUserExamRes[3]['id'] , "name" => $gruoupUserExamRes[3]['name'], "result" => $gruoupUserExamRes[3]['res']."%", ] : null,
                    'rank_5' => count($gruoupUserExamRes) > 4 ? [ 'id' => $gruoupUserExamRes[4]['id'] , "name" => $gruoupUserExamRes[4]['name'], "result" => $gruoupUserExamRes[4]['res']."%", ] : null,
                ],
                "filtered" => [
                    'all_data' => $filteredUserExamRes,
                    'rank_1' => count($filteredUserExamRes) > 0 ? [ 'id' => $filteredUserExamRes[0]['id'] , "name" => $filteredUserExamRes[0]['name'], "result" => $filteredUserExamRes[0]['res']."%", ] : null,
                    'rank_2' => count($filteredUserExamRes) > 1 ? [ 'id' => $filteredUserExamRes[1]['id'] , "name" => $filteredUserExamRes[1]['name'], "result" => $filteredUserExamRes[1]['res']."%", ] : null,
                    'rank_3' => count($filteredUserExamRes) > 2 ? [ 'id' => $filteredUserExamRes[2]['id'] , "name" => $filteredUserExamRes[2]['name'], "result" => $filteredUserExamRes[2]['res']."%", ] : null,
                    'rank_4' => count($filteredUserExamRes) > 3 ? [ 'id' => $filteredUserExamRes[3]['id'] , "name" => $filteredUserExamRes[3]['name'], "result" => $filteredUserExamRes[3]['res']."%", ] : null,
                    'rank_5' => count($filteredUserExamRes) > 4 ? [ 'id' => $filteredUserExamRes[4]['id'] , "name" => $filteredUserExamRes[4]['name'], "result" => $filteredUserExamRes[4]['res']."%", ] : null,
                ]
            ],
            // Exam Result  Ranking (Correct rate)
            "exam_correct_rate_rank" => [
                "own_data" => [
                    'all_data' => $groupExamResCorrect,
                    'rank_1' => count($groupExamResCorrect) > 0 ? [ 'id' => $groupExamResCorrect[0]['id'] , "name" => $groupExamResCorrect[0]['name'], "rate" => $groupExamResCorrect[0]['res']."%", ] : null,
                    'rank_2' => count($groupExamResCorrect) > 1 ? [ 'id' => $groupExamResCorrect[1]['id'] , "name" => $groupExamResCorrect[1]['name'], "rate" => $groupExamResCorrect[1]['res']."%", ] : null,
                    'rank_3' => count($groupExamResCorrect) > 2 ? [ 'id' => $groupExamResCorrect[2]['id'] , "name" => $groupExamResCorrect[2]['name'], "rate" => $groupExamResCorrect[2]['res']."%", ] : null,
                    'rank_4' => count($groupExamResCorrect) > 3 ? [ 'id' => $groupExamResCorrect[3]['id'] , "name" => $groupExamResCorrect[3]['name'], "rate" => $groupExamResCorrect[3]['res']."%", ] : null,
                    'rank_5' => count($groupExamResCorrect) > 4 ? [ 'id' => $groupExamResCorrect[4]['id'] , "name" => $groupExamResCorrect[4]['name'], "rate" => $groupExamResCorrect[4]['res']."%", ] : null,
                
                ],
                "filtered" => [
                    'all_data' => $filterdExamResCorrect,
                    'rank_1' => count($filterdExamResCorrect) > 0 ? [ 'id' => $filterdExamResCorrect[0]['id'] , "name" => $filterdExamResCorrect[0]['name'], "rate" => $filterdExamResCorrect[0]['res']."%", ] : null,
                    'rank_2' => count($filterdExamResCorrect) > 1 ? [ 'id' => $filterdExamResCorrect[1]['id'] , "name" => $filterdExamResCorrect[1]['name'], "rate" => $filterdExamResCorrect[1]['res']."%", ] : null,
                    'rank_3' => count($filterdExamResCorrect) > 2 ? [ 'id' => $filterdExamResCorrect[2]['id'] , "name" => $filterdExamResCorrect[2]['name'], "rate" => $filterdExamResCorrect[2]['res']."%", ] : null,
                    'rank_4' => count($filterdExamResCorrect) > 3 ? [ 'id' => $filterdExamResCorrect[3]['id'] , "name" => $filterdExamResCorrect[3]['name'], "rate" => $filterdExamResCorrect[3]['res']."%", ] : null,
                    'rank_5' => count($filterdExamResCorrect) > 4 ? [ 'id' => $filterdExamResCorrect[4]['id'] , "name" => $filterdExamResCorrect[4]['name'], "rate" => $filterdExamResCorrect[4]['res']."%", ] : null,
                ]
            ],
            // Quiz Result
            'quiz_result' => [
                'own_data' => [
                    'all_data' => $groupQuizResCorrect,
                    'rank_1' => count($groupQuizResCorrect) > 0 ? [ 'id' => $groupQuizResCorrect[0]['id'] , "name" => $groupQuizResCorrect[0]['title_en'], "rate" => $groupQuizResCorrect[0]['res']."%", ] : null,
                    'rank_2' => count($groupQuizResCorrect) > 1 ? [ 'id' => $groupQuizResCorrect[1]['id'] , "name" => $groupQuizResCorrect[1]['title_en'], "rate" => $groupQuizResCorrect[1]['res']."%", ] : null,
                    'rank_3' => count($groupQuizResCorrect) > 2 ? [ 'id' => $groupQuizResCorrect[2]['id'] , "name" => $groupQuizResCorrect[2]['title_en'], "rate" => $groupQuizResCorrect[2]['res']."%", ] : null,
                    'rank_4' => count($groupQuizResCorrect) > 3 ? [ 'id' => $groupQuizResCorrect[3]['id'] , "name" => $groupQuizResCorrect[3]['title_en'], "rate" => $groupQuizResCorrect[3]['res']."%", ] : null,
                    'rank_5' => count($groupQuizResCorrect) > 4 ? [ 'id' => $groupQuizResCorrect[4]['id'] , "name" => $groupQuizResCorrect[4]['title_en'], "rate" => $groupQuizResCorrect[4]['res']."%", ] : null,
                ],
                'filtered' => [
                    'all_data' => $filterdQuizResCorrect,
                    'rank_1' => count($filterdQuizResCorrect) > 0 ? [ 'id' => $filterdQuizResCorrect[0]['id'] , "name" => $filterdQuizResCorrect[0]['title_en'], "rate" => $filterdQuizResCorrect[0]['res']."%", ] : null,
                    'rank_2' => count($filterdQuizResCorrect) > 1 ? [ 'id' => $filterdQuizResCorrect[1]['id'] , "name" => $filterdQuizResCorrect[1]['title_en'], "rate" => $filterdQuizResCorrect[1]['res']."%", ] : null,
                    'rank_3' => count($filterdQuizResCorrect) > 2 ? [ 'id' => $filterdQuizResCorrect[2]['id'] , "name" => $filterdQuizResCorrect[2]['title_en'], "rate" => $filterdQuizResCorrect[2]['res']."%", ] : null,
                    'rank_4' => count($filterdQuizResCorrect) > 3 ? [ 'id' => $filterdQuizResCorrect[3]['id'] , "name" => $filterdQuizResCorrect[3]['title_en'], "rate" => $filterdQuizResCorrect[3]['res']."%", ] : null,
                    'rank_5' => count($filterdQuizResCorrect) > 4 ? [ 'id' => $filterdQuizResCorrect[4]['id'] , "name" => $filterdQuizResCorrect[4]['title_en'], "rate" => $filterdQuizResCorrect[4]['res']."%", ] : null,
                ],
            ],
            // Learning Time Ranking
            'learning_time' => [
                'own' => [
                    'all_data' => $gruoupLearningTime,
                    // gruoupTimeLibrary
                    'rank_1' => count($gruoupLearningTime) > 0 ? [ 'id' => $gruoupLearningTime[0]['id'] , "name" => $gruoupLearningTime[0]['name'], "total_time" => $gruoupLearningTime[0]['used_time'],] : null,
                    'rank_2' => count($gruoupLearningTime) > 1 ? [ 'id' => $gruoupLearningTime[1]['id'] , "name" => $gruoupLearningTime[1]['name'], "total_time" => $gruoupLearningTime[1]['used_time'],] : null,
                    'rank_3' => count($gruoupLearningTime) > 2 ? [ 'id' => $gruoupLearningTime[2]['id'] , "name" => $gruoupLearningTime[2]['name'], "total_time" => $gruoupLearningTime[2]['used_time'],] : null,
                    'rank_4' => count($gruoupLearningTime) > 3 ? [ 'id' => $gruoupLearningTime[3]['id'] , "name" => $gruoupLearningTime[3]['name'], "total_time" => $gruoupLearningTime[3]['used_time'],] : null,
                    'rank_5' => count($gruoupLearningTime) > 4 ? [ 'id' => $gruoupLearningTime[4]['id'] , "name" => $gruoupLearningTime[4]['name'], "total_time" => $gruoupLearningTime[4]['used_time'],] : null,
                
                ],
                'filtered' => [
                    'all_data' => $filteredLearningTime,
                    // allTimeLibrary
                    'rank_1' => count($filteredLearningTime) > 0 ? [ 'id' => $filteredLearningTime[0]['id'] , "name" => $filteredLearningTime[0]['name'], "total_time" => $filteredLearningTime[0]['used_time'],] : null,
                    'rank_2' => count($filteredLearningTime) > 1 ? [ 'id' => $filteredLearningTime[1]['id'] , "name" => $filteredLearningTime[1]['name'], "total_time" => $filteredLearningTime[1]['used_time'],] : null,
                    'rank_3' => count($filteredLearningTime) > 2 ? [ 'id' => $filteredLearningTime[2]['id'] , "name" => $filteredLearningTime[2]['name'], "total_time" => $filteredLearningTime[2]['used_time'],] : null,
                    'rank_4' => count($filteredLearningTime) > 3 ? [ 'id' => $filteredLearningTime[3]['id'] , "name" => $filteredLearningTime[3]['name'], "total_time" => $filteredLearningTime[3]['used_time'],] : null,
                    'rank_5' => count($filteredLearningTime) > 4 ? [ 'id' => $filteredLearningTime[4]['id'] , "name" => $filteredLearningTime[4]['name'], "total_time" => $filteredLearningTime[4]['used_time'],] : null,
                ],
            ]
            
        ];
        $this->result = $ranking;
        $this->message = "success";
        $this->success = "ok";
        
        return $this->response();
    }

   /**
     * Get Log Analytics Pichart  
     *
     * @return \Illuminate\Http\Request
     */
    public function getPieChart(Request $request)
    {
    
        $params = request("params"); 
        $user_univ_id = $params['user_univ_id'];
        $user_login_id = $params['user_login_id'];
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];
        
        if($date_from){
            $to = new Carbon($date_to);
            $from = new Carbon($date_from);
            $useLog = UseLog::where('type', '3' )->where('created_at', '>', $from->startOfDay())
                ->where('created_at', '<', $to->endOfDay())
                ->where(function ($q) {
                    $q->where('quiz_type', 'ECG')
                    ->orWhere('quiz_type', 'Xray')
                    ->orWhere('quiz_type', 'Ins')
                    ->orWhere('quiz_type', 'Aus')
                    ->orWhere('quiz_type', 'Palp')
                    ->orWhere('quiz_type', 'UCG')
                    ->orWhere('quiz_type', 'Stetho');
                })->whereNotNull('end_time')
                ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                ->where('user.disable_analytics',0);
            
           
        }else{
            $useLog = UseLog::where('type', '3' )
            ->where(function ($q) {
                $q->where('quiz_type', 'ECG')
                ->orWhere('quiz_type', 'Xray')
                ->orWhere('quiz_type', 'Ins')
                ->orWhere('quiz_type', 'Aus')
                ->orWhere('quiz_type', 'Palp')
                ->orWhere('quiz_type', 'UCG')
                ->orWhere('quiz_type', 'Stetho');
            })->whereNotNull('end_time')
            ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
            ->where('user.disable_analytics',0);
        } 
        // 2020-04-012021-05-30
                
		if($this->auth_user->role == "201")
            $useLog = $useLog->where("university_id", $this->university_id)->get();
        else 
            $useLog = $useLog->get();
        
        $UnivAnalytics = new UnivAnalytics();
        
        $useLog = $UnivAnalytics->object_to_array_all($useLog);
        
        $logdataOall = 0;
        $logdataFall = 0;
        $logdata = ['Aus' => 0,'ECG' => 0,'Xray' => 0,'Stetho' => 0,'Ins' => 0,'Palp' => 0,'UCG' => 0];
        $logdataF = ['Aus' => 0,'ECG' => 0,'Xray' => 0,'Stetho' => 0,'Ins' => 0,'Palp' => 0,'UCG' => 0];
        $logdataO = ['Aus' => 0,'ECG' => 0,'Xray' => 0,'Stetho' => 0,'Ins' => 0,'Palp' => 0,'UCG' => 0];
        
        foreach( $useLog as $key => $value ) {
        
            switch ( $value['quiz_type'] ) {
                case "ECG":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataOall++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataFall++ : '';
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
                case "Stetho":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataOall++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataFall++ : '';
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
                case "UCG":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataOall++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataFall++ : '';
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
                  
                case "Ins":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataOall++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataFall++ : '';
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
                case "Xray":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataOall++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataFall++ : '';
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
                  
                case "Palp":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
                  
                case "Aus":
                    $logdata[$value['quiz_type']]++;
                    $user_login_id == $value['user_id'] ? $logdataOall++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataFall++ : '';
                    $user_login_id == $value['user_id'] ? $logdataO[$value['quiz_type']]++ : '';
                    $user_univ_id == $value['university_id'] ? $logdataF[$value['quiz_type']]++ : '';
                  break;
              }
        }
        
        $result = [
            'filtered' => [
                'ecg' => $UnivAnalytics->getRate( $logdata['ECG'], count($useLog) ),
                'xray' => $UnivAnalytics->getRate( $logdata['Xray'], count($useLog) ),
                'stetho' => $UnivAnalytics->getRate( $logdata['Stetho'], count($useLog) ),
                'inspection' => $UnivAnalytics->getRate( $logdata['Ins'], count($useLog) ),
                'ausculaide' => $UnivAnalytics->getRate( $logdata['Aus'], count($useLog) ),
                'palpation' => $UnivAnalytics->getRate( $logdata['Palp'], count($useLog) ),
                'ucg' => $UnivAnalytics->getRate($logdata['UCG'], count($useLog) ),
                
                // 'ecg' => $UnivAnalytics->getRate( $logdataF['ECG'],$logdataFall ),
                // 'xray' => $UnivAnalytics->getRate( $logdataF['Xray'],$logdataFall ),
                // 'stetho' => $UnivAnalytics->getRate( $logdataF['Stetho'],$logdataFall ),
                // 'inspection' => $UnivAnalytics->getRate( $logdataF['Ins'],$logdataFall ),
                // 'ausculaide' => $UnivAnalytics->getRate( $logdataF['Aus'],$logdataFall ),
                // 'palpation' => $UnivAnalytics->getRate( $logdataF['Palp'],$logdataFall ),
                // 'ucg' => $UnivAnalytics->getRate( $logdataF['UCG'],$logdataFall ),
            ],
            'own_data' =>[
                'ecg' => $UnivAnalytics->getRate( $logdataF['ECG'],$logdataFall ),
                'xray' => $UnivAnalytics->getRate( $logdataF['Xray'],$logdataFall ),
                'stetho' => $UnivAnalytics->getRate( $logdataF['Stetho'],$logdataFall ),
                'inspection' => $UnivAnalytics->getRate( $logdataF['Ins'],$logdataFall ),
                'ausculaide' => $UnivAnalytics->getRate( $logdataF['Aus'],$logdataFall ),
                'palpation' => $UnivAnalytics->getRate( $logdataF['Palp'],$logdataFall ),
                'ucg' => $UnivAnalytics->getRate( $logdataF['UCG'],$logdataFall ),
                
                // 'ecg' => $UnivAnalytics->getRate( $logdataO['ECG'], $logdataOall ),
                // 'xray' => $UnivAnalytics->getRate( $logdataO['Xray'], $logdataOall ),
                // 'stetho' => $UnivAnalytics->getRate( $logdataO['Stetho'], $logdataOall ),
                // 'inspection' => $UnivAnalytics->getRate( $logdataO['Ins'], $logdataOall ),
                // 'ausculaide' => $UnivAnalytics->getRate( $logdataO['Aus'], $logdataOall ),
                // 'palpation' => $UnivAnalytics->getRate( $logdataO['Palp'], $logdataOall ),
                // 'ucg' => $UnivAnalytics->getRate( $logdataO['UCG'], $logdataOall ),
            ],
            "system" => [
                'ecg' => $UnivAnalytics->getRate( $logdata['ECG'], count($useLog) ),
                'xray' => $UnivAnalytics->getRate( $logdata['Xray'], count($useLog) ),
                'stetho' => $UnivAnalytics->getRate( $logdata['Stetho'], count($useLog) ),
                'inspection' => $UnivAnalytics->getRate( $logdata['Ins'], count($useLog) ),
                'ausculaide' => $UnivAnalytics->getRate( $logdata['Aus'], count($useLog) ),
                'palpation' => $UnivAnalytics->getRate( $logdata['Palp'], count($useLog) ),
                'ucg' => $UnivAnalytics->getRate($logdata['UCG'], count($useLog) ),
            ]
        ];
        if ($result) {
            $this->result = $result;
            $this->total_page = count($useLog);
            $this->message = "success";
            $this->success = "ok";
        }
        
        return $this->response();
    }

   /**
     * Get Dropdown Menu
     *
     * @return \Illuminate\Http\Request
     */
    public function selectMenu(Request $request) 
    {
    
        $params = request("params"); 
        $user_univ_id = $params['user_univ_id'];
        $user_login_id = $params['user_login_id'];
        
        $lib = StethoSound::select(
            'stetho_sounds.id as id',
            'stetho_sounds.title as title',
            'stetho_sounds.title_en as title_en'
        )->with(array('exam_groups' => function($query) {$query->select('id');}))->whereNull( 'deleted_at');
        
        $univ = Universities::select('id', 'name');
        
        $exams = Exams::select(
            'id as exam_id', 
            'name_jp as title', 
            'name as title_en',
            'quiz_pack_id',
            'group.exam_group_id as univ_id',
            DB::raw("GROUP_CONCAT( DISTINCT( IF( group.exam_group_id , group.exam_group_id, null) ) ) as univ_id")
        )
        ->whereNull( 'deleted_at')
        ->leftJoin( 'pivot_exam_exam_group as group',  'group.exam_id', '=', 'exam.id' )
        ->groupBy( 'exam.id' );
        
        $quiz = QuizPack::select(
            'quiz_packs.id as exam_id', 
            'title as title', 
            'title_en as title_en',
            'group.exam_group_id as univ',
            DB::raw("GROUP_CONCAT( DISTINCT( IF( group.exam_group_id , group.exam_group_id, null) ) ) as univ_id")
        )
        ->leftJoin( 'pivot_exam_group_quiz_pack as group',  'quiz_packs.id', '=', 'group.quiz_pack_id' )
        ->whereNull( 'deleted_at' )
        ->groupBy( 'quiz_packs.id' );
        
        $account = OnetimeKey::select(
            'user.name as name',
            'user.id as user_id',
            "onetime_keys.university_id as univ"
        )
            ->join('accounts as user', 'onetime_keys.customer_id', '=', 'user.id')
            ->where('user.disable_analytics',0)
            ->whereNotNull('user.id')
            ->where('status',1)
            ->whereNotNull('customer_id')
            //->whereNotNull('bwtk')
            ->distinct('user.id');

        $this_university_id = $this->university_id;
		if($this->auth_user->role == "201"){
            $lib = $lib->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->orHas('exam_groups', '<', 1);
            })
            ->get();
            
            $univ = $univ->where("id", $this->university_id)->get();

            $exams = $exams->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->orHas('exam_groups', '<', 1);
            })
            ->get();

            $quiz = $quiz->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->orHas('exam_groups', '<', 1);
            })
            ->get();
            $account = $account->where("onetime_keys.university_id", $this_university_id)->get();
		}
		else{
            $lib = $lib->get();
            $univ = $univ->get();
            $exams = $exams->get();
            $quiz = $quiz->get();
            $account = $account->get();
		}
        //$account = $account->get();
		
		$users = [];
		foreach ($account as $user ){
		    $getuser['account']['id'] = $user->user_id;
		    $getuser['account']['name'] = $user->name;
		    $getuser['account']['univ'] = $user->univ;
		    $getuser['user_id'] = $user->user_id;
            $users[] = $getuser;
		}
		
        $this->result = [
            'library' => $lib,
            'exam_type' => [
                ['id' =>  1, 'value' => 'Exam', 'value_jp' => '試験'],
                ['id' =>  2, 'value' => 'Quiz', 'value_jp' => 'クイズ'],
                ['id' =>  3, 'value' => 'Library', 'value_jp' => 'Library'],
            ],
            'quiz' => $quiz,
            'exam' => $exams,
            'user' => $users,
            'univ' => $univ
        ];
        $this->message = "success";
        $this->success = "ok";
        
        return $this->response();
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
