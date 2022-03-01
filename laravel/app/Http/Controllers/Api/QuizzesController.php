<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Quiz;
use App\QuizChoice;
use App\Traits\CommonTraits;

class QuizzesController extends Controller
{
	private $success = 'ng';
	private $message = '';
	private $result = null;
	private $total_page = 0;

	protected $university_id = null;
	protected $auth_user = null;

	use CommonTraits;

	public function __construct(Request $request)
	{
		$this->university_id = $request->input("university_id");
		$this->auth_user = $request->input("auth_user");
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
	public function index($page)
	{
		$params = request("params");
        $search_params = $params['search'];
        $resCount=0;
		if($this->auth_user->role == "101"){
			$all = Quiz::with(['exam_groups', 'stetho_sounds', 'quiz_choices', 'exam_author'])
				->latest()->get();
			if ($page == 'all') {
				$result = $all;
				$resCount=$all->count();
			} else {
				$skipRow = $page * 10;
	
				$question_query = Quiz::with(['exam_groups', 'stetho_sounds', 'quiz_choices', 'exam_author']);

				if(isset($search_params) && !empty($search_params)){
                    $question_query = $question_query->where('title_en', 'like', "%{$search_params}%")
								->orWhere('title', 'like', "%{$search_params}%")
                                ->orWhere('id', $search_params);                
                }
				$resCount = $question_query->count();
				$result = $question_query->skip($skipRow)
					->take(10)
					->latest()->get();
			}
		}
		$this_university_id = $this->university_id;
		if($this->auth_user->role == "201"){
			$all = Quiz::with(['exam_groups', 'stetho_sounds', 'quiz_choices', 'exam_author'])
				->where(function ($groups) use ($this_university_id) {
					$groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
						$query->where('exam_group_id', $this_university_id);
					})->orHas('exam_groups', '<', 1);
				})
				->latest()
				->get();
			if ($page == 'all') {
				$result = $all;
			} else {
				$skipRow = $page * 10;
	
				$question_query = Quiz::with(['exam_groups', 'stetho_sounds', 'quiz_choices', 'exam_author'])
					->where(function ($groups) use ($this_university_id) {
						$groups->whereHas('exam_groups', function ($query) use ($this_university_id) {
							$query->where('exam_group_id', $this_university_id);
						})->orHas('exam_groups', '<', 1);
					});
					if(isset($search_params) && !empty($search_params)){
						$question_query = $question_query->where(function ($questions) use ($search_params) {
							$questions->where('title_en', 'like', "%{$search_params}%")
							->orWhere('title', 'like', "%{$search_params}%")
							->orWhere('id', $search_params); 
						});      
					}
					$resCount = $question_query->count();
					$result = $question_query->skip($skipRow)
					->take(10)
					->latest()->get();
			}
		}
		
		if ($result) {
			$this->success = 'ok';
			$this->message = 'success';
			$this->result = $result;
			$this->total_page = ceil($resCount / 10);
		}

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
		$params = request("params");
		DB::beginTransaction();
		try {
			$Model = new Quiz();

			// $res = json_decode($params['ausculaide_fill_in']);
			// $this->message = "Quizzes Added Successfully";
			// $this->success = "ok";
			// $this->result = $res;
			// return $this->response();


			$this->saveQuizzes($Model, $params, $request);
			DB::commit();
			$this->result = [
				"quizzes" => $Model,
			];
			$this->message = "quizzes_success";
			$this->success = "ok";
		} catch (\Exception $e) {
			$this->message = "Failed to Add Quizzes: " . $e->getMessage();;
			DB::rollback();
		}

		return $this->response();
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
		$params = request("params");

		DB::beginTransaction();
		try {
			$Obj = Quiz::find((int)$id);
			$this->saveQuizzes($Obj, $params, $request);
			DB::commit();

			$this->result = [
				"quizzes" => $Obj,
			];
			$this->message = "edit_quizzes_success";
			$this->success = "ok";
		} catch (\Exception $e) {
			DB::rollback();
			$this->message = "Failed to update quizzes: " . $e->getMessage();
		}

		return $this->response();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		DB::beginTransaction();
		try {
			$quiz = Quiz::findOrFail($id);
			$filepath = public_path($quiz->image_path);
			if (\File::exists($filepath)) {
				\File::delete($filepath);
			}
			$quiz->quiz_packs()->detach();
			$quiz->stetho_sounds()->detach();
			$quiz->quiz_choices()->delete();
			$quiz->delete();

			$this->message = "delete_quizzes_success";
			$this->success = "ok";

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			$this->message = "Failed to Delete item: " . $e->getMessage();
		}

		return $this->response();
	}

	/**
	 * saveQuizzes
	 *
	 * @param  mixed $quiz
	 * @param  mixed $params
	 * @param  mixed $request
	 * @return void
	 */
	public function saveQuizzes($quiz, $params, $request)
	{
		$lib_items = [
			"ausculaide_fill_in",
			"stetho_fill_in",
			"palpation_fill_in",
			"ecg_fill_in",
			"inspection_fill_in",
			"xray_fill_in",
			"ucg_fill_in",
		];
		DB::beginTransaction();
		try {
			$quiz->title = $params['title'];
			$quiz->title_en = $params['title_en'];
			$quiz->question = $params['question'];
			$quiz->question_en = $params['question_en'];
			$quiz->case_age = $params['case_age'];
			$quiz->case_gender = $params['case_gender'];
			$quiz->case = $params['case'];
			$quiz->case_en = $params['case_en'];
			$quiz->question_en = $params['question_en'];
			$quiz->limit_seconds = $params['limit_seconds'];
			$quiz->is_optional = $params['is_optional'];
			$quiz->user_id = $params["user_id"];
			$quiz->disp_order = Quiz::max('disp_order') + 1;


			//library item description
			$quiz->description_stetho_sound_id = empty($params['ausculaide_description']) ? null : $params['ausculaide_description'];
			$quiz->description_stethoscope_id = empty($params['stetho_description']) ? null : $params['stetho_description'];
			$quiz->description_palpation_id = empty($params['palpation_description']) ? null : $params['palpation_description'];
			$quiz->description_ecg_id = empty($params['ecg_description']) ? null : $params['ecg_description'];
			$quiz->description_inspection_id = empty($params['inspection_description']) ? null : $params['inspection_description'];
			$quiz->description_xray_id = empty($params['xray_description']) ? null : $params['xray_description'];
			$quiz->description_echo_id = empty($params['ucg_description']) ? null : $params['ucg_description'];

			$quiz->university_id = $this->university_id;
			$quiz->save();
			$quiz->exam_groups()->attach($quiz->id, ['exam_group_id' => $this->university_id]);

			// $quiz->exam_groups()->detach();
			if ($params['group_attr'] == 0) {
				foreach (json_decode($params["exam_group"]) as $value) {
					$quiz->exam_groups()->attach($quiz->id, ['exam_group_id' => $value]);
				}
			}
			$quiz->save();


			$quiz->quiz_choices()->delete();
			//library item choices

			if ($params['is_optional'] == 0) {
				foreach ($lib_items as $item) {
					if (isset($params[$item])) {
						$this->saveFillIn($item, $quiz->id, $params);
					}
				}
				//final fill in
				if (isset($params['final_fill_in'])) {
					$this->saveFillInFinal($quiz->id, $params);
				}
			} else {
				foreach (json_decode($params['ausculaide_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}

				foreach (json_decode($params['stetho_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}
				foreach (json_decode($params['palpation_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}
				foreach (json_decode($params['ecg_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}
				foreach (json_decode($params['inspection_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}
				foreach (json_decode($params['xray_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}
				foreach (json_decode($params['ucg_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}

				// final choices
				foreach (json_decode($params['final_answer_quiz_choices']) as $k => $v) {
					$this->saveChoices($quiz->id, $k, $v, $params);
				}
			}

			//library items
			$quiz->stetho_sounds()->detach();
			foreach (json_decode($params['ausculaide']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}
			foreach (json_decode($params['stetho']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}
			foreach (json_decode($params['palpation']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}
			foreach (json_decode($params['ecg']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}
			foreach (json_decode($params['inspection']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}
			foreach (json_decode($params['xray']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}
			foreach (json_decode($params['ucg']) as $i => $v) {
				$quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v->id, 'description' => $v->title, 'description_en' => $v->title_en]);
			}


			$this->saveImagePath($request, $quiz, 'quizzes_images');
		} catch (Exception $e) {
			DB::rollback();
		}
		DB::commit();
	}
}
