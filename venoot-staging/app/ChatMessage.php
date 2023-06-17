<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'reciever_id', 'reciever_type', 'reciever_id', 'sender_id', 'sender_type', 'sender_name', 'message',
    ];

    public function event()
    {
        return $this->belongsTo('App\User', 'foreign_key', 'other_key');
    }
}
