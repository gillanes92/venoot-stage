<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'data', 'database_id', 'expo_token'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function database()
    {
        return $this->belongsTo('App\Database');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function events()
    {
        return $this->belongsToMany('App\Event', 'participations');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function hosted_meetings()
    {
        return $this->hasMany('App\Meeting', 'host_id');
    }

    public function attended_meetings()
    {
        return $this->hasMany('App\Meeting', 'attendant_id');
    }

    public function participant_orders()
    {
        return $this->hasMany('App\ParticipantOrder');
    }
}
