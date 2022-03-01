<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSortTable extends Model
{

    protected $table = 'user_table_orders';
    protected $fillable = ['user_id', 'table', 'order'];

}
