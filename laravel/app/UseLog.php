<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UseLog extends Model
{
    protected $appends = ['exam_libraries'];
    //
    public function exam()
    {
        return $this->belongsTo('App\Exams', 'exam_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Account', 'user_id');
    }

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id');
    }
    public function libraryName() 
    {
        return $this->belongsTo('App\StethoSound', 'lib_id');
    }
    public function univ()
    {
        return $this->belongsTo('App\Universities', 'university_id');
    }
    public function uselog()
    {
        return $this->hasMany('App\UseLog', 'parent_id');
    }
    public function univ_account()
    {
        return $this->belongsTo('App\Accounts', 'user_id');
    }
    public function getExamLibrariesAttribute()
    {
        if($this->parent_id && $this->question_log){
            $all_lib_logs = UseLog::select("question_log")->where("parent_id",$this->parent_id)->get();
            $all_lib_ids=[];
            foreach($all_lib_logs as $r){
                $question_log = json_decode($r->question_log);
                if($question_log && $question_log->lib){
                    if(count($question_log->lib)>0){

                        $all_lib_ids = array_unique(array_merge($all_lib_ids,$question_log->lib));

                    }                   
                }
            };
            return StethoSound::select("id","title","title_en","lib_type")->whereIn("id",$all_lib_ids)->get();

        }else{
            return [];
        }

    }
}