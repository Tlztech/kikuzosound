<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Quiz;
use App\QuizChoice;
use App\ExamGroup;
use App\StethoSound;
use App\Http\Requests\QuizRequest;
use Intervention\Image\Facades\Image;
use DB;
use Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $count = Quiz::count(); 
        $quizzes = Quiz::orderBy('disp_order', 'desc')->orderBy('id', 'desc')->paginate(isset($_GET['reorder']) ? $count : 10);
        return view('admin.quizzes.index', compact('quizzes', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $exam_groups=ExamGroup::orderBy("id", "desc")->get();
        // 公開設定になっている聴診音を全て取得する
        $stetho_sounds = StethoSound::whereNull("deleted_at")
            ->where("status", "!=", 0)
            ->orderBy('disp_order', 'desc')
            ->get();
        return view('admin.quizzes.create', compact('stetho_sounds','exam_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(QuizRequest $request)
    {
        DB::beginTransaction();
        try {
            $quiz = new Quiz();
            $quiz->title = $request->input('title');
            $quiz->title_en = $request->input('title_en');
            $quiz->question = $request->input('question');
            $quiz->question_en = $request->input('question_en');
            $quiz->image_path = '';
            $quiz->case_age = $request->input('case_age');
            $quiz->case_gender = $request->input('case_gender');
            $quiz->case = $request->input('case');
            $quiz->case_en = $request->input('case_en');
            $quiz->question_en = $request->input('question_en');
            $quiz->limit_seconds = $request->input('limit_seconds');
            $d = $request->input('auscultation_description');
            $quiz->description_stetho_sound_id = empty($d) ? null : $d;
            $quiz->description_stethoscope_id = empty($request->input('stethoscope_description')) ? null : $request->input('stethoscope_description');
            $quiz->description_palpation_id = empty($request->input('palpation_description')) ? null : $request->input('palpation_description');
            $quiz->description_ecg_id = empty($request->input('ecg_description')) ? null : $request->input('ecg_description');
            $quiz->description_inspection_id = empty($request->input('examination_description')) ? null : $request->input('examination_description');
            $quiz->description_xray_id = empty($request->input('xray_description')) ? null : $request->input('xray_description');
            $quiz->description_echo_id = empty($request->input('echo_description')) ? null : $request->input('echo_description');
            $quiz->disp_order = Quiz::max('disp_order') +1;
            $quiz->is_optional = $request->input('is_optional');
            $result = $quiz->save();

            //save university id's in pivot table
            $quiz->exam_groups()->detach();
            if($request->input('group_attr')==0){
                foreach ($request->input("exam_group",array()) as $key => $value) {
                    $quiz->exam_groups()->attach($quiz->id,['exam_group_id'=>$value]);
                }
            }

            // 画像ファイルを保存する
            if ($request->hasFile('image') || $request->has('image_path')) {
                $image_path = $this->moveFile($request, '/img/quizzes/', $quiz->id, 'image', 'image_path');
                if (is_null($image_path)) {
                    DB::rollback();
                    Log::error('ファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$image_path);
                    abort(500, 'ファイルの移動に失敗しました');
                }
                $quiz->image_path = $image_path;
                $quiz->save();
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
                $this->removeTmpFile($request->input('image_path'));
                \Session::forget('quiz_image_path');
            }
            //stethoscope choices
            if (!empty($request->input('stethoscope', []))){
                foreach ($request->input('stethoscope_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"stethoscope_quiz_choices_correct_index");
                }
            }
            //auscultation choices
            if (!empty($request->input('auscultation', []))){
                foreach ($request->input('auscultation_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"auscultation_quiz_choices_correct_index");
                }
            }
            //palpation choices
            if (!empty($request->input('palpation', []))){
                foreach ($request->input('palpation_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"palpation_quiz_choices_correct_index");
                }
            }
            //ecg choices
            if (!empty($request->input('ecg', []))){
                foreach ($request->input('ecg_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"ecg_quiz_choices_correct_index");
                }
            }
            //examination choices
            if (!empty($request->input('examination', []))){
                foreach ($request->input('examination_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"examination_quiz_choices_correct_index");
                }
            }
            //xray choices
            if (!empty($request->input('xray', []))){
                foreach ($request->input('xray_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"xray_quiz_choices_correct_index");
                }
            }
            //echo choices
            if (!empty($request->input('echo', []))){
                foreach ($request->input('echo_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"echo_quiz_choices_correct_index");
                }
            }
            //final choices
            foreach ($request->input('final_answer_quiz_choices', []) as $i => $v) {
                $this->saveChoices($quiz->id,$i,$v,$request,"final_answer_quiz_choices_correct_index");
            }

            //fill-in final choices
            if(!$request->input('is_optional')){
                foreach ($request->input('fill_final_answer_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,"fill_in",$v['fill_in'],$request,"");
                }
            }

            //stethoscope library contents
            foreach ($request->input('stethoscope', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //auscultation library contents
            foreach ($request->input('auscultation', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //palpation library contents
            foreach ($request->input('palpation', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //ecg library contents
            foreach ($request->input('ecg', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //examination library contents
            foreach ($request->input('examination', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //xray library contents
            foreach ($request->input('xray', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //echo library contents
            foreach ($request->input('echo', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
        } catch (Exception $e) {
            DB::rollback();
            // TODO: エラー処理
            abort(500, '');
        }
        DB::commit();

        return redirect()->route('admin.quizzes.index')->with('message', 'Item created successfully.');
    }
    /**
     * save choices for each library
     *
     * @param int $quiz_id,$i,$v,$request
     *
     */
    public function saveChoices($quiz_id,$i,$v,$request,$answer_key)
    {
        $quiz_choice = new QuizChoice();
        $quiz_choice->quiz_id = $quiz_id;
        $quiz_choice->title = $v['title'];
        $quiz_choice->title_en = (empty($v['title_en']))?"":$v['title_en'];
        $quiz_choice->lib_type = $v['lib_key']==="final_answer"?NULL:$v['lib_key'];
        if($i==="fill_in"){
            $quiz_choice->disp_order = $i;
            $quiz_choice->is_correct = 1;
            $quiz_choice->is_fill_in = 1;
            if(!empty($v['title']))
                return $quiz_choice->save();
        }else{
            $quiz_choice->disp_order = $i;
            $quiz_choice->is_correct = ($i == $request->input($answer_key, 0));
            return $quiz_choice->save();
        }
        
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $exam_groups=ExamGroup::orderBy("id", "desc")->get();
        $quiz = Quiz::findOrFail($id);
        $select_stetho_sounds = StethoSound::whereNull("deleted_at")
            ->where("status", "!=", 0)
            ->orderBy('disp_order', 'desc')
            ->get()
            ->diff($quiz->stetho_sounds()->get());
        $select_description_stetho_sounds = StethoSound::whereNull("deleted_at")
            ->where("status", "!=", 0)
            ->orderBy('disp_order', 'desc')
            ->get();
        $stetho_sounds = StethoSound::whereNull("deleted_at")->get();
        return view('admin.quizzes.edit', compact('exam_groups','quiz', 'select_stetho_sounds', 'stetho_sounds', 'select_description_stetho_sounds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function update(QuizRequest $request, $id)
    {
        // 排他制御処理
        $quiz = Quiz::findOrFail($id);
        $is_force_update = $request->input('is_force_update', false);
        if (!$is_force_update) {
            $client_up_at = Carbon::parse($request->input('updated_at'));
            $sever_up_at = $quiz->updated_at;
            if ($client_up_at->lt($sever_up_at)) {
                return redirect()
                    ->route('admin.quizzes.edit', $id)
                    ->withInput()
                    ->withErrors(['is_force_update' => '編集中に他のユーザが更新しました。強制的に変更を行う場合は『強制更新』にチェックを入れ、保存ボタンを押してください。'])
        ;
            }
        }

        DB::beginTransaction();
        try {
            // クイズ
            $quiz = Quiz::findOrFail($id);

            // 画像ファイルを保存する
            if ($request->hasFile('image') || $request->has('image_path')) {
                $image_path = $this->moveFile($request, '/img/quizzes/', $quiz->id, 'image', 'image_path');
                if (is_null($image_path)) {
                    DB::rollback();
                    Log::error('ファイルの移動に失敗しました: 元ファイルのパス: '.public_path().$image_path);
                    abort(500, 'ファイルの移動に失敗しました');
                }
                $quiz->image_path = $image_path;
                $quiz->save();

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
                $this->removeTmpFile($request->input('image_path'));
                \Session::forget('quiz_image_path');
            }
            // 画像ファイルも画像のパスも入力されていない場合、画像を削除する。
            elseif (!$request->hasFile('image') && empty($request->input('image_path'))) {
                if (!empty($quiz->image_path)) {
                    $this->removeTmpFile(public_path($quiz->image_path));
                }
                $quiz->image_path = null;
                $quiz->save();
            }

            $quiz->title = $request->input('title');
            $quiz->title_en = $request->input('title_en');
            $quiz->question = $request->input('question');
            $quiz->question_en = $request->input('question_en');
            $quiz->case_age = $request->input('case_age');
            $quiz->case_gender = $request->input('case_gender');
            $quiz->case = $request->input('case');
            $quiz->case_en = $request->input('case_en');
            $quiz->limit_seconds = $request->input('limit_seconds');
            $d = $request->input('auscultation_description');
            $quiz->description_stetho_sound_id = empty($d) ? null : $d;
            $quiz->description_stethoscope_id = empty($request->input('stethoscope_description')) ? null : $request->input('stethoscope_description');
            $quiz->description_palpation_id = empty($request->input('palpation_description')) ? null : $request->input('palpation_description');
            $quiz->description_ecg_id = empty($request->input('ecg_description')) ? null : $request->input('ecg_description');
            $quiz->description_inspection_id = empty($request->input('examination_description')) ? null : $request->input('examination_description');
            $quiz->description_xray_id = empty($request->input('xray_description')) ? null : $request->input('xray_description');
            $quiz->description_echo_id = empty($request->input('echo_description')) ? null : $request->input('echo_description');
            $quiz->is_optional = $request->input('is_optional');
            $quiz->save();

            //save university id's in pivot table
            $quiz->exam_groups()->detach();
            if($request->input('group_attr')==0){
                foreach ($request->input("exam_group",array()) as $key => $value) {
                    $quiz->exam_groups()->attach($quiz->id,['exam_group_id'=>$value]);
                }
            }
            // 回答選択肢
            $quiz->quiz_choices()->delete();
            //stethoscope choices
            if (!empty($request->input('stethoscope', []))){
                foreach ($request->input('stethoscope_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"stethoscope_quiz_choices_correct_index");
                }
            }
            //auscultation choices
            if (!empty($request->input('auscultation', []))){
                foreach ($request->input('auscultation_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"auscultation_quiz_choices_correct_index");
                }
            }
            //palpation choices
            if (!empty($request->input('palpation', []))){
                foreach ($request->input('palpation_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"palpation_quiz_choices_correct_index");
                }
            }
            //ecg choices
            if (!empty($request->input('ecg', []))){
                foreach ($request->input('ecg_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"ecg_quiz_choices_correct_index");
                }
            }
            //examination choices
            if (!empty($request->input('examination', []))){
                foreach ($request->input('examination_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"examination_quiz_choices_correct_index");
                }
            }
            //xray choices
            if (!empty($request->input('xray', []))){
                foreach ($request->input('xray_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"xray_quiz_choices_correct_index");
                }
            }
            //echo choices
            if (!empty($request->input('echo', []))){
                foreach ($request->input('echo_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,$i,$v,$request,"echo_quiz_choices_correct_index");
                }
            }
            //final choices
            foreach ($request->input('final_answer_quiz_choices', []) as $i => $v) {
                $this->saveChoices($quiz->id,$i,$v,$request,"final_answer_quiz_choices_correct_index");
            }

            //fill-in final choices
            if(!$request->input('is_optional')){
                foreach ($request->input('fill_final_answer_quiz_choices', []) as $i => $v) {
                    $this->saveChoices($quiz->id,"fill_in",$v['fill_in'],$request,"");
                }
            }

            // 聴診音
            $quiz->stetho_sounds()->detach();
            //stethoscope library contents
            foreach ($request->input('stethoscope', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //auscultation library contents
            foreach ($request->input('auscultation', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //palpation library contents
            foreach ($request->input('palpation', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //ecg library contents
            foreach ($request->input('ecg', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //examination library contents
            foreach ($request->input('examination', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //xray library contents
            foreach ($request->input('xray', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
            //echo library contents
            foreach ($request->input('echo', []) as $i => $v) {
                $quiz->stetho_sounds()->attach($quiz->id, ['disp_order' => $i, 'stetho_sound_id' => $v['id'], 'description' => $v['description'], 'description_en' => $v['description_en']]);
            }
        } catch (Exception $e) {
            DB::rollback(); // TODO: エラー処理
            abort(500, '');
        }
        DB::commit();

        return redirect()->route('admin.quizzes.index')->with('message', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $errors = [];
        if (Quiz::findOrFail($id)->quiz_packs()->exists()) {
            // クイズパックが使用している場合
            $errors = ['delete_error' => 'クイズパックがクイズを使用しているため、削除に失敗しました。'];
            Log::info($errors);
        } else {
            DB::beginTransaction();
            try {
                $quiz = Quiz::findOrFail($id);
                //クイズ画像削除
                $filepath = public_path($quiz->image_path);
                if (\File::exists($filepath)) {
                    \File::delete($filepath);
                }
                $quiz->stetho_sounds()->detach();
                $quiz->quiz_choices()->delete();
                $quiz->delete();
                DB::commit();
            } catch (Exception $e) {
                Log::critical($e);
                DB::rollback();
                $errors = ['delete_error' => 'クイズの削除に失敗しました'];
            }
        }
        return redirect()->route('admin.quizzes.index')->withErrors($errors);
    }

    /**
     * Reorder quizzes list
     *
     * @param Request $request
     *
     * @return 1
     */
    public function reorderQuizzes(Request $request) {
        $quizzes = $request->quizzes;
        $result = null;
        foreach($quizzes as $q){
          $result = DB::table("quizzes")
            ->where("id", $q['quizzes_id'])
            ->update([
              'disp_order' => $q['disp_order']
            ]);
        }
        return $result;
    }

    /**
     * Transfer old university id's to new pivot table
     */
    public function transferOldExamGroups(){
        foreach (Quiz::get() as $key => $value) {
            $quiz = Quiz::findOrFail($value['id']);
            if($value['university_id'] && $value['id']){
                $quiz->exam_groups()->attach($quiz->id,['exam_group_id'=>$value['university_id']]);
            }
        }
        return redirect()->back();
    }
}
