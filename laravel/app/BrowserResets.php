<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrowserResets extends Model
{
    protected $fillable = ['customer_id', 'token'];
}
