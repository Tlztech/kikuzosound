<?php

namespace App\Http\Common;

use Session;
use App\QuizChoice;

class QuizExam
{

    /**
     * Get Quiz/Exam Correct Answer
     *
     * @param string,array $quiz, $quiz_type, $content_type
     */
    public function getCorrectAnswer($quiz, $quiz_type, $content_type)
    {
        $correct_answer = null;
        if($quiz_type==1){//fill_in mode
            if($content_type===null || $content_type===7){//no contents quiz // final answer
                $correct_answer = $quiz->quiz_choices()->where('is_fill_in',1)->whereNull("lib_type")->where("is_correct",1)->get();
            }else{
                $correct_answer = $quiz->quiz_choices()->where('is_fill_in',1)->where("lib_type",$content_type)->where("is_correct",1)->first();
            }
        }else{
            if($content_type===null || $content_type===7){//no contents quiz
                $correct_answer = $quiz->quiz_choices()->whereNull('is_fill_in')->whereNull("lib_type")->where("is_correct",1)->first();
            }else{
                $correct_answer = $quiz->quiz_choices()->whereNull('is_fill_in')->where("lib_type",$content_type)->where("is_correct",1)->first();
            }
        }
        return $correct_answer;
    }

    /**
     * Check Correct Answer
     *
     * @param string,array $quiz, $quiz_type, $content_type
     */
    public function checkCorrectAnswer($quiz_type, $content_type, $correct_answer, $quiz_choice_title, $quiz_choice_id){
        $correct="";
        $correct_id="";
        $correct_title="";
        $correct_title_en="";
        $is_correct = 0;
        $correct_groupp_arry=[];
        $is_final_answer = false;
        if($quiz_type==1){//fill_in mode
            if($content_type===null || $content_type===7){//no contents quiz
                $correct = $correct_answer ? $correct_answer->pluck("title")->toArray() : [];
                //$implode_array =  implode(",", $correct_groupp_arry); //combine array with comma into string
                //$correct = explode( ",", $implode_array ); // separate string with comma into array
                $correct_id =  $correct_answer ? json_encode($correct_answer->pluck("id")) : "";
                $correct_title= $correct ? json_encode($correct) : "";
                $is_final_answer = true;
            }else{
                $correct = $correct_answer ? $correct_answer->title : "";
                $correct = explode( ",", $correct );
                $correct_id  = $correct_answer ? $correct_answer->id : "";
                $correct_title= $correct;
                $is_final_answer = false;
            }
            //CHECK FILL-IN ANSWER USING TEXTJUDGER
            if($correct_answer){//if has correct answer
                $is_correct = $this->checkFillinAnswer($correct, $quiz_choice_title, $is_final_answer);
              }else{
                $is_correct = 1;//set correct for no correct answer quiz
            }
            
        }else{//optional
            if($correct_answer){//if has correct answer
                $choice = QuizChoice::find($quiz_choice_id);
                $choice_title = $choice?$choice->title:"";
                $is_correct =$choice?$choice->is_correct:0;


                $correct_id  = $correct_answer ? $correct_answer->id : "";
                $correct_title = $correct_answer ? $correct_answer->title : "";
                $correct_title_en = $correct_answer ? $correct_answer->title_en : "";
            }else{
                $is_correct = 1;//set correct for no correct answer quiz
            }
        }
        
        return array(
            "is_correct" => $is_correct,
            "correct_id" => $correct_id,
            "correct_title" => $correct_title,
            "correct_title_en" => $correct_title_en,
        );
    }

    /**
     * Check Fillin Answer
     *
     * @param string,array $correct_answer,$answer
     */
    public function checkFillinAnswer($correct_answer,$answer, $is_final_answer=false){
        $TextJudger = new \App\Http\Common\TextJudger();
        if($is_final_answer){
            $correct_count = [];
            $answer_group = explode( ",", $answer );
            foreach($answer_group as $key => $answer){
                $correct_in_group = $this->checkGroupAnswer($correct_answer,$answer);
                if($correct_in_group){
                    $correct_count = array_merge($correct_count,$correct_in_group);
                }else{
                    return 0; // wrong answer without matching each field group
                }
            }
            $correct_count = array_unique($correct_count);
            if(count($correct_answer)==count($correct_count)){
                return 1;
            }else{
                return 0;
            }
        }else{
            $TextJudger->setCorrect($correct_answer);
            $TextJudger->setAnswer($answer);
            if ($TextJudger->check()) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    /**
     * Check Fillin Answer by input group
     *
     * @param string,array $correct_answer,$answer
     */
    public function checkGroupAnswer($correct_answer,$answer){
        $TextJudger = new \App\Http\Common\TextJudger();
        $group_fail_count = 0;
        $correct_row = [];
        foreach($correct_answer as $key => $correct_ans){
            $correct_group = explode( ",", $correct_ans); // separate string with comma into array
            $TextJudger->setCorrect($correct_group);
            $TextJudger->setAnswer($answer);
            if ($TextJudger->check()) {
                array_push($correct_row,"r".$key);
            }else{
                $group_fail_count++;
            }
        }
        if(count($correct_answer) <= $group_fail_count){ //answer has no match in every row
            return false;
        }else{
            return $correct_row;
        }
    }
    /**
     * Check Fillin Answer
     *
     * @param string,array $correct_answer,$answer
     */
    public function assignLogsVariables($content_ids, $is_multiple, $quiz_choice_id, $quiz_choice_title, $answered){
        $userId = Session::get('MEMBER_3SP_ACCOUNT_ID');
        $universityId = Session::get('MEMBER_3SP_UNIVERSITY_ID');
        $parent_id = Session::get('MEMBER_3SP_EXAM_QUIZ_ID');
        $answer_log=null;

        $question_log[]= array(
            "lib" => $content_ids ? $content_ids : "",
            "question_type" => $is_multiple?"multiple":"single"
        );

        if ($quiz_choice_id >= 0) {
            $answer_log[]= array(
                "correct_id" => $answered ? $answered["correct_id"] : "",
                "correct_title_en" => $answered ? $answered["correct_title_en"] : "",
                "correct_title_ja" => $answered ? $answered["correct_title"] : "",
                "answered_id" => $quiz_choice_id ? $quiz_choice_id : 0,
                "answered" => $quiz_choice_title ? $quiz_choice_title : ""
        
            );
        }

        return array(
            "userId" => $userId ? $userId : 0,
            "universityId" => $universityId ? $universityId : 0,
            "parent_id" => $parent_id ? $parent_id : null,
            "answer_log" => $answer_log,
            "question_log" => $question_log
        );
    }
}
