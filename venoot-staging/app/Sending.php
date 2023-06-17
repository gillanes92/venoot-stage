<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sending extends Model
{
    protected $fillable = [
        'event_id', 'template_id', 'subject',
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }
}
