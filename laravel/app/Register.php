<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use SoftDeletes;

    /**
     * Customm Attributes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
