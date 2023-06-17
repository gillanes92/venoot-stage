<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    protected $fillable = [
        'name', 'type', 'required', 'in_form', 'fields', 'company_id', 'verifications'
    ];

    protected $hidden = [
        'access_key'
    ];

    protected $casts = [
        'fields' => 'array',
        'required' => 'boolean',
        'in_form' => 'boolean',
    ];

    protected $with = [
        'profiles',
    ];

    protected $appends = [
        'verifications_needed'
    ];

    public function getVerificationsNeededAttribute()
    {
        return $this->participants()->where('blocked', false)->where('allow_mailing', true)->where('verified', false)->count();
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function participants()
    {
        return $this->hasMany('App\Participant');
    }

    public function profiles()
    {
        return $this->hasMany('App\Profile');
    }

    public function events()
    {
        return $this->hasManyThrough('App\Event', 'App\Profile');
    }
}
