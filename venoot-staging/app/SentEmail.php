<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    public function getOpensAttribute()
    {
        return $this->seen_at_emails()->count();
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function participants()
    {
        return $this->belongsTo('App\Participation');
    }

    public function participation()
    {
        return $this->belongsTo('App\Participation');
    }

    public function seen_at_emails()
    {
        return $this->hasMany('App\SeenAtEmail');
    }
}
