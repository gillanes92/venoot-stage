<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Device extends Authenticatable implements JWTSubject
{
    protected $fillable = ['user_id', 'event_id', 'ident', 'activity_id'];

    protected $appends = [
        'name', 'lastname'
    ];

    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function getLastnameAttribute()
    {
        return $this->user->lastname;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
