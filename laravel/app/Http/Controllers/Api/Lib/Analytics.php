<?php
namespace App\Http\Controllers\Api\Lib;

use Illuminate\Support\Facades\DB;
use App\ExamResults;
use Session;
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

class Analytics
{

    protected $search_exam_id = null;
    protected $search_univ_id = null;
    protected $search_user_id = null;
    protected $search_quiz_id = null;
    protected $search_exam_type = null;
    protected $search_library_id = null;
    protected $search_user_univ_id = null;
    protected $search_user_login_id = null;
    protected $search_start_date = null;
    protected $search_end_date = null;

    protected $auth_user = null;
    protected $university_id = null;

    public function __construct($auth_user,$university_id,$params)
    {
		$this->auth_user = $auth_user;
        $this->university_id = $university_id;

        $this->search_exam_id = $params['exam_id'];
        $this->search_univ_id = $params['univ_id'];
        $this->search_user_id = $params['user_id'];
        $this->search_quiz_id = $params['quiz_id'];
        $this->search_exam_type = $params['exam_type'];
        $this->search_library_id = $params['library_id'];
        $this->search_user_univ_id = $params['user_univ_id'];
        $this->search_user_login_id = $params['user_login_id'];
        $this->search_start_date = $params['start_date'];
        $this->search_end_date = $params['end_date'];
        if ($params['start_date'] &&  $params['end_date']){
            $this->search_start_date = date( "Y-m-d", strtotime( $params['start_date']))." 00:00:00";
            $this->search_end_date = date( "Y-m-d", strtotime($params['end_date']))." 23:59:59";
        }
    }
    /**
     * Number Of Logs Data
     */
    public function getNumberOfData()
    {
        //filtered
        $filter_log_data = $this->getFilterData("number_of_lines")->get();
        //group data
        $own_log_data = $this->getLogQuery("number_of_lines")->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id)->get();
        //all data
        $all_logs_data =  $this->getLogQuery("number_of_lines")->get();

        return array(
            "Filtered" => $filter_log_data[0]->data_count, 
            "Own Data" => $own_log_data[0]->data_count,
            "System Data" => $all_logs_data[0]->data_count,
        );
    }

    /**
     * Number Of Groups
     */
    public function getNumberOfGroups()
    {
        $university = new Universities();
        //filtered
        $filter_data = $university;
        if($this->search_univ_id){
            $filter_data =  $filter_data->where("id", $this->search_univ_id);
        }
        $filter_data = $filter_data->get()->count();
        //all data
        $all__data =  $university->get()->count();

        return array(
            "Filtered" => ($this->auth_user->role == "201")? 1 : $filter_data, 
            "Own Data" => 1,
            "System Data" => $all__data,
        );
    }

    /**
     * Number Of Users
     */
    public function getNumberOfUsers()
    {
        //filtered
        $filter_data = $this->getLogQuery("number_of_users");
        if($this->search_univ_id){
            $filter_data =  $filter_data->where("onetime_keys.university_id", $this->search_univ_id);
        }
        if($this->search_user_id){
            $filter_data =  $filter_data->where("user.id", $this->search_user_id);
        }
        if($this->auth_user->role == "201"){
            $filter_data = $filter_data->where("onetime_keys.university_id", $this->university_id);
        }
        $filter_data =  $filter_data->get()->count();
        //group data
        $own_data = $this->getLogQuery("number_of_users")->where("onetime_keys.university_id", $this->university_id)->get()->count();
        $all_data = $this->getLogQuery("number_of_users")->get()->count();

        return array(
            "Filtered" => $filter_data, 
            "Own Data" => $own_data,
            "System Data" => $all_data,
        );
    }

    /**
     * get User Usage Time
     */
    public function getTotalUsageTime()
    {
        //filtered
        $filter_data = $this->getFilterData("user_usage_time")->get();
        //group data
        $own_data = $this->getLogQuery("user_usage_time")->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id)->get();
        //all data
        $all_data =  $this->getLogQuery("user_usage_time")->get();

        return [
            "usage_time" => array(
                            "Filtered" => $filter_data[0]->total_used_time ? $filter_data[0]->total_used_time : "00:00:00", 
                            "Own Data" => $own_data[0]->total_used_time ? $own_data[0]->total_used_time : "00:00:00",
                            "System Data" => $all_data[0]->total_used_time ? $all_data[0]->total_used_time : "00:00:00"
                        ),
            "avg_usage_time" => array(
                            "Filtered" => $filter_data[0]->average_usage_time ? $filter_data[0]->average_usage_time : "00:00:00", 
                            "Own Data" => $own_data[0]->average_usage_time ? $own_data[0]->average_usage_time : "00:00:00",
                            "System Data" => $all_data[0]->average_usage_time ? $all_data[0]->average_usage_time : "00:00:00",
                        ),

        ];
    }

    /**
     * Number Of Exams
     */
    public function getNumberOfExams()
    {
        $this_university_id = $this->university_id;
        //filtered
        $filter_data = $this->getLogQuery("number_of_exams");
        if($this->search_exam_id){
            $filter_data =  $filter_data ->where("exam.id", $this->search_exam_id);
        }
        if($this->auth_user->role == "201"){
            $filter_data = $filter_data->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                });
            });
        }
        $filter_data = $filter_data->get()->count();

        //group data
        $own_data = $this->getLogQuery("number_of_exams")->where(function ($groups) use ($this_university_id) {
            $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                $query->where('exam_group_id', $this_university_id);
            });
        })->get()->count();

        //all data
        $all_data = $this->getLogQuery("number_of_exams")->get()->count();
        
        return array(
            "Filtered" => $filter_data, 
            "Own Data" => $own_data,
            "System Data" => $all_data,
        );
    }

    /**
     * get Exam Correct Rate
     */
    public function getCorrectRate()
    {
        //filtered
        $filter_data = $this->getFilterData("correct_rate")->get();
        //group data
        $own_data = $this->getLogQuery("correct_rate")->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id)->get();
        //all data
        $all_data =  $this->getLogQuery("correct_rate")->get();

        return [
            "exam_correct_rate" => array(
                "Filtered" => $filter_data[0]->exam_rate, 
                "Own Data" => $own_data[0]->exam_rate,
                "System Data" => $all_data[0]->exam_rate
            ),
            "quiz_correct_rate" => array(
                "Filtered" => $filter_data[0]->quiz_rate, 
                "Own Data" => $own_data[0]->quiz_rate,
                "System Data" => $all_data[0]->quiz_rate
            ),
        ];
    }


    /**
     * get Exam Usage Time
     */
    public function getExamUsageTime()
    {
        //filtered
        $filter_data = $this->getFilterData("exam_usage_time")->get();
        //group data
        $own_data = $this->getLogQuery("exam_usage_time")->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id)->get();
        //all data
        $all_data =  $this->getLogQuery("exam_usage_time")->get();

        return [
            "usage_time" => array(
                            "Filtered" => $filter_data[0]->total_used_time ? $filter_data[0]->total_used_time : "00:00:00" , 
                            "Own Data" => $own_data[0]->total_used_time ? $own_data[0]->total_used_time : "00:00:00",
                            "System Data" => $all_data[0]->total_used_time ? $all_data[0]->total_used_time : "00:00:00"
                        ),
            "avg_usage_time" => array(
                            "Filtered" => $filter_data[0]->average_usage_time ? $filter_data[0]->average_usage_time : "00:00:00" , 
                            "Own Data" => $own_data[0]->average_usage_time ? $own_data[0]->average_usage_time : "00:00:00",
                            "System Data" => $all_data[0]->average_usage_time ? $all_data[0]->average_usage_time : "00:00:00",
                        ),

        ];
    }
    

    /**
     * Number Of Quiz
     */
    public function getNumberOfQuizes()
    {
        $this_university_id = $this->university_id;
        //filtered
        $filter_data = $this->getLogQuery("number_of_quiz");
        if($this->search_quiz_id){
            $filter_data =  $filter_data ->where("quiz_packs.id", $this->search_quiz_id);
        }
        if($this->auth_user->role == "201"){
            $filter_data = $filter_data->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                });
            });
        }
        $filter_data = $filter_data->get()->count();

        //group data
        $own_data = $this->getLogQuery("number_of_quiz")->where(function ($groups) use ($this_university_id) {
            $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                $query->where('exam_group_id', $this_university_id);
            });
        })->get()->count();

        //all data
        $all_data = $this->getLogQuery("number_of_quiz")->get()->count();
        
        return array(
            "Filtered" => $filter_data, 
            "Own Data" => $own_data,
            "System Data" => $all_data,
        );
    }

    /**
     * get Quiz Usage Time
     */
    public function getQuizUsageTime()
    {
        //filtered
        $filter_data = $this->getFilterData("quiz_usage_time")->get();
        //group data
        $own_data = $this->getLogQuery("quiz_usage_time")->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id)->get();
        //all data
        $all_data =  $this->getLogQuery("quiz_usage_time")->get();

        return [
            "usage_time" => array(
                            "Filtered" => $filter_data[0]->total_used_time ? $filter_data[0]->total_used_time : "00:00:00" , 
                            "Own Data" => $own_data[0]->total_used_time ? $own_data[0]->total_used_time : "00:00:00",
                            "System Data" => $all_data[0]->total_used_time ? $all_data[0]->total_used_time : "00:00:00"
                        ),
            "avg_usage_time" => array(
                            "Filtered" => $filter_data[0]->average_usage_time ? $filter_data[0]->average_usage_time : "00:00:00" , 
                            "Own Data" => $own_data[0]->average_usage_time ? $own_data[0]->average_usage_time : "00:00:00",
                            "System Data" => $all_data[0]->average_usage_time ? $all_data[0]->average_usage_time : "00:00:00",
                        ),

        ];
    }

    /**
     * Number Of Libraries
     */
    public function getNumberOfLibraries()
    {
        $this_university_id = $this->university_id;
        //filtered
        $filter_data = $this->getLogQuery("number_of_libs");
        if($this->search_library_id){
            $filter_data =  $filter_data ->where("stetho_sounds.id", $this->search_library_id);
        }
        if($this->auth_user->role == "201"){
            $filter_data = $filter_data->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                });
            });
        }
        $filter_data = $filter_data->get()->count();

        //group data
        $own_data = $this->getLogQuery("number_of_libs")->where(function ($groups) use ($this_university_id) {
            $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                $query->where('exam_group_id', $this_university_id);
            });
        })->get()->count();

        //all data
        $all_data = $this->getLogQuery("number_of_libs")->get()->count();
        
        return array(
            "Filtered" => $filter_data, 
            "Own Data" => $own_data,
            "System Data" => $all_data,
        );
    }
    
    /**
     * get Library Usage Time
     */
    public function getLibraryUsageTime()
    {
        //filtered
        $filter_data = $this->getFilterData("lib_usage_time")->get();
        //group data
        $own_data = $this->getLogQuery("lib_usage_time")->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id)->get();
        //all data
        $all_data =  $this->getLogQuery("lib_usage_time")->get();

        return [
            "usage_time" => array(
                            "Filtered" => $filter_data[0]->total_used_time ? $filter_data[0]->total_used_time : "00:00:00" , 
                            "Own Data" => $own_data[0]->total_used_time ? $own_data[0]->total_used_time : "00:00:00",
                            "System Data" => $all_data[0]->total_used_time ? $all_data[0]->total_used_time : "00:00:00"
                        ),
            "avg_usage_time" => array(
                            "Filtered" => $filter_data[0]->average_usage_time ? $filter_data[0]->average_usage_time : "00:00:00" , 
                            "Own Data" => $own_data[0]->average_usage_time ? $own_data[0]->average_usage_time : "00:00:00",
                            "System Data" => $all_data[0]->average_usage_time ? $all_data[0]->average_usage_time : "00:00:00",
                        ),

        ];
    }

    /**
     * Logs Query
     */
    public function getLogQuery($mode=""){
        $use_logs = new UseLog();
        $onetime_key = new OnetimeKey();
        $exams = new Exams();
        $quiz = new QuizPack();
        $libs = new StethoSound();
        switch($mode){
            case "number_of_lines":
                return $use_logs->select([
                    DB::raw("(COUNT( DISTINCT( IF(use_logs.type = 3 AND use_logs.end_time IS NOT NULL, CONCAT(use_logs.user_id,use_logs.lib_id), null))) +
                            COUNT( DISTINCT( IF( use_logs.type = 1 AND use_logs.parent_id IS NOT NULL AND use_logs.is_correct IS NOT NULL, use_logs.parent_id, null ) ) ) +
                            COUNT( DISTINCT( IF( use_logs.type = 2 AND use_logs.parent_id IS NOT NULL AND use_logs.is_correct IS NOT NULL, use_logs.parent_id, null ) ) ))
                            as data_count")
                    ])
                    ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                    ->join('onetime_keys as key', 'key.customer_id' , '=' , 'use_logs.user_id')
                    ->where('user.disable_analytics',0)
                    ->where('use_logs.updated_at', '>=', $this->search_start_date)->where('use_logs.updated_at', '<=',  $this->search_end_date);

            case "number_of_users":
                return $onetime_key->select('user.id as user_id')
                    ->join('accounts as user', 'onetime_keys.customer_id', '=', 'user.id')
                    ->where('user.disable_analytics',0)
                    ->whereNotNull('user.id')
                    ->where('status',1)
                    ->whereNotNull('customer_id')
                    ->distinct('user.id');

            case "user_usage_time": 
                return $use_logs->select([
                    DB::raw("SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time)))) as total_used_time"),//get total usage time
                    DB::raw("SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) as total_hour_time"),//get total usage time by sec
                    DB::raw("DATEDIFF(DATE_FORMAT(MAX(use_logs.stt_time), '%Y-%m-%d'), DATE_FORMAT(MIN(use_logs.stt_time), '%Y-%m-%d')) as total_days"),//get total days
                    DB::raw("COUNT(DISTINCT(use_logs.user_id)) as total_users"),//det all users with usage
                    DB::raw("SEC_TO_TIME(CAST(((
                        SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) / 
                        DATEDIFF(DATE_FORMAT(MAX(use_logs.stt_time), '%Y-%m-%d'), DATE_FORMAT(MIN(use_logs.stt_time), '%Y-%m-%d')) /
                        COUNT(DISTINCT(use_logs.user_id))
                        )) AS SIGNED)) AS average_usage_time"),//get average usage time by hh:mm:ss
                    ])
                    ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                    ->join('onetime_keys as key', 'key.customer_id' , '=' , 'use_logs.user_id')
                    ->where('user.disable_analytics',0)
                    ->whereNotNull('use_logs.end_time')
                    ->where('use_logs.updated_at', '>=', $this->search_start_date)->where('use_logs.updated_at', '<=',  $this->search_end_date);

            case "number_of_exams":
                return $exams->select('id as exam_id')
                    ->whereNull( 'deleted_at')
                    ->leftJoin( 'pivot_exam_exam_group as group',  'group.exam_id', '=', 'exam.id' )
                    ->groupBy( 'exam.id' );
            
            case "correct_rate":
                return $use_logs->select([
                        DB::raw(" CONCAT( IFNULL( ceil( ( count( IF( type = 1 AND is_correct = 1, is_correct, null ) ) /  count( IF( type = 1, is_correct, null ) ) ) * 100 ), 0), '%') as exam_rate"),
                        DB::raw(" CONCAT( IFNULL( ceil( ( count( IF( type = 2 AND is_correct = 1, is_correct, null ) ) /  count( IF( type = 2, is_correct, null ) ) ) * 100 ), 0), '%') as quiz_rate")
                    ])
                    ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                    ->join('onetime_keys as key', 'key.customer_id' , '=' , 'use_logs.user_id')
                    ->where('user.disable_analytics',0)
                    ->where('use_logs.updated_at', '>=', $this->search_start_date)->where('use_logs.updated_at', '<=',  $this->search_end_date);

            case "exam_usage_time":
                return $use_logs->select([
                    DB::raw("SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time)))) as total_used_time"),//get total usage time
                    DB::raw("SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) as total_hour_time"),//get total usage by sec
                    DB::raw("COUNT(DISTINCT(use_logs.exam_id)) as total_exams"),//get total exams
                    DB::raw("COUNT(DISTINCT(use_logs.user_id)) as total_users"),//det all users with usage
                    DB::raw("SEC_TO_TIME(CAST(((
                        SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) / 
                        COUNT(DISTINCT(use_logs.exam_id)) /
                        COUNT(DISTINCT(use_logs.user_id))
                        )) AS SIGNED)) AS average_usage_time"),//get average usage time by hh:mm:ss
                    ])
                    ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                    ->join('onetime_keys as key', 'key.customer_id' , '=' , 'use_logs.user_id')
                    ->where('user.disable_analytics',0)
                    ->whereNotNull('use_logs.end_time')
                    ->where('use_logs.type',1)
                    ->where('use_logs.updated_at', '>=', $this->search_start_date)->where('use_logs.updated_at', '<=',  $this->search_end_date);

            case "number_of_quiz":
                return $quiz->select('quiz_packs.id')
                    ->leftJoin( 'pivot_exam_group_quiz_pack as group',  'quiz_packs.id', '=', 'group.quiz_pack_id' )
                    ->whereNull( 'deleted_at' )
                    ->groupBy( 'quiz_packs.id' );
            
            case "quiz_usage_time":
                return $use_logs->select([
                    DB::raw("SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time)))) as total_used_time"),//get total usage time
                    DB::raw("SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) as total_hour_time"),//get total usage by sec
                    DB::raw("COUNT(DISTINCT(use_logs.quiz_pack_id)) as total_exams"),//get total quiz
                    DB::raw("COUNT(DISTINCT(use_logs.user_id)) as total_users"),//det all users with usage
                    DB::raw("SEC_TO_TIME(CAST(((
                        SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) / 
                        COUNT(DISTINCT(use_logs.quiz_pack_id)) /
                        COUNT(DISTINCT(use_logs.user_id))
                        )) AS SIGNED)) AS average_usage_time"),//get average usage time by hh:mm:ss
                    ])
                    ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                    ->join('onetime_keys as key', 'key.customer_id' , '=' , 'use_logs.user_id')
                    ->where('user.disable_analytics',0)
                    ->whereNotNull('use_logs.end_time')
                    ->where('use_logs.type',2)
                    ->where('use_logs.updated_at', '>=', $this->search_start_date)->where('use_logs.updated_at', '<=',  $this->search_end_date);
            
            case "number_of_libs":
                return $libs->select('stetho_sounds.id as id')
                    ->with(array('exam_groups' => function($query) {$query->select('id');}))->whereNull( 'deleted_at');
            
            case "lib_usage_time":
                return $use_logs->select([
                    DB::raw("SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time)))) as total_used_time"),//get total usage time
                    DB::raw("SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) as total_hour_time"),//get total usage by sec
                    DB::raw("COUNT(DISTINCT(use_logs.lib_id)) as total_libs"),//get total library
                    DB::raw("COUNT(DISTINCT(use_logs.user_id)) as total_users"),//det all users with usage
                    DB::raw("SEC_TO_TIME(CAST(((
                        SUM(time_to_sec(TIMEDIFF(use_logs.end_time, use_logs.stt_time))) / 
                        COUNT(DISTINCT(use_logs.lib_id)) /
                        COUNT(DISTINCT(use_logs.user_id))
                        )) AS SIGNED)) AS average_usage_time"),//get average usage time by hh:mm:ss
                    ])
                    ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                    ->join('onetime_keys as key', 'key.customer_id' , '=' , 'use_logs.user_id')
                    ->where('user.disable_analytics',0)
                    ->whereNotNull('use_logs.end_time')
                    ->where('use_logs.type',3)
                    ->where('use_logs.updated_at', '>=', $this->search_start_date)->where('use_logs.updated_at', '<=',  $this->search_end_date);
            
        }
    }

        /**
     * getLogsQuery
     */
    public function getFilterData($mode=""){
        $use_logs = $this->getLogQuery($mode);

        if($this->search_exam_type){
            $use_logs =  $use_logs->where("type",$this->search_exam_type);
        }
        if($this->search_univ_id){
            $use_logs =  $use_logs->where("use_logs.university_id", $this->search_univ_id)->where("key.university_id", $this->search_univ_id);
        }
        if($this->search_user_id){
            $use_logs =  $use_logs->where("use_logs.user_id", $this->search_user_id);
        }


        if(!$this->search_exam_type && ($this->search_library_id || $this->search_quiz_id || $this->search_exam_id)){
            $use_logs =  $use_logs->where(function ($logs){
                $logs->where("use_logs.exam_id", $this->search_exam_id)
                    ->orWhere("use_logs.quiz_pack_id", $this->search_quiz_id)
                    ->orWhere("use_logs.lib_id", $this->search_library_id);
            });
        }else if((int)$this->search_exam_type==1 && $this->search_exam_id){
            $use_logs =  $use_logs->where("use_logs.exam_id", $this->search_exam_id)->where("use_logs.type", 1);
        }else if((int)$this->search_exam_type==2 && $this->search_quiz_id){
            $use_logs =  $use_logs->where("use_logs.quiz_pack_id", $this->search_quiz_id)->where("use_logs.type", 2);
        }else if((int)$this->search_exam_type==3 && $this->search_library_id){
            $use_logs =  $use_logs->where("use_logs.lib_id", $this->search_library_id)->where("use_logs.type", 3);
        }


        if($this->auth_user->role == "201"){
            $use_logs= $use_logs->where("use_logs.university_id", $this->university_id)->where("key.university_id", $this->university_id);
        }

        return $use_logs;

    }

}