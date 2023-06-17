<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    
    /**
     * Relationships
     */

    public function communes()
    {
        return $this->hasMany('App\Commune');
    }
}
