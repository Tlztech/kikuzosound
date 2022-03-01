<?php

namespace App;

use App\Model;

class FavoritePacks extends Model
{
  protected $table = 'favorite_packs';

  /**
   * 指定アカウントIDのデータ取得【未使用】
   */
  public function scopeSelectAID($query,$aid)
  {
    return $query->where('account_id', '=', $aid);
  }
}
