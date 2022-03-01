<?php

namespace App;

use Illuminate\Database\Eloquent\Model as APM;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountsProduct extends APM
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //
}
