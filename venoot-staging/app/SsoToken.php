<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SsoToken extends Model
{
    protected $fillable = [
        'sso_token', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
