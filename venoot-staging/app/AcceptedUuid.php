<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcceptedUuid extends Model
{
    protected $fillable = [
        'uuid', 'quota'
    ];
}
