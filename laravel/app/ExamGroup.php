<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamGroup extends Model
{
    // use SoftDeletes;

    protected $table = 'universities';

    protected $fillable = ['id','name'];

    public function exams() {
        return $this->hasMany('App\Exams');
    }

    public function exam_groups() {
        return $this->belongsToMany("App\ExamGroup", 'pivot_exam_exam_group');
      }

    public function pivot_exam_exam_group() {
        return $this->belongsToMany("App\Exams", 'pivot_exam_exam_group',"exam_group_id","exam_id");
    }

    public function pivot_exam_group_quiz_pack() {
        return $this->belongsToMany("App\QuizPack", 'pivot_exam_group_quiz_pack',"exam_group_id","quiz_pack_id");
    }

    public function pivot_exam_group_quiz() {
        return $this->belongsToMany("App\Quiz", 'pivot_exam_group_quiz',"exam_group_id","quiz_id");
    }

    public function pivot_exam_group_stetho_sound() {
        return $this->belongsToMany("App\StethoSound", 'pivot_exam_group_stetho_sound',"exam_group_id","stetho_sound_id");
    }
}
