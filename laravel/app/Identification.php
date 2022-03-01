<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Identification extends Model
{
    use SoftDeletes;

    /**
     * custom table attributes.
     *
     * @var string
     */
    protected $table = 'identification';
}
