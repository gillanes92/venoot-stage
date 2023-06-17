<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecureVideoView extends Model
{
    protected $fillable = [
        'video_url', 'seen_at', 'stopped_at', 'event_id'
    ];

    public function participation()
    {
        return $this->belongsTo('App\Participation');
    }
}
