<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyRequest extends Model
{
    protected $fillable = [
        'company_id', 'user_id',
    ];

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
