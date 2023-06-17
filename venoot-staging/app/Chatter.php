<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Chatter extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'event_id', 'type', 'token', 'presenter_uuid',
    ];

    protected $hidden = ['token'];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function join_requests()
    {
        return $this->hasMany('App\WebinarJoinRequest');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
