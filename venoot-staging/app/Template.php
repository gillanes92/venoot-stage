<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'body', 'test_email', 'company_id'
    ];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
