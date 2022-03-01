<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Intervention\Image\Facades\Image;
use Carbon;
use App\Exams;
use App\QuizPack;
use App\Quiz;

class ExamManangementController extends Controller
{

    private $success = 'ng';
    private $message = '';
    private $result = null;
    private $total_page = 0;

	protected $auth_user = null;
    protected $university_id = null;
    private $count;


    public function __construct(Request $request)
    {
		$this->auth_user = $request->input("auth_user");
        $this->university_id = $request->input("university_id");
        $this->count = QuizPack::where('deleted_at', null)->count();
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
     * @return response
     */
    public function index(Request $request, $page)
    {
        $params = request("params");
        $search_params = $params['search'];
        $resCount=0;

		if($this->auth_user->role == "101"){
            $all = Exams::with([
                'quiz_pack.quizzes',
                'quiz_pack.quizzes.quiz_choices'
            ])
            ->latest()->get();
            if ($page == 'all') {
                $result = $all;
                $resCount=$all->count();
            } else {
                $skipRow = $page * 10;

                $exam_query = Exams::with([
                    'quiz_pack.quizzes',
                    'quiz_pack.quizzes.quiz_choices'
                ]);

                if(isset($search_params) && !empty($search_params)){
                    $exam_query = $exam_query->where('name', 'like', "%{$search_params}%")
                                ->orWhere('id', $search_params);                
                }
                $resCount=$exam_query->count();
                $result = $exam_query->skip($skipRow)->take(10)->latest()->get();
            }
        }
		$this_university_id = $this->university_id;
        if($this->auth_user->role == "201"){
            $all = Exams::with([
                'quiz_pack.quizzes',
                'quiz_pack.quizzes.quiz_choices'
            ])
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
                $exam_query = Exams::with([
                    'quiz_pack.quizzes',
                    'quiz_pack.quizzes.quiz_choices'
                ])
                ->where(function ($groups) use ($this_university_id) {
                    $groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
                        $query->where('exam_group_id', $this_university_id);
                    })->orHas('exam_groups', '<', 1);
                });

                if(isset($search_params) && !empty($search_params)){
                    $exam_query = $exam_query->where(function ($exams) use ($search_params) {
                        $exams->where('name', 'like', "%{$search_params}%")
                        ->orWhere('id', $search_params); 
                    });      
                }
                $resCount=$exam_query->count();
                $result = $exam_query->skip($skipRow)->take(10)->latest()->get();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return response
     */
    public function create()
    {
        //
        $quizzes = Quiz::with('quiz_choices')->latest()->get();

        if ($quizzes) {
            $this->result = $quizzes;
            $this->message = "success";
            $this->success = "ok";
        }

        return $this->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return response
     */
    public function store(Request $request)
    {
        $params = request("params");
        //db
        DB::beginTransaction();
        try {
            $quizzes = json_decode($params["quizzes"]); // list of quizzes id

            $is_exam = $params["type_exam"] == "true" ? true : false;
            $is_quizzes = $params["type_quizzes"] == "true" ? true : false;
            $type = 0;
            if ($is_exam && $is_quizzes) {
                //both
                $type = 1;
            } else if ($is_exam && !$is_quizzes) {
                // type is exam
                $type = 2;
            } else if (!$is_exam && $is_quizzes) {
                // type is quizzes
                $type = 3;
            }

            if ($type === 0) {
                $this->message = "failed to create exam, Type field is required!";
                $this->success = "ng";
                $this->response();
            };

            $quiz_pack_id = DB::table('quiz_packs')->insertGetId([
                'title' => $params["title"],
                'title_en' => $params["title_en"],
                'title_color' => $params["color"] ?? '#eeeeee',
                'quiz_order_type' => $params["quiz_order_type"],
                'description' => $params["description"],
                'description_en' => $params["description_en"],
                'icon_path' => "",
                'user_id' => $params["user_id"],
                'max_quiz_count' => count($quizzes),
                'lang' => $params["lang"] ?? "en",
                'is_public' => ($type === 3 || $type === 1) ? 1 : 0,
                'university_id' => $this->university_id,
                'disp_order' => $this->count + 1,
                'created_at' => Carbon\Carbon::now()->toDateTimeString('Y-m-d H:i:s'),
                'updated_at' => Carbon\Carbon::now()->toDateTimeString('Y-m-d H:i:s'),
            ]);
            $quizpack = QuizPack::find($quiz_pack_id);
            $quizpack->exam_groups()->attach($quiz_pack_id, ['exam_group_id' => $this->university_id]);

            // $quiz_pack = new QuizPack();
            // $quiz_pack->title = $params["title"];
            // $quiz_pack->title_en = $params["title_en"];
            // $quiz_pack->title_color = $params["color"] ?? '#eeeeee';
            // $quiz_pack->quiz_order_type = $params["quiz_order_type"];
            // $quiz_pack->description = $params["description"];
            // $quiz_pack->description_en = $params["description_en"];
            // $quiz_pack->icon_path = "";
            // $quiz_pack->max_quiz_count = count($quizzes);
            // $quiz_pack->lang = $params["lang"] ?? "en";
            // $quiz_pack->is_public = ($type === 3 || $type === 1 ) ? 1 : 0;
            // $quiz_pack->university_id = $this->university_id;
            // $quiz_pack->increment('disp_order');
            // $quiz_pack->save();

            if ($request->hasFile('bg_img') || $request->has('tmp_quiz_pack_image_path')) {

                $image_path = $this->moveFile($request, '/img/quiz_packs/', $quiz_pack_id, 'bg_img', 'tmp_quiz_pack_image_path');

                if (is_null($image_path)) {
                    DB::rollback();
                    Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                    $this->message = 'ファイルの移動に失敗しました: 元ファイルのパス: ';
                    return $this->response();
                }
                //save path
                DB::table('quiz_packs')->where('id', $quiz_pack_id)
                    ->update(
                        ['icon_path' => $image_path]
                    );


                //resize large image
                list($w, $t) = getimagesize(public_path($image_path));
                if ($t > 512) {
                    // resize
                    $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // overwrite
                    $img->save(public_path($image_path));
                }
                // remove the temp file and session as well
                $this->removeTmpFile($request->input('tmp_quiz_pack_image_path'));
                \Session::forget('tmp_quiz_pack_image_path');
            }

            //quizzes
            foreach ($quizzes as $key => $value) {
                DB::table('quiz_quiz_pack')->insert([
                    'quiz_pack_id' => $quiz_pack_id,
                    'disp_order' => $key,
                    'quiz_id' => $value
                ]);
            }

            $exam = null;
            $latest_disp_order = Exams::max("disp_order");
            if ($type === 2 || $type === 1) {
                $exam =  new Exams;
                $exam->name = $params["title_en"];
                $exam->user_id = $params["user_id"];
                $exam->name_jp = $params["title"];
                $exam->university_id = $this->university_id;
                $exam->quiz_pack_id = $quiz_pack_id;
                $exam->result_destination_email = $params["destination_email"] ?? "";
                $exam->is_publish = $params["enabled"];
                $exam->disp_order = $latest_disp_order + 1;
                $exam->save();
                $exam->exam_groups()->attach($exam->id, ['exam_group_id' => $this->university_id]);
                $exam->save();
            }

            //db commit
            DB::commit();

            $this->result = [
                "exam" => $exam,
                "quiz_pack" => $quiz_pack_id
            ];
            $this->message = "exam_success";
            $this->success = "ok";
        } catch (\Exception $e) {
            //db roll back
            $this->message = $e->getMessage();
            DB::rollback();
        }

        return $this->response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return response
     */
    public function show($id)
    {
        $result = Exams::where("id", $id)->with([
            'quiz_pack.quizzes',
            'quiz_pack.quizzes.quiz_choices'
        ])->latest()->get()->first();

        if ($result) {
            $this->result = $result;
            $this->message = "success";
            $this->success = "ok";
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
        $exam = Exams::where("id", $id)->with([
            'quiz_pack.quizzes',
            'quiz_pack.quizzes.quiz_choices'
        ])->latest()->get()->first();

        if ($exam) {
            $this->result = $exam;
            $this->message = "success";
            $this->success = "ok";
        }

        return $this->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return response
     */
    public function update(Request $request, $exam_id)
    {
        $params = request("params");
        $quiz_pack_id = Exams::where("id", $exam_id)->get()->pluck("quiz_pack_id")->first();
        //db
        DB::beginTransaction();
        try {
            $is_exam = $params["type_exam"] == "true" ? true : false;
            $is_quizzes = $params["type_quizzes"] == "true" ? true : false;
            $type = 0;

            if ($is_exam && $is_quizzes) {
                //both
                $type = 1;
            } else if ($is_exam && !$is_quizzes) {
                // type is exam
                $type = 2;
            } else if (!$is_exam && $is_quizzes) {
                // type is quizzes
                $type = 3;
            }
            $quizzes = json_decode($params["quizzes"]); // list of quizzes id

            $quiz_pack = QuizPack::find(intval($quiz_pack_id));
            $quiz_pack->title = $params["title"];
            $quiz_pack->title_en = $params["title_en"];
            $quiz_pack->title_color = $params["color"] ?? '#eeeeee';
            $quiz_pack->quiz_order_type = $params["quiz_order_type"];
            $quiz_pack->description = $params["description"];
            $quiz_pack->description_en = $params["description_en"];
            $quiz_pack->user_id = $params["user_id"];
            if ($request->hasFile('bg_img')) {
                $quiz_pack->icon_path = "";
            }
            $quiz_pack->max_quiz_count = count($quizzes);
            $quiz_pack->lang = $params["lang"] ?? "en";
            $quiz_pack->is_public = ($type === 3 || $type === 1) ? 1 : 0;
            $quiz_pack->university_id = $this->university_id;
            $quiz_pack->increment('disp_order');
            $quiz_pack->exam_groups()->attach($quiz_pack->id, ['exam_group_id' => $this->university_id]);
            $quiz_pack->save();

            if ($request->hasFile('bg_img') || $request->has('tmp_quiz_pack_image_path')) {

                $image_path = $this->moveFile($request, '/img/quiz_packs/', $quiz_pack->id, 'bg_img', 'tmp_quiz_pack_image_path');

                if (is_null($image_path)) {
                    DB::rollback();
                    Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
                    $this->message = 'ファイルの移動に失敗しました: 元ファイルのパス: ';
                    return $this->response();
                }
                //save path
                $quiz_pack->icon_path = $image_path;
                $quiz_pack->save();

                //resize large image
                list($w, $t) = getimagesize(public_path($image_path));
                if ($t > 512) {
                    // resize
                    $img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // overwrite
                    $img->save(public_path($image_path));
                }
                // remove the temp file and session as well
                $this->removeTmpFile($request->input('tmp_quiz_pack_image_path'));
                \Session::forget('tmp_quiz_pack_image_path');
            }


            $quiz_pack->quizzes()->detach();
            foreach ($quizzes as $key => $value) {
                $quiz_pack->quizzes()->attach($quiz_pack->id, ['disp_order' => $key, 'quiz_id' => $value]);
            }
            $exam = null;
            if ($type === 2 || $type === 1) {
                $id = Exams::where("quiz_pack_id", $quiz_pack_id)->get()->pluck("id")->first();
                //updateOrCreate for higher version
                if ($id) {
                    $exam = Exams::find($id);
                } else {
                    $id = Exams::where("quiz_pack_id", $quiz_pack_id)->withTrashed()->get()->pluck("id")->first();
                    $exam = ($id) ? Exams::withTrashed()->find($id) : new Exams;
                    $exam->deleted_at = null;
                }
                $exam->name = $params["title_en"];
                $exam->name_jp = $params["title"];
                $exam->user_id = $params["user_id"];
                $exam->university_id = $this->university_id;
                $exam->quiz_pack_id = $quiz_pack->id;
                $exam->result_destination_email = $params["destination_email"] ?? "";
                $exam->is_publish = $params["enabled"];
                $exam->exam_groups()->attach($exam->id, ['exam_group_id' => $this->university_id]);
                $exam->save();
            }

            if ($type === 3) {
                $id = Exams::where("quiz_pack_id", $quiz_pack_id)->get()->pluck("id")->first();
                if ($id)  Exams::find($id)->delete();
            }

            //db commit
            DB::commit();

            $this->result = [
                "exam" => $exam,
                "quiz_pack" => $quiz_pack
            ];
            $this->message = "edit_exam_success";
            $this->success = "ok";
        } catch (\Exception $e) {
            //db roll back
            $this->message = $e->getMessage();
            DB::rollback();
        }

        return $this->response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return response
     */
    public function destroy($exam_id)
    {
        DB::beginTransaction();
        try {
            if ($exam_id) Exams::find((int)$exam_id)->delete();

            $this->message = "delete_exam_success";
            $this->success = "ok";

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $this->message = "Failed to Delete Exam";
        }

        return $this->response();
    }
}
