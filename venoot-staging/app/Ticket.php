<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'name', 'description', 'quantity', 'price', 'event_id', 'status', 'available', 'protection', 'database_id', 'key', 'email', 'protection_quantity', 'currency'
    ];

    protected $appends = [
        'left', 'sell_status', 'event_name',
    ];

    protected $hidden = [
        'status',
    ];

    protected $with = [
        'database'
    ];

    public function getLeftAttribute()
    {
        return $this->quantity - $this->participant_tickets()->whereHas('participant_order', function ($query) {
            $query->where('status', 1);
        })->count();
    }

    public function getSellStatusAttribute()
    {
        if (!$this->status || $this->left <= 0) {
            return false;
        }

        return true;
    }

    public function getEventNameAttribute()
    {
        return $this->event()->without('tickets')->first()->name;
    }

    public function participant_tickets()
    {
        return $this->hasMany('App\ParticipantTicket');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function discounts()
    {
        return $this->belongsToMany('App\Discount');
    }

    public function database()
    {
        return $this->belongsTo('App\Database');
    }
}
