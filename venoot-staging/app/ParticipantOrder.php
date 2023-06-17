<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ParticipantOrder extends Model
{
    protected $fillable = [
        'number', 'event_id', 'participant_id', 'status', 'buyData', 'return_url',
    ];

    protected $casts = [
        'buyData' => 'array',
    ];

    protected $appends = [
        'total',
    ];

    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->buyData as $ticket) {
            $ticket = (object) $ticket;
            $ticket_db = Ticket::find($ticket->id);
            $discount = Discount::find($ticket->discount['id'] ?? null);

            if (isset($ticket->free) && $ticket->free) {
                $total += 0;
            } else if ($discount) {
                $total += ceil($ticket_db->price * $ticket->amount * (1 - $discount->type_quantity / 100));
            } else {
                $total += $ticket_db->price * $ticket->amount;
            }
        }
        return $total;
    }

    public function getTicketsDataAttribute()
    {
        $tickets_data = (object)['total' => 0, 'tickets' => []];
        foreach ($this->buyData as $ticket) {

            $ticket = (object) $ticket;

            if ($ticket->amount > 0) {

                $ticket_data = (object) [];


                $ticket_db = Ticket::find($ticket->id);
                $ticket_data->ticket = $ticket_db;

                $discount = Discount::find($ticket->discount['id'] ?? null);
                $ticket_data->discount = $discount;

                if (isset($ticket->free) && $ticket->free) {
                    $tickets_data->total += 0;
                    $ticket_data->cost = 0;
                } else if ($discount) {
                    $ticket_data->cost = ceil($ticket_db->price * $ticket->amount * (1 - $discount->type_quantity / 100));
                    $tickets_data->total += $ticket_data->cost;
                } else {
                    $ticket_data->cost = $ticket_db->price * $ticket->amount;
                    $tickets_data->total += $ticket_data->cost;
                }

                array_push($tickets_data->tickets, $ticket_data);
            }
        }

        return $tickets_data;
    }

    public function participant()
    {
        return $this->belongsTo('App\Participant');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function participant_tickets()
    {
        return $this->hasMany('App\ParticipantTicket');
    }
}
