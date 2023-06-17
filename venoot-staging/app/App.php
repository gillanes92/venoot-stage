<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    // protected $fillable = [
    //     'name', 'lastname', 'category', 'job', 'organization', 'description', 'photo', 'company_id', 'links', 'event_id',
    // ];

    // protected $casts = [
    //     'links' => 'array',
    // ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
