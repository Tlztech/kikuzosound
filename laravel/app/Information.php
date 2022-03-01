<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Information extends Model
{
    use SoftDeletes;

    /**
     * Table Attributes.
     *
     * @var string
     */
    protected $table = 'informations';

    /**
     * Guarded Attributes.
     *
     * @var array
     */
    protected $guarded = [];
}
