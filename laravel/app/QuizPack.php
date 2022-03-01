<?php

namespace App;

use App\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizPack extends Model
{
  use SoftDeletes;

  protected $dates = ['created_at', 'updated_at'];
  // 出題形式を示す定数
  // 固定
  public static $ORDER_FIXED  = 0;
  // ランダム
  public static $ORDER_RANDOM = 1;

  protected $fillable = ['title' ,'title_en' ,'title_color' ,'description' ,'description_en' ,'icon_path' ,'quiz_order_type','max_quiz_count','is_public' ,'disp_order','updated_at', 'lang' ];

 /**
   * ステータスが『公開』のクイズパックを取得します
   */
  public function scopePublicAll($query)
  {
    return $query->where('is_public', '=', true);
  }

  public function quizzes()
  {
    return $this->belongsToMany('App\Quiz')->withPivot('disp_order')->orderBy("disp_order", "ASC");
  }

  public function quizquizpacks()
  {
    return $this->hasMany('App\QuizQuizPack');
  }


  public function exams() {
    return $this->hasMany('App\Exams')->with('exam_author');
  }

  /**
   * 次のクイズを取得します
   * @param  array $exclude_ids: 除外する（回答済の）クイズのID配列
   */
  public function nextQuiz($exclude_ids) {
    if ($this->quiz_order_type == QuizPack::$ORDER_RANDOM) {
      return $this->quizzes()->whereNotIn('quiz_id',$exclude_ids)->get()->random();
    }
    // 固定の場合
    else {
      $next_quiz = DB::table('quiz_quiz_pack')
        ->where('quiz_pack_id', $this->id)
        ->whereNotIn('quiz_id',$exclude_ids)
        ->orderBy('disp_order', 'ASC')
        ->first();

      $next_quiz->id = $next_quiz->quiz_id;
      return $next_quiz;
    }
  }

  public function exam_groups()
  {
      return $this->belongsToMany("App\ExamGroup", 'pivot_exam_group_quiz_pack');
  }
}
