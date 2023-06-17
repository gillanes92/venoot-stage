<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
  protected $fillable = [
    'event_id', 'type', 'name', 'description', 'color', 'multimedia', 'interval',
  ];

  protected $casts = [
    'multimedia' => 'array',
  ];

  protected $with = [
    'meetings'
  ];

  protected $without = [
    'event'
  ];

  public function event()
  {
    return $this->belongsTo('App\Event');
  }

  public function manager()
  {
    return $this->hasOne('App\StandManager');
  }

  public function meetings()
  {
    return $this->hasMany('App\StandMeeting');
  }
}
