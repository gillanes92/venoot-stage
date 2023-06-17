<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Meeting extends Model
{
    protected $fillable = [
        'host_id', 'attendant_id', 'date', 'start_time', 'end_time', 'end_time',
    ];

    protected $with = [
        'host', 'attendant'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        // 'start_time' => 'datetime:H:i',
        // 'end_time' => 'datetime:H:i',
    ];

    public function getStartTimeAttribute($value)
    {
        return Carbon::parse($value)->format("H:i");
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::parse($value)->format("H:i");
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function host()
    {
        return $this->belongsTo('App\Participant', 'host_id');
    }

    public function attendant()
    {
        return $this->belongsTo('App\Participant', 'attendant_id');
    }
}
