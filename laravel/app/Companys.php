<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companys extends Model
{
    /**
     * @var array
     */
    protected $guarded = [''];
    /**
     * One to Many
     * @return obj
     */
    public function contacts()
    {
        return $this->hasMany('\App\Contact', 'company_id');
    }
}
