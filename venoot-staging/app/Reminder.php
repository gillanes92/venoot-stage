<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'event_id', 'job_id', 'scheduled_for',
    ];

    public function getStatusAttribute()
    {
        return !$this->job;
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

}
