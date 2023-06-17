<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $fillable = [
        'name', 'lastname', 'category', 'job', 'organization', 'description', 'photo', 'company_id', 'links', 'event_id', 'email'
    ];

    protected $casts = [
        'links' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function activities()
    {
        return $this->belongsToMany('App\Activity');
    }

    public function event()
    {
        return $this->belongsToMany('App\Event');
    }
}
