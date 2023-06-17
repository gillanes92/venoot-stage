<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppQuestion extends Model
{
    protected $fillable = [
        'question', 'answered',
    ];

    protected $with = [
        'activity',
    ];

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }
}
