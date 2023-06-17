<?php

namespace App;

use App\Constant;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    // protected $extras = $this->extras();

    protected $fillable = [
        'landing', 'confirmation_form', 'mailings', 'mailings_quantity', 'polls', 'polls_quantity', 'register_keys', 'register_keys_quantity', 'ticket_sale', 'free_tickets', 'company_id', 'estimate_id', 'webpay_client_order_id', 'administration', 'graphical_design', 'registering', 'lanyards', 'credentials', 'collectors_half', 'collectors_full', 'development'
    ];

    protected $casts = [
        'landing' => 'boolean',
        'confirmation_form' => 'boolean',
        'mailings' => 'boolean',
        'polls' => 'boolean',
        'register_keys' => 'boolean',
        'ticket_sale' => 'boolean',
        'free_tickets' => 'boolean',
        'status' => 'boolean',
        'administration' => 'boolean',
        'graphical_design' => 'boolean',
        'registering' => 'boolean',
        'lanyards' => 'boolean',
        'credentials' => 'boolean',
        'collectors_half' => 'boolean',
        'collectors_full' => 'boolean',
        'development' => 'boolean',
    ];

    protected $with = [
        'event',
        'extras',
    ];

    protected $appends = [
        'net_total', 'unpaid_ids', 'has_unpaid_ids'
    ];

    public function getLandingAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('landing', true);
        }

        return $value;
    }

    public function getConfirmationFormAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('confirmation_form', true);
        }

        return $value;
    }

    public function getMailingsAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('mailings', true);
        }

        return $value;
    }

    public function getPollsAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('polls', true);
        }

        return $value;
    }

    public function getRegisterKeysAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('register_keys', true);
        }

        return $value;
    }

    public function getTicketSaleAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('ticket_sale', true);
        }

        return $value;
    }

    public function getFreeTicketsAttribute($value)
    {
        if (!$value && !$this->estimate_id) {
            return $this->extras->contains('free_tickets', true);
        }

        return $value;
    }

    public function getMailingsQuantityAttribute($value)
    {
        if (!$this->estimate_id) {
            return  $value + $this->extras->sum('mailings_quantity');
        }

        return $value;
    }

    public function getPollsQuantityAttribute($value)
    {
        if (!$this->estimate_id) {
            return  $value + $this->extras->sum('polls_quantity');
        }

        return $value;
    }

    public function getRegisterKeysQuantityAttribute($value)
    {
        if (!$this->estimate_id) {
            return  $value + $this->extras->sum('register_keys_quantity');
        }

        return $value;
    }

    public function getStatusAttribute($value)
    {
        if (!$this->estimate_id) {
            return  $value && !$this->extras->contains('status', false);
        }

        return $value;
    }

    public function getUnpaidIdsAttribute()
    {
        $unpaid_ids = [];
        if (!$this->status) {
            array_push($unpaid_ids, $this->id);
        }

        foreach ($this->extras as $extra) {
            if (!$extra->status) {
                array_push($unpaid_ids, $extra->id);
            }
        }

        return $unpaid_ids;
    }

    public function getHasUnpaidIdsAttribute()
    {
        return count($this->unpaid_ids) > 0;
    }

    public function getNetTotalAttribute()
    {
        $prices = Constant::where('name', 'prices')->first()->object;

        $mailings_quantity = $this->mailings ? $this->mailings_quantity : 0;
        $polls_quantity = $this->polls ? $this->polls_quantity : 0;
        $register_keys_quantity = $this->mailings ? $this->register_keys_quantity : 0;

        $landing = $this->landing ? $prices->landing : 0;
        $confirmation_form = $this->confirmation_form ? $prices->confirmForm : 0;
        $mailings = $mailings_quantity * $prices->mailing;
        $polls = $polls_quantity * $prices->poll;
        $register_keys = $register_keys_quantity * $prices->registerKey;
        $ticket_sale = $this->ticket_sale ? $prices->ticketSale : 0;
        $free_tickets = $this->free_tickets ? $prices->freeTicket : 0;

        return $landing + $confirmation_form + $mailings + $polls + $register_keys + $ticket_sale + $free_tickets;
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function event()
    {
        return $this->hasOne('App\Event')->without('estimate');
    }

    public function original()
    {
        return $this->belongsTo('App\Estimate');
    }

    public function extras()
    {
        return $this->hasMany('App\Estimate')->without('event');
    }

    public function webpayClientOrder()
    {
        return $this->belongsTo('App\WenpayClientOrder');
    }
}
