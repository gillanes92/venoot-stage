<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantQuestion extends Model
{
    protected $table = 'participant_questions';

    protected $fillable = [
        'activity_id', 'question_request_id', 'question', 'votes', 'participation_id'
    ];

    protected $appends = [
        'activity_name', 'message_sent', 'participant_name'
    ];

    public function getMessageSentAttribute()
    {
        return $this->question_request->message;
    }

    public function getActivityNameAttribute()
    {
        return optional($this->activity)->name;
    }

    public function getParticipantNameAttribute()
    {
        $data = $this->participation->participant->data;
        return $data['name'] . ' ' . $data['lastname'];
    }

    public function participation()
    {
        return $this->belongsTo('App\Participation');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function question_request()
    {
        return $this->belongsTo('App\QuestionRequest');
    }
}
