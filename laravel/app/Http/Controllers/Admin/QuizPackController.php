<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\QuizPack;
use App\ExamGroup;
use App\Quiz;
use App\QuizQuizPack;
use Illuminate\Http\Request;
use App\Http\Requests\QuizPackRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use DB;
use Log;
use Carbon\Carbon;

class QuizPackController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $count = QuizPack::count(); 
    $quiz_packs = QuizPack::orderBy('disp_order','asc')->orderBy('id', 'desc')->paginate(isset($_GET['reorder']) ? $count : 10);
    return view('admin.quiz_packs.index', compact('quiz_packs'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $exam_groups= ExamGroup::orderBy("id", "desc")->get();
    $quizzes = Quiz::orderBy('disp_order', 'desc')->orderBy('id', 'desc')->get();
    return view('admin.quiz_packs.create', compact('exam_groups','quizzes'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(QuizPackRequest $request)
  {
    DB::beginTransaction();
    try {
      $title_color = "#".$request->input("title_color");
      $quiz_pack = new QuizPack();
      $quiz_pack->title = $request->input("title");
      $quiz_pack->title_en = $request->input("title_en");
      $quiz_pack->title_color = $title_color;
      $quiz_pack->description = $request->input("description");
      $quiz_pack->description_en = $request->input("description_en");
      $quiz_pack->icon_path = "";
      $quiz_pack->quiz_order_type = $request->input("quiz_order_type");
      $quiz_pack->max_quiz_count = $request->input("max_quiz_count");
      $quiz_pack->lang = $request->input("lang");
      $quiz_pack->is_public = $request->input("is_public");
      $quiz_pack->increment('disp_order');
      $result = $quiz_pack->save();

      //save university id's in pivot table
      $quiz_pack->exam_groups()->detach();
      if($request->input('group_attr')==0){
          foreach ($request->input("exam_group",array()) as $key => $value) {
              $quiz_pack->exam_groups()->attach($quiz_pack->id,['exam_group_id'=>$value]);
          }
      }

      // 画像ファイルを保存する
      if ( $request->hasFile('bg_img') || $request->has('icon_path') ) {
        $image_path = $this->moveFile($request, '/img/quiz_packs/', $quiz_pack->id, 'bg_img', 'icon_path');
        if ( is_null($image_path) ) {
          DB::rollback();
          Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
          abort(500, 'ファイルの移動に失敗しました');
        }
        $quiz_pack->icon_path = $image_path;
        $quiz_pack->save();
        list($w,$t) = getimagesize(public_path($image_path));
        if($t > 512){
           // リサイズ
          $img = Image::make(public_path($image_path))->resize(null,512,function ($constraint) {
            $constraint->aspectRatio();
          });
          // 上書き保存
          $img->save(public_path($image_path));
        }
        // セッションにパスがある場合、ファイルとSessionも消す
        $this->removeTmpFile($request->input('tmp_quiz_pack_image_path'));
        \Session::forget('tmp_quiz_pack_image_path');
      }

      $is_attach_success = true;
      foreach ($request->input("quizzes",array()) as $key => $value) {
        $quiz_pack->quizzes()->attach($quiz_pack->id,['disp_order'=>$key,'quiz_id'=>$value]);
      }
      DB::commit();
      Log::info("クイズパックを保存しました");
    } catch(\Exception $e) {
      DB::rollback();
      Log::error("クイズパックの保存に失敗しました");
      return redirect()->route('admin.quiz_packs.create')->withInput()->with('errors', 'データの保存に失敗しました');
    }
    return redirect()->route('admin.quiz_packs.index')->with('message', 'クイズパックを追加しました');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $quiz_pack = QuizPack::findOrFail($id);
    $exam_groups= ExamGroup::orderBy("id", "desc")->get();
    $quiz_pack_quizzes = DB::table('quiz_quiz_pack')
      ->select("quiz_quiz_pack.*", "quizzes.*")
      ->leftJoin('quizzes', 'quiz_quiz_pack.quiz_id', '=', 'quizzes.id')
      ->where("quiz_pack_id", $quiz_pack->id)
      ->orderBy("quiz_quiz_pack.disp_order", "ASC")
      ->get();

    // クイズパックに紐づかないクイズ一覧を取得
    $select_quizzes = Quiz::orderBy('disp_order', 'desc')->orderBy('id', 'desc')->get()
      ->diff($quiz_pack->quizzes()->get());
    return view('admin.quiz_packs.edit', compact('exam_groups','quiz_pack','select_quizzes', 'quiz_pack_quizzes'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @param Request $request
   * @return Response
   */
  public function update(QuizPackRequest $request, $id)
  {
    // 排他制御処理
    $quiz_pack = QuizPack::findOrFail($id);
    $is_force_update = $request->input('is_force_update',false);
    if (!$is_force_update) {
      $client_up_at = Carbon::parse($request->input('updated_at'));
      $sever_up_at  = $quiz_pack->updated_at;
      if ($client_up_at->lt($sever_up_at)) {
        return redirect()
          ->route('admin.quiz_packs.edit', $id)
          ->withInput()
          ->withErrors(['is_force_update'=>'編集中に他のユーザが更新しました。強制的に変更を行う場合は『強制更新』にチェックを入れ、保存ボタンを押してください。']);
      }
    }

    DB::beginTransaction();
    try {
      $title_color = "#".$request->input("title_color");
      $quiz_pack = QuizPack::findOrFail($id);
      $quiz_pack->title = $request->input("title");
      $quiz_pack->title_en = $request->input("title_en");
      $quiz_pack->title_color = $title_color;
      $quiz_pack->description = $request->input("description");
      $quiz_pack->description_en = $request->input("description_en");
      $quiz_pack->quiz_order_type = $request->input("quiz_order_type");
      $quiz_pack->max_quiz_count = $request->input("max_quiz_count");
      $quiz_pack->lang = $request->input("lang");
      $quiz_pack->is_public = $request->input("is_public");
      $quiz_pack->save();

      //save university id's in pivot table
      $quiz_pack->exam_groups()->detach();
      if($request->input('group_attr')==0){
          foreach ($request->input("exam_group",array()) as $key => $value) {
              $quiz_pack->exam_groups()->attach($quiz_pack->id,['exam_group_id'=>$value]);
          }
      }

      // クイズパックに紐づくクイズとの関連を全て削除
      $quiz_pack->quizzes()->detach();
      // クイズパックとクイズの関係を再構築
      foreach ($request->input("quizzes",array()) as $i=>$v) {
        $quiz_pack->quizzes()->attach($quiz_pack->id,['disp_order'=>$i,'quiz_id'=>$v]);
      }

      // 画像ファイルを保存する
      if ( $request->hasFile('bg_img') || $request->has('icon_path') ) {
        $image_path = $this->moveFile($request, '/img/quiz_packs/', $quiz_pack->id, 'bg_img', 'icon_path');
        if ( is_null($image_path) ) {
          DB::rollback();
          Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
          abort(500, 'ファイルの移動に失敗しました');
        }
        $quiz_pack->icon_path = $image_path;
        $quiz_pack->save();
        list($w,$t) = getimagesize(public_path($image_path));
        if($t > 512){
           // リサイズ
          $img = Image::make(public_path($image_path))->resize(null,512,function ($constraint) {
            $constraint->aspectRatio();
          });
          // 上書き保存
          $img->save(public_path($image_path));
        }
        // セッションにパスがある場合、ファイルとSessionも消す
        $this->removeTmpFile($request->input('tmp_quiz_pack_image_path'));
        \Session::forget('tmp_quiz_pack_image_path');
      }
      else if( !$request->hasFile('bg_img') && empty($request->input('icon_path')) ) {
        if( !empty($quiz_pack->icon_path) ) 
          $this->removeTmpFile(public_path($quiz_pack->icon_path));
        $quiz_pack->icon_path = NULL;
        $quiz_pack->save();
      }
    } catch (Exception $e) {
      DB::rollback();
      Log::error("クイズを変更に失敗しました");
      // TODO: エラー処理
      return redirect()
          ->route('admin.quiz_packs.edit', $id)
          ->withInput()
          ->withErrors(['error'=>'データの保存に失敗しました']);
    }
    DB::commit();
    
    Log::info("クイズを変更しました");

    return redirect()->route('admin.quiz_packs.index');
  }

  /**
   * クイズパックの表示順変更API
   *
   * @param  Request  $request
   * @return Response JSON
  */
  public function update_orders(Request $request) 
  {
    $quiz_packs = $request->quiz_packs;
    $result = null;
    DB::beginTransaction();
    try { 
      foreach ($quiz_packs as $q) {
        $q = QuizPack::where("id",$q['quiz_pack_id'])
              ->update(['disp_order' => $q['disp_order'] ]);
      }
      DB::commit();
      $result=$q;
      Log::debug("クイズパック表示順を変更しました");
    } catch(\Exception $e) {
      DB::rollback();
      Log::error("クイズパック表示順の変更に失敗しました");
      // TODO:仮置き
      return array(
        'code'    => 500,
        'message' => "クイズパック表示順の変更に失敗しました"
      );
    }
    return $result;
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $errors = [];
    $has_exams= DB::table('exam')->whereNull('deleted_at')->where('quiz_pack_id', $id)->get();
    $quiz_pack = QuizPack::findOrFail($id);
    $filepath = public_path($quiz_pack->icon_path);
    if (count($has_exams)>0) {
      $errors = ['delete_error_exam' => '試験はコンテンツを使用したため、削除できませんでした。'];
    } else {
      if ( \File::exists($filepath) ) {
        \File::delete($filepath);
      }
      $quiz_quiz_pack = QuizQuizPack::where('quiz_pack_id',$quiz_pack->id);
      $quiz_quiz_pack->delete();
      $quiz_pack->delete();

      return redirect()->route('admin.quiz_packs.index')->with('message', 'Item deleted successfully.');
    }

    return redirect()->route('admin.quiz_packs.index')->withErrors($errors);
  }

  /**
   * Transfer old university id's to new pivot table
   */
  public function transferOldExamGroups(){
    foreach (QuizPack::get() as $key => $value) {
        $quiz_pack = QuizPack::findOrFail($value['id']);
        if($value['university_id'] && $value['id']){
            $quiz_pack->exam_groups()->attach($quiz_pack->id,['exam_group_id'=>$value['university_id']]);
        }
    }
    return redirect()->back();
  }
}
