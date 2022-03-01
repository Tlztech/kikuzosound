<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Quiz;
use App\QuizPack;
use App\QuizQuizPack;
use App\ExamResults;
use DB;
use Log;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class QuizPacksController extends Controller
{

    private $success = 'ng';
    private $message = '';
    private $result = null;
    private $total_page = 0;

    protected $university_id = null;
	protected $auth_user = null;

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




    public function getQuizPacks(Request $request, $page)
    {
        $params = request("params");
        $search_params = $params['search'];
        $resCount=0;
		$this_university_id = $this->university_id;
		if($this->auth_user->role == "201"){
            $all = QuizPack::with(['exam_groups', 'quizzes'])
            ->where(function ($groups) use ($this_university_id) {
                $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->orHas('exam_groups', '<', 1);
            })
            ->latest()->get();

            if ($page == 'all') {
                $result = $all;
                $resCount=$all->count();
            } else {
                $skipRow = $page * 10;
                $quiz_query = QuizPack::with(['exam_groups', 'quizzes'])
                ->where(function ($groups) use ($this_university_id) {
                    $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                        $query->where('exam_group_id', $this_university_id);
                    })->orHas('exam_groups', '<', 1);
                });

                if(isset($search_params) && !empty($search_params)){
                    $quiz_query = $quiz_query->where(function ($quiz_packs) use ($search_params) {
                        $quiz_packs->where('title', 'like', "%{$search_params}%")
                        ->orWhere('title_en', 'like', "%{$search_params}%")
                        ->orWhere('title_en', 'like', "%{$search_params}%")
                        ->orWhere('description', 'like', "%{$search_params}%")
                        ->orWhere('description_en', 'like', "%{$search_params}%")
                        ->orWhere('id', $search_params);  
                    });
                }
                $resCount=$quiz_query->count();
                $result = $quiz_query->skip($skipRow)->take(10)->latest()->get();
            }
        }
        else if($this->auth_user->role == "101") {
            $all = QuizPack::with(['exam_groups', 'quizzes'])
            ->latest()->get();

            if ($page == 'all') {
                $result = $all;
                $resCount=$all->count();
            } else {
                $skipRow = $page * 10;

                $quiz_query = QuizPack::with(['exam_groups', 'quizzes']);
                if(isset($search_params) && !empty($search_params)){
                    $quiz_query = $quiz_query->where('title', 'like', "%{$search_params}%")
                    ->orWhere('title_en', 'like', "%{$search_params}%")
                    ->orWhere('title_en', 'like', "%{$search_params}%")
                    ->orWhere('description', 'like', "%{$search_params}%")
                    ->orWhere('description_en', 'like', "%{$search_params}%")
                    ->orWhere('id', $search_params);  
                }
                $resCount=$quiz_query->count();
                $result = $quiz_query->skip($skipRow)->take(10)->latest()->get();
            }
        }



        if ($result) {
            $this->result = $result;
            $this->message = "success";
            $this->success = "ok";
            $this->total_page = ceil($resCount / 10);
        }

        return $this->response();
    }

    public function index()
    {
		$this_university_id = $this->university_id;
		if($this->auth_user->role == "201"){
		    $result = QuizPack::orderBy('id', 'DESC')
                ->whereHas('exam_groups', function ($query) use ($this_university_id) {
                    $query->where('exam_group_id', $this_university_id);
                })->get();
        }
        else {
		    $result = QuizPack::orderBy('id', 'DESC')->get();
        }
        if ($result) {
            $this->result = $result;
            $this->message = "success";
            $this->success = "ok";
        }

        return $this->response();
    }

    public function create(Request $request)
    {
        $params = request("params");
        //db
        DB::beginTransaction();
        try {
            // $quizzes = json_decode($params["quizzes"]); // list of quizzes id

            $quiz_pack = new QuizPack();
            $this->saveData($quiz_pack, $params, $request);
            //db commit
            DB::commit();

            $this->result = [
                $quiz_pack
            ];
            $this->message = "success_add_quiz_pack";
            $this->success = "ok";
        } catch (\Exception $e) {
            //db roll back
            $this->message = "failed_add_quiz_pack";
            DB::rollback();
        }

        return $this->response();
    }

    public function update(Request $request, $id)
    {
        $params = request("params");
        //db
        DB::beginTransaction();
        try {
            // $quizzes = json_decode($params["quizzes"]); // list of quizzes id

            $quiz_pack = QuizPack::find((int)$id);

            $this->saveData($quiz_pack, $params, $request);
            //db commit
            DB::commit();

            $this->result = [
                $quiz_pack
            ];
            $this->message = "success_update_quiz_pack";
            $this->success = "ok";
        } catch (\Exception $e) {
            //db roll back
            $this->message = "failed_update_quiz_pack";
            DB::rollback();
        }

        return $this->response();
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $has_exams = DB::table('exam')->whereNull('deleted_at')->where('quiz_pack_id', $id)->get();
            $quiz_pack = QuizPack::findOrFail((int)$id);
            $filepath = public_path($quiz_pack->icon_path);
            if (count($has_exams) > 0) {
                $this->message = "content_used_on_exam";
                $this->success = "failed";
                // "試験はコンテンツを使用したため、削除できませんでした。"
            } else {
                if (\File::exists($filepath)) {
                    \File::delete($filepath);
                }
                $quiz_quiz_pack = QuizQuizPack::where('quiz_pack_id', $quiz_pack->id);
                $quiz_quiz_pack->delete();
                $quiz_pack->delete();

                $this->message = "success_delete_quiz_pack";
                $this->success = "ok";
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->message = "failed_delete_quiz_pack";
        }

        return $this->response();
    }

    public function saveData($quiz_pack, $params, $request)
    {
        $quiz_pack->title = $params["title"];
        $quiz_pack->title_en = $params["title_en"];
        $quiz_pack->title_color = $params["color"] ?? '#eeeeee';
        $quiz_pack->quiz_order_type = $params["quiz_order_type"];
        $quiz_pack->description = $params["description"];
        $quiz_pack->description_en = $params["description_en"];
        $quiz_pack->max_quiz_count = count(json_decode($params["quizzes"]));
        $quiz_pack->lang = $params["lang"] ?? "en";
        $quiz_pack->is_public = $params["is_public"];
        $quiz_pack->user_id = $this->auth_user->id;
        // $quiz_pack->university_id = $this->university_id;
        if ($quiz_pack->disp_order) {
            $quiz_pack->increment('disp_order');
        }
        $quiz_pack->save();
        $quiz_pack->quizzes()->detach();
        foreach (json_decode($params["quizzes"]) as $key => $value) {
            $quiz_pack->quizzes()->attach($quiz_pack->id, ['disp_order' => $key, 'quiz_id' => $value]);
        }
        $quiz_pack->exam_groups()->detach();
        if ($params['group_attr'] == 0) {
            foreach (json_decode($params["exam_group"]) as $value) {
                $quiz_pack->exam_groups()->attach($quiz_pack->id, ['exam_group_id' => $value]);
            }
        }
        $quiz_pack->save();


        //image
        if ($request->hasFile('bg_img') || $request->has('icon_path')) {
            $image_path = $this->moveFile($request, '/img/quiz_packs/', $quiz_pack->id, 'bg_img', 'icon_path');
            if (is_null($image_path)) {
                DB::rollback();
                Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                abort(500, 'ファイルの移動に失敗しました');
            }
            $quiz_pack->icon_path = $image_path;
            $quiz_pack->save();

            //resizing
            list($w, $t) = getimagesize(public_path($image_path));
            if ($t > 512) {
                // リサイズ
                $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // 上書き保存
                $img->save(public_path($image_path));
            }
            // セッションにパスがある場合、ファイルとSessionも消す
            $this->removeTmpFile($request->input('tmp_quiz_pack_image_path'));
            \Session::forget('tmp_quiz_pack_image_path');
        }
    }

    public function quiz_start($quiz_pack_id, $exam_id = null)
    {
        $params = request("params");
        $res_quizzes = [];
        try {
            $quiz_pack = QuizPack::withTrashed()->where('id', $quiz_pack_id)->with('quizzes')->orderBy('disp_order', 'asc')->get();
            $quizzes = $quiz_pack->pluck('quizzes');
            foreach ($quizzes as $key => $value) {
                foreach ($value as $v) {
                    $quiz = Quiz::where('id', $v->pivot->quiz_id)->with(
                        [
                            'stetho_sounds' => function ($q) {
                                $q->orderBy('quiz_stetho_sound.disp_order', 'asc');
                            },
                            'quiz_choices',
                        ]
                    )->get();
                    $quiz[0]["pivot"] = $v->pivot;
                    $quiz[0]["quiz_type"] = $this->getQuizType($quiz);
                    $quiz[0]["mode"] = $this->getQuizMode($quiz);
                    array_push($res_quizzes, $quiz);
                }
            }

            if ($exam_id) {
                $exam = DB::table('exam')->where("id", $exam_id)->first();
                $title = $params['lang'] == "en" ? $exam->name : $exam->name_jp;
            } else {
                $title =  $params['lang'] == "en" ? $quiz_pack[0]->title_en : $quiz_pack[0]->title;
            }

            $this->message = "Quiz of quizpack {$quiz_pack_id} received Successfully";
            $this->success = "ok";
            $this->result = [
                'quiz_pack_id' => $quiz_pack_id,
                'quizzes' => $res_quizzes,
                'title' => $title,
                'exam_id' => $exam_id,
                'quiz_count' => $quiz_pack[0]->max_quiz_count
            ];
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->success = "failed";
            $this->result = $e;
        }

        return $this->response();
    }


    public function getQuizType($quiz)
    {
        if ($quiz[0]->is_optional == 0) {
            return "text_box";
        } else {
            return "multiple_choice";
        }
    }

    public function getQuizMode($quiz)
    {
        $lib_types= [];
        if (count($quiz[0]->stetho_sounds) <= 1) {
            return "simple";
        } else {
            foreach($quiz[0]->stetho_sounds as $sound){
                array_push($lib_types,$sound->lib_type);
            }
            return count(array_unique($lib_types)) === 1 ? "simple" : "multiple";
        }
    }

    public function getQuizData($quiz_id)
    {
        try {
            $quiz = Quiz::where('id', $quiz_id)->with('stetho_sounds', 'quiz_choices')->get();
            $this->message = "Quiz of {$quiz_id} received Successfully";
            $this->success = "ok";
            $this->result = [
                'quiz' => $quiz,
                'quiz_type' => $this->getQuizType($quiz),
                'mode' => $this->getQuizMode($quiz)
            ];
        } catch (\Exception $e) {
            $this->message = $e;
            $this->success = "failed";
            $this->result = $e;
        }
        return $this->response();
    }

    public function saveExamResult(Request $request, $exam_id, $userId)
    {
        $data =  $request->data;
        foreach ($data as $item) {
            $examResults = new ExamResults;
            $examResults->exam_id = $exam_id;
            $examResults->customer_id = $userId;
            $examResults->quiz_id = $item['quiz_id'];
            $examResults->is_correct = $item['is_correct'];
            $examResults->start_time =  $item['start_time'];
            $examResults->finish_time = $item['finish_time'];
            $examResults->save();
        }
    }

    public function sendExamCsv(Request $request)
    {
        $exam_id = $_GET['exam_id'];
        $quiz_pack_id = $_GET['quiz_pack_id'];
        $universityId = $this->university_id;

        $exams = ExamResults::where('exam_id', $exam_id)
            ->with('exam', 'account', 'quiz_choice')
            ->get();

        $ExamGroup = DB::table('universities')->find($universityId)->name;

        $csv_file = [];
        foreach ($exams as $exam) {
            $csv_row = [
                '試験' => $exam->exam->name,
                '大学' => $ExamGroup,
                'クイズパック' => $exam->exam->quiz_pack->title_en,
                'クイズ名' => $exam->quiz_choice->quiz->title_en,
                'クイズの選択' => $exam->quiz_choice->title_en,
                'ユーザー' => $exam->account->user,
                '正しい' => ($exam->is_correct == 1) ? '正しい' : '正しくない',
                '始まる時間' => $exam->start_time,
                '終了時間' => $exam->finish_time,
            ];
            array_push($csv_file, $csv_row);
        }
        //download csv
        $file = \Excel::create('Exam_' . $exam_id . '_' . Carbon::now()->timestamp, function ($excel) use ($csv_file) {
            $excel->sheet('exam_result', function ($sheet) use ($csv_file) {
                $sheet->fromArray($csv_file);
            });
        })->store('csv');

        $email = $exams->first()->exam->result_destination_email;
        $path = "{$file->storagePath}/{$file->filename}.csv";

        // email to student
        $userName = "MEMBER_3SP_USER";
        $userEmail = "gamenic.madan.pd@gmail.com";
        $examGroupName = DB::table('universities')->find($universityId)->name;
        $examName = DB::table('exam')->find($exam_id)->name;

        $success = Mail::send(
            'emails.user_exam',
            [
                'userName' => $userName,
                'examGroupName' => $examGroupName,
                'examName' => $examName
            ],
            function ($message) use ($userEmail, $path) {
                $message->from('kikuzosound@3sp.co.jp', 'dev.3sp.sys4tr.com');
                $message->to($userEmail)->subject('Exam Results');
                // $message->attach($path);
            }
        );
        unlink($path);
        return $success;
    }
}
