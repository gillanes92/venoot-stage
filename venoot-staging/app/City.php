<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * Relationships
     */

    public function communes()
    {
        return $this->belongsTo('App\Commune');
    }
}
