<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'name', 'description', 'company_id'
    ];

    protected $with = [
        'questions',
    ];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function events()
    {
        return $this->belongsToMany('App\Event')->withTimestamps();
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }
}
