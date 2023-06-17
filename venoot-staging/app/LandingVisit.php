<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandingVisit extends Model
{
    protected $fillable = [
        'uuid', 'event_id', 'service', 'device',
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
