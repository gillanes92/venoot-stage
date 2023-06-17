<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StandManager extends Authenticatable implements JWTSubject
{
  use Notifiable;

  protected $guard_name = 'stands';

  protected $fillable = [
    'event_id', 'role', 'name', 'last_name', 'email', 'job', 'company', 'password', 'lw_id', 'lw_password'
  ];

  protected $hidden = [
    'password', 'lw_id', 'lw_password'
  ];

  public function events()
  {
    return $this->belongsTo('App\Event');
  }

  public function stand()
  {
    return $this->belongsTo('App\Stand');
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
