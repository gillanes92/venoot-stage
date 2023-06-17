<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $casts = [
        'alternative_ids' => 'array'
    ];

    public function alternative()
    {
        return $this->belongsTo('App\Alternative');
    }

    public function getAlternativesAttribute()
    {
        return Alternative::whereIn('id', $this->alternative_ids)->get();
    }
}
