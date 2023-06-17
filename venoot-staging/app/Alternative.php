<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $fillable = [
        'alternative',
    ];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }
}
