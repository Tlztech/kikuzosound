<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $guarded = [''];

    /**
     * One To Many Relationship.
     *
     * @return obj
     */
    public function company()
    {
        return $this->belongsTo('App\Companys', 'company_id');
    }
}
