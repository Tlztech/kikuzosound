<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ExamResults;
use DB;
use DateTime;
use App\Quiz;
use App\Exams;
use App\UseLog;
use App\Account;
use App\QuizPack;
use App\OnetimeKey;
use App\StethoSound;
use App\Universities;
use App\Traits\AnalyticsTraits;
use App\Http\Common\UnivAnalytics;
use Carbon\Carbon;

class AnalyticsController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $result = ExamResults::select(
                "id",
                "exam_id",
                "quiz_id",
                "customer_id",
                "is_correct",
                "start_time",
                "finish_time",
                "created_at",
                "updated_at",
                DB::raw("TIME_FORMAT(TIMEDIFF(finish_time, start_time), '%H:%i:%s') as used_time")
            )
                ->with([
                    "exam",
                    "account",
                    "quiz_choice",
                ])->latest();
            
            if($this->auth_user->role == "201")
                $result = $result->where("university_id", $this->university_id)->get();
            else
                $result = $result->get();
            
            if ($result) {
                $this->result = $result;
                $this->message = "success";
                $this->success = "ok";
            }
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
        }



        return $this->response();
    }

    /**
     * Get Exam logs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getExamlog(Request $request)
    {
        $params = request("params");
        $page = $params['page'];
        $exam_id = $params['exam_id'];
        $user_id = $params['user_id'];
        $univ_id = $params['univ_id'];
        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $type = $params['type'];
    
        // $all = UseLog::select('id');
        $examLogs = UseLog::select([
            'use_logs.id',
            'use_logs.type',
            'use_logs.exam_id',
            'use_logs.quiz_id',
            'use_logs.user_id',
            'use_logs.university_id',
            'use_logs.quiz_pack_id',
            'use_logs.question_log',
            'use_logs.updated_at',
            'use_logs.parent_id',

            "exam.name as exam_name",
            "exam.id as exam_id",
            
            "user.name as author",
            "user.id as author_id",
            
            "university.name as univ_name",
            "university.id as univ",
            
            "quiz_pack.title as quiz_name",
            "quiz_pack.id as quiz_id",
            "quizzes.title_en as quizzes_name",
            
            DB::raw("sum(is_correct) as num_correct_exam"),
            DB::raw("count(use_logs.is_correct) as count"),
            DB::raw("sum(TIMEDIFF(end_time, stt_time)) as used_time"),
            DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as rate")
        ])
            ->where('use_logs.user_id', '!=', '0')
            ->leftJoin('quiz_packs as quiz_pack', 'quiz_pack.id', '=', 'use_logs.quiz_pack_id')
            ->leftJoin('exam', 'exam.id', '=', 'use_logs.exam_id')
            ->leftJoin('quizzes', 'quizzes.id', '=', 'use_logs.quiz_id')
            ->join('universities as university', 'university.id', '=', 'use_logs.university_id')
            ->leftJoin('accounts as user', 'user.id', '=', 'use_logs.user_id')
            ->leftJoin('onetime_keys as keys', 'keys.customer_id', '=', 'user.id')
            ->where('user.disable_analytics',0)
            ->whereNotNull('use_logs.is_correct');
        
        if($start_date > $end_date ){
            $this->message = "fail start date not accepted";
            $this->success = "ok";
            return $this->response();
        }
        
        if($type) 
            $examLogs = $examLogs->where('use_logs.type', $type);
        else
            $examLogs = $examLogs->where('use_logs.type', '<=', '2');
            
        if ($exam_id){
            $exam_type_id = explode ("_", $exam_id);
            $examLogs = $examLogs->where('use_logs.type', $exam_type_id[0])->where('use_logs.exam_id', $exam_type_id[1]);
        }
        if ($user_id)
            $examLogs = $examLogs->where('use_logs.user_id', $user_id);
        if ($univ_id)
            $examLogs = $examLogs->where('use_logs.university_id', $univ_id);
        if ($start_date && $end_date){
            // $pieces = explode("/", $end_date);
            // $day = intval($pieces[2]);
            // $end_date = $pieces[0].'-' .$pieces[1].'-'.($day+1); 
            $start_date = date( "Y-m-d", strtotime( $start_date))." 00:00:00";
            $end_date = date( "Y-m-d", strtotime( $end_date))." 23:59:59";
            $examLogs = $examLogs->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
        }
            
        if($this->auth_user->role == "201")
            $examLogs = $examLogs->where("use_logs.university_id", $this->university_id);
            
        if ($page == 'all') {
            $examLogs = $examLogs->groupBy('parent_id')->get();
        } else {
            $skipRow = $page * 20;
            $examLogs = $examLogs
                ->skip($skipRow)
                ->groupBy('use_logs.parent_id')
                ->take(20)->get();
        }

        // // $question_log = [];
        $res = [];
        $UnivAnalytics = new UnivAnalytics();
        $groupres = [];

        foreach ($examLogs as $log) {

            $dateOfRegister = DB::table("onetime_keys")->where("customer_id", $log->user_id)->first()->created_at;
            $dateOfRegister = date('Y-m-d', strtotime($dateOfRegister));
            $todayDate = date('Y-m-d');
        
            $diff = date_diff(date_create($dateOfRegister), date_create($todayDate));
            $days = $diff->format("%a");

            $studyHours = DB::table("use_logs")->where("user_id", $log->user_id)->where("type", 3)->whereNotNull("end_time" )->get();
            $totalStudyHour = "00:00:00";

            foreach ($studyHours as $studyHour) {
                $start = new Carbon($studyHour->stt_time);
                $end = new Carbon($studyHour->end_time);
        
                $time = $start->diff($end)->format('%H:%I:%S');
        
                $secs = strtotime($time)-strtotime("00:00:00");
                $sum_the_time = date("H:i:s",strtotime($totalStudyHour)+$secs);

                $totalStudyHour = $sum_the_time;
            }

            $total_hr = date("H", strtotime($totalStudyHour));
            $total_min = date("i", strtotime($totalStudyHour));
        
            $hrs = (float)($total_hr . "." . $total_min);
            $averageStudyHour = $days > 0 ? round($hrs / $days, 2) : $days;
            $exam_id = $log->type == 1 ? '1_'.$log->exam_id : '2_'.$log->quiz_pack_id;
            // $hrs = (float)($total_hr . "." . $total_min);
            // $averageStudyHour = $days > 0 ? round($hrs / $days, 2) : $days;
            $averageStudyHour = date("H:i:s", strtotime($totalStudyHour));

            $res[$log->id] = [
                'exam_id' => $exam_id,
                'exam_title' => $log->type == 1 ? $log->exam_name  : $log->quiz_name,
                'log_id' => $log->parent_id,
                'user_id' => $log->author_id,
                'user_name' => $log->author,
                'group_id' => $log->university_id,
                'group_name' => $log->univ_name,
                'correct_answers' => $log->num_correct_exam,
                'type' => $log->type,
                'number_of_question' => $log->count,
                'average_time' => $averageStudyHour,
                'rate' => $log->rate,
                'updated_at' => $log->updated_at,
                'data' => [
                    'x' => gmdate("H:i:s", $log->used_time),
                    'y' => $log->rate,
                ],
            ];
            
            $group_question[$exam_id .'_'. $log->university_id][] = $log->count;
            $group_answer[$exam_id .'_'. $log->university_id][] = $log->num_correct_exam;
            $group_time[$exam_id .'_'. $log->university_id][] = $log->used_time;
            $group_rate[$exam_id .'_'. $log->university_id][] = $log->rate;
            
            $groupres[$exam_id .'_'. $log->university_id] = [
                'exam_id' => $exam_id,
                'exam_title' => $log->type == 1 ? $log->exam_name  : $log->quiz_name,
                'log_id' => $log->parent_id,
                'user_id' => $log->author_id,
                'user_name' => $log->author,
                'group_id' => $log->university_id,
                'group_name' => $log->univ_name,
                'correct_answers' => $group_answer[$exam_id .'_'. $log->university_id],
                'type' => $log->type,
                'number_of_question' => $group_question[$exam_id .'_'. $log->university_id],
                'average_time' => gmdate("H:i:s", $log->used_time),
                'rate' => $log->rate,
                'updated_at' => $log->updated_at,
                'data' => [
                    'x' => $group_time[$exam_id .'_'. $log->university_id],
                    'y' => $group_rate[$exam_id .'_'. $log->university_id],
                ],
            ];
        }
        
        $all_group_res = [];
        foreach($groupres as $g_res){
            $g_res['correct_answers'] = $UnivAnalytics->sum($g_res['correct_answers']);
            $g_res['number_of_question'] = $UnivAnalytics->sum($g_res['number_of_question']);
            $g_res['data']['x'] =  gmdate("H:i:s",  $UnivAnalytics->sum($g_res['data']['x']));
            $g_res['data']['y'] = $UnivAnalytics->sum($g_res['data']['y']) / count($g_res['data']['y']);
            $all_group_res[] = $g_res;
            
        }
        
        $this->result = [
            'res' => $UnivAnalytics->object_to_array($res),
            'group_res' => $UnivAnalytics->object_to_array($all_group_res),
        ];
        $this->total_page = ceil($examLogs->count() / 20);
        $this->message = "success";
        $this->success = "ok";

        return $this->response();
    }

    /**
     * Get Menu
     *
     */
    public function getExamAnalyticMenu()
    {
        $exams = Exams::select(
            'exam.id as exam_id', 
            'exam.name_jp as exam_title', 
            'exam.name as exam_title_en',
            'quiz_pack_id',
            DB::raw("GROUP_CONCAT( DISTINCT( IF( group.exam_group_id , group.exam_group_id, null) ) ) as univ_id")
        )
        ->leftJoin( 'pivot_exam_exam_group as group',  'group.exam_id', '=', 'exam.id' )
        ->whereNull( 'deleted_at' )
        ->groupBy( 'exam.id' );
        
        $quizzes = QuizPack::select(
            'quiz_packs.id as exam_id', 
            'title as exam_title', 
            'title_en as exam_title_en',
            'group.exam_group_id as univ',
            DB::raw("GROUP_CONCAT( DISTINCT( IF( group.exam_group_id , group.exam_group_id, null) ) ) as univ_id")
        )
        ->leftJoin( 'pivot_exam_group_quiz_pack as group',  'quiz_packs.id', '=', 'group.quiz_pack_id' )
        ->whereNull( 'deleted_at' )
        ->groupBy( 'quiz_packs.id' );
            
        $users = OnetimeKey::select(
            'user.name as user_name',
            'user.id as user_id',
            "onetime_keys.university_id as univ"
        )
            ->join('accounts as user', 'onetime_keys.customer_id', '=', 'user.id')
            ->where('user.disable_analytics',0)
            ->whereNotNull('user.id')
            ->where('status',1)
            ->whereNotNull('customer_id')
            // ->whereNotNull('bwtk')
            ->distinct();

        $university = Universities::select('id as group_id', 'name as group_name');
        
        $this_university_id = $this->university_id;
        if($this->auth_user->role === 201){
            $university = $university->where("id", $this->university_id)->get();
            $exams = $exams->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->orHas('exam_groups', '<', 1);
            })
            ->get();

            $quizzes = $quizzes->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->orHas('exam_groups', '<', 1);
            })
            ->get();
            // $exams = $exams->where("university_id", $this->university_id)->orWhereNull("university_id")->orWhere("university_id",0)->get();
            // $quizzes = $quizzes->where("group.exam_group_id", $this->university_id)->orWhereNull("university_id")->get();
            $users = $users->where("onetime_keys.university_id", $this_university_id)->get();
        }else{
            $university = $university->get();
            $exams = $exams->get();
            $quizzes = $quizzes->get();
            $users = $users->get();
        }
        //$users = $users->get();
        $UnivAnalytics = new UnivAnalytics();
        
        foreach($exams as $exam){
            $exam->exam_id = '1_'.$exam->exam_id; 
        }
        foreach($quizzes as $quiz){
            $quiz->exam_id = '2_'.$quiz->exam_id;
        }
        
        $exams = $UnivAnalytics->object_to_array($exams);
        $quizzes = $UnivAnalytics->object_to_array($quizzes);

        $this->result = [
            'exams' => $exams,
            'quizzes' => $quizzes,
            'group' => $university,
            'users' => $users,
            'type' => [
                ['id' => 1, 'type' => 'Exam'],
                ['id' => 2, 'type' => 'Quiz']
            ],
            'title' => array_merge( $exams, $quizzes )
        ];
        $this->message = "success";
        $this->success = "ok";

        
        // $UnivAnalytics = new UnivAnalytics();
            
        return $this->response();
    }

    /**
     * Get Monthly rate by Line Chart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getExamChart(Request $request)
    {
        $params = request("params");
        $chart = $params['chart'];
        $univ_id = $params['univ_id'];
        $user_id = $params['user_id'];
        $type = $params['type'];
        $type_title = $params['type_title'] ? true : false;
        $srt_date = $params['srt_date'];
        $end_date = $params['end_date'];
        
        if($type_title){
            $t_title = explode("_", $params['type_title'] );
            $title = $t_title[1];
            $type =  $t_title[0];
        }

        if ($srt_date && $end_date){
            $srt_date = date( "Y-m-d", strtotime( $srt_date))." 00:00:00";
            $end_date = date( "Y-m-d", strtotime( $end_date))." 23:59:59";
        }
        
        $examLogs = UseLog::select(
            'use_logs.id',
            'use_logs.type',
            'use_logs.exam_id',
            'use_logs.quiz_id',
            'use_logs.quiz_pack_id',
            'use_logs.user_id',
            'use_logs.university_id',
            'user.name as username',
            'use_logs.parent_id',

            "quiz_pack.title AS quiz_title",
            "exam.name AS exam_name",
            "university.name AS university_name",

            DB::raw("CONCAT(use_logs.type, '_' , use_logs.exam_id) AS exam_chart"),
            DB::raw("CONCAT(use_logs.type, '_' , use_logs.quiz_id) AS quiz_chart"),
            DB::raw("sum(TIMEDIFF(end_time, stt_time)) as used_time"),
            DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as rate")
        )
        ->leftJoin('quiz_packs as quiz_pack', 'quiz_pack.id', '=', 'use_logs.quiz_pack_id')
        ->leftJoin('exam', 'exam.id', '=', 'use_logs.exam_id')
        ->leftJoin('universities as university', 'university.id', '=', 'use_logs.university_id')
        ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
        ->where('user.disable_analytics',0)
        ->whereNotNull('use_logs.university_id')
        ->whereNotNull('use_logs.parent_id')
        ->whereNotNull('use_logs.user_id')
        ->whereNotNull('use_logs.is_correct')
        ->groupBy('use_logs.parent_id');
        
        
        if($type) 
            $examLogs = $examLogs->where('use_logs.type', $type);
        else
            $examLogs = $examLogs->where('use_logs.type', '<=', '2');
        if ($type_title){
            $examLogs = $examLogs->where('use_logs.type', $type);
            if($type == 1)
                $examLogs = $examLogs->where('use_logs.exam_id', $title);
            else 
                $examLogs = $examLogs->where('use_logs.quiz_pack_id', $title);
        }
        if ($user_id)
            $examLogs = $examLogs->where('use_logs.user_id', $user_id);
        if ($univ_id)
            $examLogs = $examLogs->where('use_logs.university_id', $univ_id);
        if ($srt_date && $end_date)
            $examLogs = $examLogs->where('use_logs.updated_at', '>=', $srt_date)->where('use_logs.updated_at', '<=', $end_date);
            

        if($this->auth_user->role == "201")
            $examLogs = $examLogs->where("use_logs.university_id", $this->university_id)->get();
        else
            $examLogs = $examLogs->get();
            
        $chart_log = [];
        $over_all_time = [];
        $all_correct = [];
        
        $UnivAnalytics = new UnivAnalytics();
        
        foreach ($examLogs as $log ) {
            $time[$log->parent_id][] =  $log->used_time;
            $rate[$log->parent_id][] =  $log->rate;
            if ($chart == 'user') {
                $log_id = $log->parent_id;
                $log_label = $log->username;
            }
            else{
                $log_id = $log->group;
                $log_label = $log->university_name;
            }

            $dateOfRegister = DB::table("onetime_keys")->where("customer_id", $log->user_id)->first()->created_at;
            $dateOfRegister = date('Y-m-d', strtotime($dateOfRegister));
            $todayDate = date('Y-m-d');
        
            $diff = date_diff(date_create($dateOfRegister), date_create($todayDate));
            $days = $diff->format("%a");

            $studyHours = DB::table("use_logs")->where("user_id", $log->user_id)->where("type", 3)->whereNotNull("end_time" )->get();
            $totalStudyHour = "00:00:00";

            foreach ($studyHours as $studyHour) {
                $start = new Carbon($studyHour->stt_time);
                $end = new Carbon($studyHour->end_time);
        
                $timeDiff = $start->diff($end)->format('%H:%I:%S');
        
                $secs = strtotime($timeDiff)-strtotime("00:00:00");
                $sum_the_time = date("H:i:s",strtotime($totalStudyHour)+$secs);

                $totalStudyHour = $sum_the_time;
            }

            $total_hr = date("H", strtotime($totalStudyHour));
            $total_min = date("i", strtotime($totalStudyHour));
        
            $hrs = (float)($total_hr . "." . $total_min);
            $averageStudyHour = $days > 0 ? round($hrs / $days, 2) : $days;
            $averageStudyHour = date("H:i:s", strtotime($totalStudyHour));

            $chart_log[$log->parent_id] = [
                'id' => $log_id,
                'label' => $log_label,
                'tittle' => $log->type == 2 ? $log->quiz_title : $log->exam_name,
                'user' => $log->username,
                'univ' => $log->university_name,
                'data' => [
                    // 'x' => gmdate("H:i:s", array_sum($time[$log->parent_id])),
                    'x' => ($chart == "user") ? gmdate("H:i:s", array_sum($time[$log->parent_id])) : $averageStudyHour,
                    'y' => ceil(array_sum($rate[$log->parent_id]) / count($rate[$log->parent_id]))
                ],
            ];
        } 
         
        $this->result = [
            'chart' => $UnivAnalytics->object_to_array($chart_log),
            'test' => $examLogs
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
    public function show($id, $examGroupId, $scope, $whereCapable)
    {
        try {
            switch ($scope) {
                case 1: // content
                    $result = $this->getTopExams($id, $examGroupId, $whereCapable);
                    break;
                case 2: // Score of Exam
                    $result = $this->getScoreExam($id, $examGroupId, $whereCapable);
                    break;
                case 3: // Time if Study
                    $result = $this->getExamStudyTime($id, $examGroupId, $whereCapable);
                    break;
                case 4: // Rate of Correct Exam
                    $result = $this->getPercentExams($id, $examGroupId, $whereCapable);
                    break;
                case 5: // Rate of Correct quizzes
                    $result = $this->getPercentQuiz($id, $examGroupId, $whereCapable);
                    break;
                default:
                    $result = ExamResults::with([
                        "account",
                        "quiz_choice",
                        "exam"
                    ])
                        ->where("customer_id", $id)
                        ->whereHas('exam', function ($query) use ($examGroupId) {
                            $query->where('university_id', $examGroupId);
                        })
                        ->latest()->get();
                    break;
            }

            $this->result = $result;
            $this->message = "success";
            $this->success = "ok";
        } catch (\Exception $e) {
            $this->message = "error occur!";
        }
        return $this->response();
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
