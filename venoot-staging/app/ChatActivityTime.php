<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatActivityTime extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'activity_id', 'participation_id',
    ];

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function participation()
    {
        return $this->belongsTo('App\Participation');
    }

    public function participant()
    {
        return $this->belongsToThrough('App\Participant', 'App\Participation');
    }
}
