<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'logo', 'name', 'description', 'category', 'url', 'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function events()
    {
        return $this->belongsToMany('App\Event');
    }
}
