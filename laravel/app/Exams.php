<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exams extends Model
{

    use SoftDeletes;
  
    protected $table = 'exam';
       /**
     * casting attributes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
	protected $appends = ['quiz_count','university_obj'];


    protected $fillable = ['id','name', 'name_jp', 'university_id', 'quiz_pack_id', 'result_destination_email', 'is_publish', 'user_id'];

    public function exam_group() {
        return $this->belongsTo('App\ExamGroup','university_id');
    }

    public function quiz_pack() {
        return $this->belongsTo('App\QuizPack','quiz_pack_id')->withTrashed();
    }

    public function university(){
        return $this->belongsTo('App\Universities','university_id');
    }

    public function exam_groups()
    {
        return $this->belongsToMany("App\ExamGroup", 'pivot_exam_exam_group','exam_id');
    }

    public function exam_author()
    {
        return $this->belongsTo("App\User", 'user_id');
    }
    
    public function getUniversityObjAttribute(){
        $university = $this->university()->first();
        return $university;
    }

    public function getQuizCountAttribute(){
        $quiz_pack =  $this->quiz_pack()->first();
        $quiz_count = $quiz_pack->max_quiz_count;
        return $quiz_count;
    }
}
