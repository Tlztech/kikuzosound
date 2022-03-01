<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrowserResetsHistory extends Model
{
    protected $table = 'browser_resets_history';
    protected $fillable = ['onetime_key_id', 'prev_onetime_key'];
}
