<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name', 'trigger', 'trigger_value', 'trigger_quantity', 'type', 'type_quantity', 'quota'
    ];

    protected $with = [
        'tickets'
    ];

    protected $appends = [
        'in_use'
    ];

    public function getInUseAttribute()
    {
        return $this->participant_tickets()->count() > 0;
    }

    public function tickets()
    {
        return $this->belongsToMany('App\Ticket');
    }

    public function participant_tickets()
    {
        return $this->hasMany('App\ParticipantTicket');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function discount_key_values()
    {
        return $this->hasMany('App\DiscountKeyValue');
    }
}
