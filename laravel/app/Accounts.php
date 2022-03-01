<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
  
    // protected $table = 'accounts';
    public function onetime_key()
    {
        return $this->belongsTo('App\OnetimeKey','id','customer_id');
    }
}
