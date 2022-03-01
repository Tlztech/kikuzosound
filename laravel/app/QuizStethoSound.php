<?php

namespace App;

use App\Model;

class QuizStethoSound extends Model
{
  protected $table = 'quiz_stetho_sound';
  protected $fillable = ['quiz_id','stetho_sound_id','disp_order','description'];
  public $timestamps = false;
}
