<?php

namespace App;

use App\Model;

class Comment extends Model
{
  protected $dates = ['created_at', 'updated_at'];

  protected $fillable = ['text', 'user_id', 'stetho_sound_id'];

  /**
   * コメントに紐付いたユーザを取得します
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

}
