<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Model;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $dates = ['created_at', 'updated_at'];

    // 役割を示す定数
    // システム管理者
    public static $ROLE_ADMIN          = 99;
    // 監修者
    public static $ROLE_SUPERINTENDENT = 10;
    // 有償会員
    public static $ROLE_PAID_USER      =  1;
    // 無償会員
    public static $ROLE_FREE_USER      =  0;
    //Customer Admin
    public static $ROLE_CUSTOMER_ADMIN      =  100;
    //University Admin
    public static $ROLE_UNIVERSITY_ADMIN      =  101;
    //University teacher
    public static $ROLE_UNIVERSITY_TEACHER      =  201;
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role', 'enabled','timezone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
    * 監修者一覧を取得します
    */
    public function scopeSuperintendents($query)
    {
        return $query->where('role', '=', User::$ROLE_SUPERINTENDENT);
    }

    public function scopeUniversityAdmin($query)
    {
        return $query->where('role', '=', User::$ROLE_UNIVERSITY_ADMIN);
    }

    public function scopeUniversityTeacher($query)
    {
        return $query->where('role', '=', User::$ROLE_UNIVERSITY_TEACHER);
    }
    
    public function isAdmin()
    {
        return $this->role == User::$ROLE_ADMIN;
    }

    public function isSuperintendent()
    {
        return $this->role == User::$ROLE_SUPERINTENDENT;
    }

    public function isCustomerAdmin()
    {
        return $this->role == User::$ROLE_CUSTOMER_ADMIN;
    }

    public function isUniversityAdmin()
    {
        return $this->role == User::$ROLE_UNIVERSITY_ADMIN;
    }
    
    public function isUniversityTeacher()
    {
        return $this->role == User::$ROLE_UNIVERSITY_TEACHER;
    }
    
    

    public function exam_group() {
        return $this->belongsTo('App\ExamGroup','university_id');
    }
}
