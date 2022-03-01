<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model;

class TrialmembersServices extends Model
{
//  use SoftDeletes;
//  protected $dates = ['deleted_at'];

  protected $table = 'trialmembers_services';
}
