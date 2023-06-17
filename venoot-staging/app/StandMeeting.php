<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StandMeeting extends Model
{
  protected $fillable = [
    'stand_id', 'participation_id', 'start', 'end', 'status', 'manager_link', 'participant_link'
  ];

  protected $hidden = [
    'manager_link',
  ];

  public function stand()
  {
    return $this->belongsTo('App\Stand');
  }

  public function participation()
  {
    return $this->belongsTo('App\Participation');
  }
}
