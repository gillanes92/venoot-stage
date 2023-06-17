<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 'type',
    ];

    protected $with = [
        'alternatives',
    ];

    public function poll()
    {
        return $this->belongsTo('App\Poll');
    }

    public function alternatives()
    {
        return $this->hasMany('App\Alternative');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function stream_events()
    {
        return $this->belongsToMany('App\Event');
    }
}
