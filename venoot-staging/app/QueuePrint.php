<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueuePrint extends Model
{
    protected $fillable = [
        'participation_uuid', 'printed',
    ];

    public function participation()
    {
        return $this->belongsTo('App\Participation');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
