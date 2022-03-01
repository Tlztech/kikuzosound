<?php

namespace App;

use App\Model;

class Quiz extends Model
{

	protected $dates = ['created_at', 'updated_at'];

	protected $fillable = ['title', 'question', 'image_path', 'disp_order', 'limit_seconds', 'description_stetho_sound_id', 'user_id'];
	
	protected $appends = ['lib_count', 'choices_count'];

	// クイズパック
	public function quiz_packs()
	{
		return $this->belongsToMany('App\QuizPack');
	}

	// 聴診音
	public function stetho_sounds()
	{
		return $this->belongsToMany('App\StethoSound')->withPivot(['disp_order', 'description', 'description_en'])->orderBy("quiz_stetho_sound.disp_order", "ASC");
	}
	//　クイズの聴診音説明
	public function quiz_stetho_description()
	{
		return $this->hasMany('App\QuizStethoSound');
	}

	// クイズ選択肢
	public function quiz_choices()
	{
		return $this->hasMany('App\QuizChoice')->orderBy("disp_order", "ASC");
	}

	// 回答説明用の聴診音
	public function description_stetho_sound()
	{
		return $this->hasOne('App\StethoSound', 'id', 'description_stetho_sound_id');
	}
	//get stethoscope description
	public function libray_description($column)
	{
		return $this->hasOne('App\StethoSound', 'id', $column);
	}

	// 正解のクイズ選択肢
	public function correctSingleQuizChoice($mode="optional")
	{
		if($mode=="optional"){
			return $this->quiz_choices()->where("is_correct", true)->whereNull("is_fill_in")->whereNotNull("lib_type")->first();
		}else{//fill - in answers
			return $this->quiz_choices()->where("is_correct", true)->where("is_fill_in",1)->whereNotNull("lib_type")->first();
		}
	}
	public function correctNoContentQuizChoice($mode="optional")
	{
		if($mode=="optional"){
			return $this->quiz_choices()->where("is_correct", true)->whereNull("is_fill_in")->whereNull("lib_type")->first();
		}else{
			return $this->quiz_choices()->where("is_correct", true)->where("is_fill_in",1)->whereNull("lib_type")->first();
		}
	}

	public function exam_groups()
	{
		return $this->belongsToMany("App\ExamGroup", 'pivot_exam_group_quiz');
	}

	public function getLibCountAttribute()
	{
		return $this->stetho_sounds()->get()->groupBy('lib_type')->count();
	}


	public function exam_author()
    {
        return $this->belongsTo("App\User", 'user_id');
    }
	
	public function getChoicesCountAttribute()
	{
		$choices_count = 0;
		$single_lib =  $this->stetho_sounds()->first();

		if ($this->lib_count > 1) {
			$choices_count = $this->quiz_choices()->where('is_fill_in', null)->count();
		} else {
			$choices_count = $this->quiz_choices()->where('lib_type', $single_lib['lib_type'])->where('is_fill_in', null)->count();
		}
		return $choices_count;
	}
}
