<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    public $fillable = ['activity_id', 'participation_id', 'user_id'];

    public function participation()
    {
        return $this->hasOne('App\Participation');
    }
}
