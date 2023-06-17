<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    /**
     * Relationships
     */

    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    public function cities()
    {
        return $this->hasMany('App\City');
    }
}
