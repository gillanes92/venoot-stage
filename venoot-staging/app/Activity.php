<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Activity extends Model
{
    protected $fillable = [
        'name', 'location', 'date', 'start_time', 'end_time', 'description', 'event_id', 'video_url', 'extra_link', 'show_in_landing', 'style', 'order'
    ];

    protected $with = [
        'actors',
    ];

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Actor')->withPivot('id')->orderBy('pivot_id');
    }

    public function devices()
    {
        return $this->hasMany('App\Devices');
    }

    public function appQuestions()
    {
        return $this->hasMany('App\AppQuestion');
    }
}
