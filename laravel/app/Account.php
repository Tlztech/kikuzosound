<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $table = 'accounts';
    /**
     * Guarded Attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Customm Attributes.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * One To Many Relationship.
     *
     * @return obj
     */
    public function contracts()
    {
        return $this->belongsTo('App\Contracts', 'contract_id');
    }

    /**
     * Many To One Relationship.
     *
     * @return obj
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'accounts_products', 'account_id', 'product_id');
    }

    /**
     * Many to One.
     *
     * @return obj
     */
    public function onetime_key()
    {
        return $this->belongsTo('App\OnetimeKey','id','customer_id');
    }
}
