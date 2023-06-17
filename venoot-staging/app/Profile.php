<?php

namespace App;

use App\Participant;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name', 'conditions',
    ];

    protected $casts = [
        'conditions' => 'array',
    ];

    public function getParticipantsAttribute()
    {
        $participants = Participant::where('database_id', $this->attributes['database_id']);

        foreach (json_decode($this->attributes['conditions'], true) as $condition) {
            $participants = $participants->where('data->' . $condition['variable'], $condition['operation'], $condition['parameter']);
        }

        return $participants->get();
    }

    public function getParticipantsCollectionAttribute()
    {
        $participants = Participant::where('database_id', $this->attributes['database_id']);

        foreach (json_decode($this->attributes['conditions'], true) as $condition) {
            $participants = $participants->where('data->' . $condition['variable'], $condition['operation'], $condition['parameter']);
        }

        return $participants;
    }

    public function database()
    {
        return $this->belongsTo('App\Database');
    }

    public function events()
    {
        return $this->hasMany('App\Events');
    }

    public function participations()
    {
        return $this->participants_collection()->map(function ($participant) {
            return $participant->participation;
        });
    }
}
