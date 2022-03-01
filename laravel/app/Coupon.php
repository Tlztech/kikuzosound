<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;

    /**
     * casting attributes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * guarded attributes.
     *
     * @var array
     */
    protected $guarded = [''];

    /**
     * Many To One Realationship.
     *
     * @return obj
     */
    public function companies()
    {
        return $this->belongsTo('App\Companys','company_id');
    }
}
