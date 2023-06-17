<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventShare extends Model
{
    protected $fillable = [
        'event_id', 'service',
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
