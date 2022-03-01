<?php

namespace App\Http\Controllers;

use App\Exam;
use App\Exams;
use App\UseLog;
use App\ExamGroup;
use App\ExamResults;
use App\OnetimeKey;
use Illuminate\Http\Request;
use App\QuizPack;
use App\Quiz;
use App\QuizChoice;
use App\StethoSound;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Common\QuizExam;
use Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// クイズパック画面
class QuizPacksController extends Controller
{
  private $content_type_string = [0=>"Stetho", 1=>"Aus" , 2=>"Palp" ,3=>"ECG", 4=>"Ins", 5=>"Xray", 6=>"UCG", 7=>"final"];
  public function __construct()
  {
    if(Session::get('lang')) App::setLocale(Session::get('lang'));
  }
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() 
  {
    //.htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
    setcookie("audioaccess","true");    // .htaccessで許可
    $isExamGroup = false;
    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');

    // クイズパック一覧を取得する
    $quiz_packs = QuizPack::publicAll()
    ->where(function($groups) use ($universityId) {
      $groups->whereHas('exam_groups', function($query1) use ($universityId){
          $query1->where('exam_group_id',$universityId);//has group attribute
      })->orHas('exam_groups', '<', 1);//has no group attribute
    })
    ->whereNull('deleted_at')
    ->orderby("disp_order")
    ->get();

    return view('quiz_packs',compact('quiz_packs', 'isExamGroup'));
  }

  public function exams() {
    //.htaccessでのcookieチェック(これがないと[audio/stetho_sounds]アクセス不可)
    setcookie("audioaccess","true");    // .htaccessで許可
    $isExamGroup = true;

    $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');
    $oneTimeKey = OnetimeKey::where("customer_id", $userId)->first();

    if ($oneTimeKey->is_exam_group == 1) {
      $universityId = $oneTimeKey->university_id;

      $examGroupName = ExamGroup::find($universityId)->name;

      $exams = Exams::where("is_publish", 1)
      ->with(array('quiz_pack'))
      ->where(function($groups) use ($universityId) {
        $groups->whereHas('exam_groups', function($query1) use ($universityId){
            $query1->where('exam_group_id',$universityId);//has group attribute
        })->orHas('exam_groups', '<', 1);//has no group attribute
      })
      ->whereNull('deleted_at')
      ->orderBy("disp_order", "desc")
      ->get();
      
      return view('quiz_packs',compact('exams', 'userId', 'isExamGroup', 'examGroupName'));
    } else {
      throw new NotFoundHttpException();
    }
    
  }

  // クイズ回答画面
  public function quiz($id) 
  {
    $quiz_packs = QuizPack::publicAll()->orderby("disp_order")->get();
    return view('_partials.quiz',['action'=>'GET']);
  }

  // クイズスタート画面
  public function quiz_start(Request $request, $quiz_pack_id, $exam_id = null) 
  {
    Session::forget('MEMBER_3SP_EXAM_QUIZ_ID');//forget old quiz id when start new quiz
    // 1問目のクイズを取得する
    $quiz_pack = QuizPack::withTrashed()->find($quiz_pack_id);
    // App::setLocale($quiz_pack->lang == "EN" ? "en" : "ja");
    $first_quiz = null;
    // ランダムの場合
    if (!empty($quiz_pack ) && $quiz_pack->quiz_order_type == QuizPack::$ORDER_RANDOM) {
      $first_quiz = $quiz_pack->quizzes()->get()->random();
    }
    // 固定の場合
    else {
      $first_quiz = DB::table('quiz_quiz_pack')
        ->where('quiz_pack_id', $quiz_pack->id)
        ->orderBy('disp_order', 'ASC')->first();
      if(!empty($first_quiz)){
        $first_quiz->id = $first_quiz->quiz_id;
      }
    }

    $next_quiz_id = $first_quiz? $first_quiz->id : null;
    $title = "";

    if($exam_id) {
      $exam = DB::table('exam')->where("id", $exam_id)->first();
      $title = (Config::get('app.locale') == "en") ? $exam->name : $exam->name_jp;
    } else {
      $title = (Config::get('app.locale') == "en") ? $quiz_pack->title_en : $quiz_pack->title;
    }
    $old_choices = \Session::get("old_choices",[]);
    // ユーザ操作ログ記録
    $this->writeUserTrackingLog($request, 'QUIZ_PACK', 'QUIZ_START', 'INIT', $quiz_pack);
    return view('_partials.quiz_start',compact('quiz_pack_id','next_quiz_id','title', 'exam_id'));
  }

  // クイズ回答選択画面
  public function quiz_answer_select(Request $request, $quiz_pack_id, $quize_id, $exam_id = null) {
    $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');
    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
    $start_time = Carbon::now();
    $useLog = new UseLog;
    $old_choices = \Session::get("old_choices",[]);
    $old_observations = \Session::get("old_observations",[]);
    $quiz_pack = QuizPack::findOrFail($quiz_pack_id);
    $quiz_ids = $quiz_pack->quizzes()->get()->pluck('id')->toArray();
    // App::setLocale($quiz_pack->lang == "EN" ? "en" : "ja");
    $quiz = Quiz::findOrFail($quize_id);
    $quiz_type = ($quiz->is_optional==1)?0:1;
    // dd($stetho);
    // ユーザ操作ログ記録
    //save user logs
    $question_log[]= array(
      "quiz"=>$quiz_ids
    );
    if(!Session::get('MEMBER_3SP_EXAM_QUIZ_ID')){ //save use log only on quiz start
      $this->saveUseLogs($useLog,$exam_id?1:2,null,$exam_id,$quiz_pack_id,null,$userId,$universityId,$question_log,null);
      Session::set("MEMBER_3SP_EXAM_QUIZ_ID", $useLog->id);
    }

    $this->writeUserTrackingLog($request, $request->input('screen_cd'), 'QUIZ', 'INIT', $quiz_pack, $quiz);
    return view('_partials.quiz_answer_select',compact('quiz','quiz_pack','exam_id','quiz_type','start_time'))->with('old_choices',$old_choices)->with('old_observations',$old_observations);
  }

  //multiple quiz select content 
  public function multi_quiz_content_select(Request $request, $quiz_pack_id, $quize_id, $lib_type, $exam_id = null) {
    $start_time = $request->has("start_time")? Carbon::parse($request->input("start_time")) : Carbon::now();
    $old_choices = $request->has("old_choices") ? $request->input("old_choices") : [];
    $old_observations = $request->has("old_observations") ? $request->input("old_observations") : [];
    $quiz_pack = QuizPack::findOrFail($quiz_pack_id);
    $quiz = Quiz::findOrFail($quize_id);
    $contents = $quiz->stetho_sounds()->where('lib_type',$lib_type)->get();
    $quiz_type = ($quiz->is_optional==1)?0:1;

    //set next start time for multi quiz contents
    Session::set("MULTI_EXAM_QUIZ_NEXT_STT_TIME", Carbon::now());
    // ユーザ操作ログ記録
    $this->writeUserTrackingLog($request, $request->input('screen_cd'), 'QUIZ', 'INIT', $quiz_pack, $quiz);
    return view('_partials.quiz_answer_select',compact('quiz','quiz_pack','exam_id','contents','quiz_type','start_time'))->with('old_choices',$old_choices)->with('old_observations',$old_observations);
  }

  // クイズ回答判定
  public function quiz_answer_choice(Request $request, $quiz_pack_id, $quize_id)
  {
    $quiz_choice_id = $request->input("quiz_choice_id");
    $quiz_choice_title = $request->input("quiz_choice_title");
    $old_choices = $request->has("old_choices") ? $request->input("old_choices") : [];
    $old_observations = $request->has("old_observations") ? $request->input("old_observations") : [];
    $quiz_end = $request->input("quiz_end");
    $exam_id = $request->input("exam_id");
    $is_multiple = filter_var($request->input("is_multiple"), FILTER_VALIDATE_BOOLEAN);

    // ユーザ操作ログ記録
    $quiz_pack = QuizPack::findOrFail($quiz_pack_id);
    $quiz =  Quiz::findOrFail($quize_id);
    $quiz_type = ($quiz->is_optional==1)?0:1;

    //get all included libs for logs
    $included_libs = $quiz->stetho_sounds()->get();
    $content_ids = $included_libs->pluck("id")->toArray();
    
    if($is_multiple){
      $content_type = 7;
    }else{
      $single_quiz_content_type = $included_libs->first();
      $content_type = $single_quiz_content_type?$single_quiz_content_type->lib_type:7;
    }

    //get choices and correct answer
    $start_time = $request->has("start_time")? Carbon::parse($request->input("start_time")) : Carbon::now();
    $finish_time = Carbon::now();
    $is_correct = 0;
    $correct_answer= null;
    $correct = null;
    $QuizExam = new QuizExam();
    if ($quiz_choice_id >= 0) {
      $correct_answer = $QuizExam->getCorrectAnswer($quiz, $quiz_type, $content_type);
      $correct = $QuizExam->checkCorrectAnswer($quiz_type, $content_type, $correct_answer, $quiz_choice_title, $quiz_choice_id);
      $is_correct = $correct["is_correct"];
    }

    //save user logs
    $useLog = new UseLog;
    $Logs = $QuizExam->assignLogsVariables($content_ids, $is_multiple, $quiz_choice_id, $quiz_choice_title, $correct);

    $question_type =$this->content_type_string[$content_type];
    $this->saveUseLogs($useLog,$exam_id?1:2,$Logs["parent_id"],$exam_id,$quiz_pack_id,$quize_id,$Logs["userId"],$Logs["universityId"],$Logs["question_log"],$Logs["answer_log"],$question_type,$is_correct,$start_time,$finish_time);
    //save logs for library contents of quiz
    if(!$is_multiple){
      foreach ($content_ids as $lib_id) {
        $useLog = new UseLog;
        $this->saveUseLogs($useLog,$exam_id?1:2,$Logs["parent_id"],$exam_id,$quiz_pack_id,$quize_id,$Logs["userId"],$Logs["universityId"],null,null,$question_type,null,$start_time,$finish_time,$lib_id);
      }
    }

    if ($exam_id != "" || $exam_id != null) {
      $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');

      $examResults = new ExamResults;
      $examResults->exam_id = $exam_id;
      $examResults->quiz_id = $quiz_choice_id;
      $examResults->customer_id = $userId?$userId:0;
      $examResults->is_correct = $is_correct;
      $examResults->start_time =  $start_time;
      $examResults->finish_time = $finish_time;
      $examResults->save();
    } 

    if ( $quiz_choice_id < 0 ) {
      $this->writeUserTrackingLog($request, 'QUIZ', 'QUIZ', 'TIME_OVER', $quiz_pack, $quiz);
    }
    else {
      $quiz_choice = QuizChoice::find($quiz_choice_id);
      $this->writeUserTrackingLog($request, 'QUIZ', 'QUIZ', 'ANSWER', $quiz_pack, $quiz, $quiz_choice);
    }

    // タイムオーバの場合、quiz_choice_idが-1
    $old_choices[] = ['is_correct'=>$is_correct,'quiz_id'=>$quize_id, 'quiz_choice_id'=>$quiz_choice_id, 'quiz_choice_title'=>$quiz_choice_title, 'observations'=>$old_observations];
    if ((count($old_choices) < $quiz_pack->max_quiz_count) && $quiz_end != 1) {
      // 次のクイズを取得する
      $exclude_ids = array_map(function($q) { return $q['quiz_id'];}, $old_choices);
      $next_quiz = $quiz_pack->nextQuiz($exclude_ids);
      $quize_id = $next_quiz->id;
      // 次の問題に遷移させる
      // 'screen_cd'=>'QUIZ' は操作ログに前画面の情報を記録するため
      if ($exam_id != "" || $exam_id != null) {
        return redirect()->route('exam_answer_select', [$quiz_pack_id,$quize_id,$exam_id, 'screen_cd'=>'QUIZ', 'quiz_type' => $quiz_type])->with('old_choices',$old_choices);
      } else {
        return redirect()->route('quiz_answer_select', [$quiz_pack_id,$quize_id, 'screen_cd'=>'QUIZ', 'quiz_type' => $quiz_type])->with('old_choices',$old_choices);
      }
      
    } else {  
      // 最終問題の場合はクイズ結果一覧画面に遷移する
      if ($exam_id != "" || $exam_id != null) {
        return redirect()->route('exam_score', [$quiz_pack_id, $exam_id, 'quiz_type' => $quiz_type])->with('old_choices',$old_choices);
      } else {
        return redirect()->route('quiz_score', [$quiz_pack_id, 'quiz_type' => $quiz_type])->with('old_choices',$old_choices);
      }
    }
  }

  //Multiple quiz select observation
  public function quiz_observation_choice(Request $request, $quiz_pack_id, $quize_id)
  {
    $start_time = $request->has("start_time")? Carbon::parse($request->input("start_time")) : Carbon::now();
    $quiz_choice_id = $request->input("quiz_choice_id");
    $quiz_choice_title = $request->input("quiz_choice_title");
    $old_choices = $request->has("old_choices") ? $request->input("old_choices") : [];
    $old_observations = $request->has("old_observations") ? $request->input("old_observations") : [];
    $quiz_end = $request->input("quiz_end");
    $exam_id = $request->input("exam_id");
    $content_type = $request->input("content_type");
    $is_final_answer= false;
    // ユーザ操作ログ記録
    $quiz_pack = QuizPack::findOrFail($quiz_pack_id);
    $quiz =  Quiz::findOrFail($quize_id);
    $quiz_type = ($quiz->is_optional==1)?0:1;
    //old_observations
    $old_observations[$quize_id][$content_type] = ['lib_type'=>$content_type, 'quiz_id'=>$quize_id, 'quiz_choice_id'=>$quiz_choice_id, 'quiz_choice_title'=>$quiz_choice_title];
    
    $exclude_content_ids = array_map(function($q) { return $q['lib_type'];}, $old_observations[$quize_id]);

    $contents = $quiz->stetho_sounds()
    ->whereNotIn('lib_type',$exclude_content_ids)->get()->sortBy("lib_type")->groupBy('lib_type');
    $contents = $contents->first();

    if(!$contents){
      $is_final_answer=true;
    }
    //get all included libs for logs
    $included_libs = $quiz->stetho_sounds()->where('lib_type',$content_type)->get();
    $content_ids = $included_libs->pluck("id")->toArray();
    //get choices and correct answer
    $correct_answer= null;
    $correct = null;
    $QuizExam = new QuizExam();
    if ($quiz_choice_id >= 0) {
      $correct_answer = $QuizExam->getCorrectAnswer($quiz, $quiz_type, $content_type);
      $correct = $QuizExam->checkCorrectAnswer($quiz_type, $content_type, $correct_answer, $quiz_choice_title, $quiz_choice_id);
      $is_correct = $correct["is_correct"];
    }


    //save user logs
    $useLog = new UseLog;
    $stt_time = Session::get('MULTI_EXAM_QUIZ_NEXT_STT_TIME');
    $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');
    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
    $parent_id = Session::get('MEMBER_3SP_EXAM_QUIZ_ID');
    $question_log[]= array(
      "lib"=>$content_ids
    );
    $answer_log[]= array(
      "correct_id" => $correct_answer?$correct_answer->id:null,
      "correct_title_en" =>$correct_answer?$correct_answer->title_en:"",
      "correct_title_ja" =>$correct_answer?$correct_answer->title:"",
      "answered_id" => $quiz_choice_id,
      "answered" => $quiz_choice_title

    );
    $this->saveUseLogs($useLog,$exam_id?1:2,$parent_id,$exam_id,$quiz_pack_id,$quize_id,$userId,$universityId,$question_log,$answer_log,$this->content_type_string[$content_type],$is_correct,$stt_time);
    //save logs for library contents of quiz
    foreach ($content_ids as $lib_id) {
      $useLog = new UseLog;
      $this->saveUseLogs($useLog,$exam_id?1:2,$parent_id,$exam_id,$quiz_pack_id,$quize_id,$userId,$universityId,null,null,$this->content_type_string[$content_type],null,$stt_time,null,$lib_id);
    }
    return view('_partials.quiz_answer_select',compact('quiz','quiz_pack','exam_id','contents','is_final_answer', 'quiz_type','start_time'))->with('old_choices',$old_choices)->with('old_observations',$old_observations);
  }

  // クイズ成績画面
  public function quiz_score(Request $request, $quiz_pack_id, $exam_id = null)
  {
    $old_choices = \Session::get("old_choices",[]);
    $scores = [];
    $correct_count = 0;
    foreach ($old_choices as $key => $c) {
      $quiz = Quiz::findOrFail($c['quiz_id']);
      $quiz_type = ($quiz->is_optional==1)?0:1;
      // タイムオーバーで不正解になった場合はquiz_choice_idが-1になる
      $is_correct = $c['is_correct'] ;
      $scores[] = ['is_correct' => $is_correct,'number'=>$key,'quiz_id'=>$c['quiz_id'], 'quiz_choice_id' => $c['quiz_choice_id']];
      if ($is_correct) $correct_count++;
    }
    // ユーザ操作ログ記録
    $quiz_pack = QuizPack::find($quiz_pack_id);
    // App::setLocale($quiz_pack->lang == "EN" ? "en" : "ja");
    $this->writeUserTrackingLog($request, 'QUIZ', 'QUIZ_SCORE', 'INIT', $quiz_pack);
    return view('_partials.quiz_score',compact('scores','quiz_pack_id','correct_count','exam_id','quiz_type','old_choices'));
  }

  // クイズ回答確認画面
  public function quiz_answer_confirm(Request $request, $quiz_pack_id,$quiz_id) {
    $old_choices = $request->has("old_choices") ? $request->input("old_choices") : [];
    $quiz_type = $request->has("quiz_type") ? $request->input('quiz_type') : 0;
    $quiz_pack = QuizPack::findOrFail($quiz_pack_id);
    $quiz = Quiz::findOrFail($quiz_id);
    // ユーザ操作ログ記録
    $quiz_pack = QuizPack::findOrFail($quiz_pack_id);
    $this->writeUserTrackingLog($request, 'QUIZ_SCORE', 'QUIZ_ANSWER', 'INIT', $quiz_pack, $quiz);
    return view('_partials.quiz_answer_confirm',compact('quiz_pack','quiz','correct_count','quiz_type','old_choices',$quiz_pack_id));
  }

  /**
   * ユーザ操作ログを記録します。
   * @param $request        リクエストオブジェクト
   * @param $from_screen_cd 遷移元画面コード
   * @param $screen_cd      遷移先画面コード
   * @param $event_cd       イベントコード
   * @param $quiz_pack      選択中のクイズパック
   * @param $quiz           選択中のクイズ
   * @param $quiz_choice    選択したクイズ選択肢
   */
  private function writeUserTrackingLog($request, $from_screen_cd, $screen_cd, $event_cd, $quiz_pack, $quiz = null, $quiz_choice = null) {
    // 記録日時
    date_default_timezone_set('Asia/Tokyo');
    $date = "%datetime%";
    // 接続元IPアドレス
    $ip = $request->ip();
    // ユーザエージェント
    $user_agent = $request->header('User-Agent');
    // セッションID
    $session_id = \Session::getId();
    // ユーザID
    $user_id = $request->session()->get('MEMBER_3SP_USER');
    if ( empty($user_id) ) $user_id = "";
    // リファラ
    $referer = $request->headers->get('referer');
    if ( is_null($referer) ) $referer = '';
    // 遷移元画面コード(引数 $from_screen_cd)
    // 遷移先画面コード(引数 $screen_cd)
    // イベントコード(引数 $event_cd)
    // HTTPメソッド
    $http_method = $request->method();
    // HTTPパス
    $http_path = $request->path();
    // HTTPボディ
    $http_body = [];
    //// クイズパック
    $http_body['quiz_pack']          = [];
    $http_body['quiz_pack']['id']    = $quiz_pack->id;
    $http_body['quiz_pack']['title'] = $quiz_pack->title;
    //// クイズ
    if ( !is_null($quiz) ) {
      $http_body['quiz']             = [];
      $http_body['quiz']['id']       = $quiz->id;
      $http_body['quiz']['title']    = $quiz->title;
      $http_body['quiz']['question'] = $quiz->question;
    }
    //// クイズ選択肢
    if ( !is_null($quiz_choice) ) {
      $http_body['quiz_choice']               = [];
      $http_body['quiz_choice']['id']         = $quiz_choice->id;
      $http_body['quiz_choice']['title']      = $quiz_choice->title;
      $http_body['quiz_choice']['is_correct'] = $quiz_choice->is_correct;
    }
    // 出力する変数の配列
    $keys = ['date','ip','user_agent','session_id','user_id','referer','from_screen_cd','screen_cd','event_cd','http_method','http_path','http_body'];
    // ログ書込
    $logger = \App::make('tracking_logger');
    $logger->info('', compact($keys));
  }

  /**
   * Send Exam Csv
   * @param  int $exam_id      
   * @param  int $quiz_pack_id 
   * @return obj               
   */
  public function sendExamCsv($exam_id, $quiz_pack_id)
  {
    $exams = UseLog::where('exam_id', $exam_id)
    ->where('user_id',Session::get('MEMBER_3SP_ACCOUNT_ID'))
    ->with('exam','account','quiz')
    ->whereNotNull('anwser_log')
    //->where('quiz_type',"=","final")//only for final answers (no observation included)
    ->where('anwser_log','<>',"")
    ->get();

    $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
    $ExamGroup = DB::table('universities')->find($universityId)->name;
    $csv_file =[];
    foreach ($exams as $exam) {
      $answer = json_decode($exam->anwser_log);
      $question_log = json_decode($exam->question_log);
      $csv_row = [
        '試験' => $exam->exam->name,
        '大学' => $ExamGroup,
        'クイズパック' => $exam->exam->quiz_pack->title_en,
        'クイズ名' => $exam->quiz->title_en,
        'クイズの選択' => isset($answer->answered) ? $answer->answered : null,
        'ユーザー' => $exam->account->user,
        '正しい' => ($exam->is_correct == 1) ? '正しい' : '正しくない',
        '始まる時間' => $exam->stt_time,
        '終了時間' => $exam->end_time,
      ];
      if(isset($question_log->question_type)){
        array_push($csv_file, $csv_row);
      }

    }
    //download csv
    $file = \Excel::create('Exam_'.$exam_id.'_'.Carbon::now()->timestamp, function ($excel) use ($csv_file) {
        $excel->sheet('exam_result', function ($sheet) use ($csv_file) {
            $sheet->fromArray($csv_file);
        });
    })->store('xlsx');

    $email = $exams->first()->exam->result_destination_email;
    $path = "{$file->storagePath}/{$file->filename}.xlsx";

    // email to student
    $ExamGroup = DB::table('universities')->find($universityId)->name;
    $userName = Session::get('MEMBER_3SP_USER');
    $userEmail = DB::table('accounts')->find(Session::get('MEMBER_3SP_ACCOUNT_ID'))->email;
    $examGroupId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
    $examGroupName = DB::table('universities')->find($examGroupId)->name;
    $examName= DB::table('exam')->find($exam_id)->name;

    $success = Mail::send('emails.admin_exam', [
      'userName' => $userName, 
      'examGroupName' => $examGroupName,
      'examName'=>$examName
    ], function ($message) use ($email, $path){
      $message->from('kikuzosound@telemedica.co.jp', 'Kikuzosound.com');
      $message->from(env('MAIL_FROM_ADDRESS'), 'Kikuzosound.com');
      $message->to($email)->subject('Exam Results');
      // $message->attach($path);
    });

    $success = Mail::send('emails.user_exam', 
    [
      'userName' => $userName, 
      'examGroupName' => $examGroupName,
      'examName'=>$examName
    ], 
    function ($message) use ($userEmail){
      $message->from('kikuzosound@telemedica.co.jp', 'Kikuzosound.com');
      $message->from(env('MAIL_FROM_ADDRESS'), 'Kikuzosound.com');
      $message->to($userEmail)->subject('Exam Results');
    });

    unlink($path);
    return $success;
  }

  private function saveUseLogs($useLog,$type,$parent_id,$exam_id,$quiz_pack_id,$quiz_id,$userId,$universityId,$question_log="",$answer_log="",$content_type=null,$is_correct=null,$stt_time=null,$end_time=null,$lib_id=null){
    $useLog->type = $exam_id?1:2;
    $useLog->parent_id = $parent_id ? $parent_id : null;
    $useLog->exam_id = $exam_id ? $exam_id : null;
    $useLog->university_id = $universityId ? $universityId : 0;
    $useLog->user_id = $userId?$userId:0;
    $useLog->quiz_pack_id = $quiz_pack_id ? $quiz_pack_id: 0;
    $useLog->quiz_id = $quiz_id ? $quiz_id : null;
    $useLog->quiz_type = $content_type ? $content_type : null;
    $useLog->lib_id = $lib_id ? $lib_id : null;
    $useLog->is_correct = $is_correct;
    $useLog->stt_time = $stt_time?$stt_time:Carbon::now();
    $useLog->end_time = $end_time?$end_time:Carbon::now();
    $useLog->question_log = $question_log?json_encode($question_log[0]):"";
    $useLog->anwser_log = $answer_log?json_encode($answer_log[0]):"";
    $useLog->save();
  }

  public function endQuiz(){
    $useLog = UseLog::findOrFail(Session::get('MEMBER_3SP_EXAM_QUIZ_ID'));
    $useLog->end_time = Carbon::now();
    $useLog->save();
    Session::forget('MEMBER_3SP_EXAM_QUIZ_ID');
    Session::forget('MULTI_EXAM_QUIZ_NEXT_STT_TIME');
    return $useLog;
  }
}
