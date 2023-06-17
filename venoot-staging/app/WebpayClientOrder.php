<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebpayClientOrder extends Model
{
    protected $fillable = [
        'token', 'order', 'user_id', 'estimates'
    ];

    protected $casts = [
        'estimates' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function estimates()
    {
        return $this->hasMany('App\Estimate');
    }
}
