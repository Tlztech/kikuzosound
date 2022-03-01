<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{
    /**
     * Guarded Attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * One To One Relationship.
     *
     * @return obj
     */
    public function dealer()
    {
        return $this->belongsTo('App\Contact', 'dealer_id');
    }

    /**
     * One To One Relationship.
     *
     * @return obj
     */
    public function customer()
    {
        return $this->belongsTo('App\Contact', 'customer_id');
    }
}
