<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'event_publish', 'event_landing', 'poll_publish', 'mailings', 'register_keys', 'register_days', 'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
