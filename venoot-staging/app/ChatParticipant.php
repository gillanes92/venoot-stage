<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    protected $fillable = [
        'event_id', 'activity_id', 'participation_id', 'name'
    ];

    protected $hidden = [
        'event_id', 'activity_id', 'participation_id', 'created_at', 'updated_at'
    ];

    public function getIdAttribute($value)
    {
        return $this->participation_id;
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function participation()
    {
        return $this->belongsTo('App\Participation');
    }
}
