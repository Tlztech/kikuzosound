<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class Groups extends Model
{
    use SoftDeletes;

    /**
     * custom table attributes.
     *
     * @var string
     */
    protected $table = 'groups';
}
