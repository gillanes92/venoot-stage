<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamChatMessage extends Model
{
    protected $fillable = [
        'event_id', 'activity_id', 'participation_id', 'message_data',
    ];

    protected $casts = [
        "message_data" => "object",
    ];

    protected $appends = [
        'activity_name', 'full_name',
    ];

    protected $hidden = [
        'participation', 'activity'
    ];

    public function getParticipationId($value)
    {
        return $value ?? 0;
    }

    public function getActivityNameAttribute()
    {
        return $this->activity ? $this->activity->name : null;
    }

    public function getFullNameAttribute()
    {
        if ($this->participation) {
            return $this->participation->participant->data['name'] . " " . $this->participation->participant->data['lastname'];
        } else {
            return "Speaker";
        }
    }

    public function read_by_participations()
    {
        return $this->belongsToMany('App\Participation');
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
