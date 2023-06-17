<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantTicket extends Model
{
    protected $fillable = [
        'ticket_id', 'participant_order_id', 'uuid', 'free', 'discount_id', 'participant_id'
    ];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket');
    }

    public function participant_order()
    {
        return $this->belongsTo('App\ParticipantOrder');
    }

    public function discount()
    {
        return $this->belongsTo('App\Discount');
    }

    public function participant()
    {
        return $this->belongsTo('App\Participant');
    }
}
