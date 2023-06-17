<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityActor extends Model
{
    protected $fillable = [
        'actor_id', 'activity_id',
    ];

    public function actor()
    {
        return $this->belongsTo('App\Actor');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }
}
