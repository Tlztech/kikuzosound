<?php

namespace App;

class QuizChoice extends Model
{
    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['quiz_id', 'title', 'disp_order', 'is_correct'];

    public function quiz()
    {
        return $this->belongsTo('App\Quiz', 'quiz_id');
    }
}
