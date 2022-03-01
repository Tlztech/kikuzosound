<?php

namespace App;

use App\Model;

class QuizQuizPack extends Model
{
  protected $table = 'quiz_quiz_pack';
  protected $fillable = ['quiz_pack_id', 'quiz_id','disp_order'];
  public $timestamps = false;
}
