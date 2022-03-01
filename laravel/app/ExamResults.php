<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamResults extends Model
{
    /**
     * Many to One.
     *
     * @return obj
     */
    public function exam()
    {
        return $this->belongsTo('App\Exams', 'exam_id');
    }

    /**
     * Many to One.
     *
     * @return obj
     */
    public function account()
    {
        return $this->belongsTo('App\Account', 'customer_id');
    }

    /**
     * Many to One.
     *
     * @return obj
     */
    public function quiz_choice()
    {
        return $this->belongsTo('App\QuizChoice', 'quiz_id');
    }
}
