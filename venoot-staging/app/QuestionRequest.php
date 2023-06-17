<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionRequest extends Model
{
    protected $fillable = [
        'event_id', 'message', 'question_limit', 'per_person_question_limit', 'vote_instead', 'activity_id'
    ];

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function activity()
    {
        return $this->belongsTo('App\Activity');
    }

    public function submitted_questions()
    {
        return $this->hasMany('App\ParticipantQuestion');
    }

    public function received_questions()
    {
        return $this->belongsToMany('App\Participation');
    }
}
