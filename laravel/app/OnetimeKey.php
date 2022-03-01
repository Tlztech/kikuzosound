<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OnetimeKey extends Model
{
    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = ['is_expired'];

    protected $dates = ['expiration_date'];

    public function accounts()
    {
        return $this->belongsTo('App\Accounts', 'customer_id');
    }
    public function univ_account()
    {
        return $this->belongsTo('App\Accounts', 'customer_id');
    }
    public function exam_groups()
    {
        return $this->belongsTo('App\ExamGroup', 'university_id');
    }

    public function getIsExpiredAttribute()
    {
        //dd(Carbon::now());
        if($this->expiration_date && $this->expiration_date->lte(Carbon::now())){
            $this->update(['university_id' => NULL]);
            if((int)$this->status){//if used
                return 2; // suspended
            }else{//issued
                return 1; // invalid
            }
        }else{
            return 0;
        }
    }
}
