<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountKeyValue extends Model
{
    protected $fillable = [
        'discount_id', 'value',
    ];

    public function discount()
    {
        return $this->belongsTo('App\Discount');
    }
}
