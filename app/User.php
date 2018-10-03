<?php

namespace App;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;

    protected $dates=['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    
     */

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER='0';

    const ADMIN_USER='true';
    const REGULAR_USER ='false';

    public $transformer=UserTransformer::class;
    protected $table='users';

    protected $fillable = [
        'name', 
        'email', 
        'password',
        'verified', // if user is verified or not
        'verification_token', //verifiation of the user email
        'admin', //if user is an admin or not
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        //'verification_token'
    ];

    public function isverified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin ==User::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return str_random(40);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    public function getEmailAttribute($email)
    {
        return strtolower($email);
    }
}
