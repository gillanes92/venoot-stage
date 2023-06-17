<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\VerifyEmail;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Model that should be added to user
     */

    protected $with = ['company', 'roles'];

    public function preferredLocale()
    {
        return $this->locale;
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function companyRequest()
    {
        return $this->hasOne('App\CompanyRequest');
    }

    public function collects()
    {
        return $this->belongsToMany('App\Events');
    }

    public function webpayClientOrders()
    {
        return $this->hasMany('App\WebpayClientOrder');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function sso_tokens()
    {
        return $this->hasMany('App\SsoToken');
    }
}
