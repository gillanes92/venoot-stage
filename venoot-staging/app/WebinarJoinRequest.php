<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebinarJoinRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'message', 'chatter_id', 
    ];

    public function chatter()
    {
        return $this->belongsTo('App\Chatter');
    }
}
