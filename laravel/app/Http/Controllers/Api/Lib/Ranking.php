<?php
namespace App\Http\Controllers\Api\Lib;

use Illuminate\Support\Facades\DB;
use App\UseLog; 
use Session;
class Ranking
{
    /**
     * ranking filter
     */
    public function rankingFilter($query,$params,$type)
    {
        $filter_user_id  = $params['user_id'];
        $filter_univ_id  = $params['univ_id'];
        $filter_exam_type  = $params['exam_type'];
        $filter_exam_id  = $params['exam_id'];
        $filter_quiz_id  = $params['quiz_id'];
        $filter_library_id  = $params['library_id'];
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        if ($date_from && $date_to){
            $start_date = date( "Y-m-d", strtotime( $date_from))." 00:00:00";
            $end_date = date( "Y-m-d", strtotime( $date_to))." 23:59:59";
        }
        //date filter
        if ($date_from && $date_to){
            $query = $query->where('use_logs.updated_at', '>=', $start_date)->where('use_logs.updated_at', '<=', $end_date);
        }
        //user_id filter
        if ($filter_user_id){
            $query = $query->where('use_logs.user_id', $filter_user_id);
        }
        //filter_univ_id
        if ($filter_univ_id){
            $query = $query->where('use_logs.university_id', $filter_univ_id);
        }
        switch($type){
            case "popularQuiz":
            case "quizResCorrect":
                if($filter_quiz_id){
                    $query = $query->where('use_logs.quiz_id', $filter_quiz_id);
                }
                break;
            case "popularLibray":
                if($filter_library_id){
                    $query = $query->where('use_logs.lib_id', $filter_library_id);
                }
                break;
            case "userExamRes":
            case "examResCorrect":
                if($filter_exam_id){
                    $query = $query->where('use_logs.exam_id', $filter_exam_id);
                }
                break;
            default: break;
        }
        
        return $query->get();
    }
    /**
     * ranking filter
     */
    public function getRankingQuery($type)
    {
        switch($type){
            case "useLogExamRes";
                return UseLog::select(
                    [
                        "use_logs.id",
                        "use_logs.exam_id",
                        "use_logs.type",
                        "use_logs.parent_id",
                        "use_logs.university_id",
                        "use_logs.is_correct",
                        "exams.name",
                        "exams.name_jp",
                        "exams.id",
                        DB::raw("TIME_FORMAT(sum(TIMEDIFF(end_time, stt_time)), '%H:%i:%s') as used_time"),
                        DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as res"),
                        DB::raw("sum(is_correct) as total_correct"),
                        DB::raw("count(*) as rate")
                    ]
                ) 
                ->join('exam as exams', 'exams.id', '=', 'use_logs.exam_id')
                ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
                ->where('user.disable_analytics',0)
                ->where(['use_logs.type' => 1])
                ->whereNotNull('is_correct')
                ->groupBy('exam_id');
            case "useLogQuizRes":
                return UseLog::select(
                    [
                        "use_logs.id",
                        "use_logs.quiz_pack_id",
                        "use_logs.type",
                        "use_logs.parent_id",
                        "use_logs.university_id",
                        "use_logs.is_correct",
                        "quiz.title_en",
                        "quiz.title",
                        "quiz.id",
                        DB::raw("TIME_FORMAT(sum(TIMEDIFF(end_time, stt_time)), '%H:%i:%s') as used_time"),
                        DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as res"),
                        DB::raw("sum(is_correct) as total_correct"),
                        DB::raw("count(*) as rate")
                    ]
                ) 
                ->join('quiz_packs as quiz', 'quiz.id', '=', 'use_logs.quiz_pack_id')
                ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
                ->where('user.disable_analytics',0)
                ->where(['use_logs.type' => 2])
                ->whereNotNull('is_correct')
                ->groupBy('quiz_pack_id');
            case "useLogUserExamRes": 
                return UseLog::select(
                    [
                        "use_logs.university_id",
                        "use_logs.is_correct",
                        "user.name",
                        "user.id",
                        DB::raw("sum(is_correct) as total_correct"),
                        DB::raw("count(*) as total"),
                        DB::raw("ceil((sum(is_correct) / count(*)) * 100 ) as res"),
                        // "quiz.title_en",
                    ]
                ) 
                ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
                ->where('user.disable_analytics',0)
                ->where(['use_logs.type' => 1])
                ->whereNotNull('is_correct');
            case "useLogQuiz":
                return UseLog::select(
                    [
                        "use_logs.id",
                        "use_logs.quiz_pack_id",
                        "use_logs.type",
                        "use_logs.university_id",
                        "use_logs.is_correct",
                        "use_logs.updated_at",
                        "quiz.title_en",
                        "quiz.title",
                        "quiz.id",
                        DB::raw("sum(TIMEDIFF(end_time, stt_time)) as used_time"),
                        DB::raw("count(*) as total")
                    ]
                ) 
                // ->join('exam as exams', 'exams.id', '=', 'use_logs.exam_id')
                ->join('quiz_packs as quiz', 'quiz.id', '=', 'use_logs.quiz_pack_id')
                ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
                ->where('user.disable_analytics',0)
                ->where(['use_logs.type' => 2])
                ->whereNotNull('is_correct')
                ->groupBy('use_logs.quiz_pack_id');
            case "useLogLibrary":
                return UseLog::select(
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
                ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
                ->where('user.disable_analytics',0)
                ->where(['use_logs.type' => 3])
                ->groupBy('lib_id')
                ->whereNotNull('use_logs.end_time');
            case "useLogLearning":
                return UseLog::select(
                    [
                        "user.id",
                        "use_logs.user_id",
                        "use_logs.university_id",
                        "user.name",
                        "user.user AS username",
                        
                        DB::raw("sum(TIMEDIFF(end_time, stt_time)) as used_time"),
                        DB::raw("count(*) as total")
                    ]
                ) 
                ->join('accounts as user', 'user.id', '=', 'use_logs.user_id')
                ->leftJoin('onetime_keys as key', 'key.customer_id' , '=' , 'user.id')
                ->where('user.disable_analytics',0)
                ->where(['use_logs.type' => 3])
                ->whereNotNull('use_logs.lib_id')
                ->whereNotNull('use_logs.end_time')
                ->groupBy('user_id');
            default:
                return new UseLog;;
        }
    }
    
}