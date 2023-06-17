<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Participation extends Authenticatable implements JWTSubject
{
  use Notifiable;

  protected $fillable = [
    'participant_id', 'event_id', 'uuid', 'confirmed_status', 'confirmed_sent_at', 'confirmed_at', 'registered_at', 'invitee', 'invitees'
  ];

  protected $casts = [
    'invitees' => 'array',
  ];

  public function getViewMailCountAttribute()
  {
    return $this->sent_mails()->whereIn('category', ['ConfirmationRequest', 'QrEventReconfirmed'])->sum('opens');
  }

  public function getSentMailDateAttribute()
  {
    return $this->sent_mails()->whereIn('category', ['ConfirmationRequest', 'QrEventReconfirmed'])->min('created_at');
  }

  public function getViewMailDateAttribute()
  {
    return $this->sent_mails()->whereIn('category', ['ConfirmationRequest', 'QrEventReconfirmed'])->where('opens', '>', 0)->min('updated_at');
  }

  public function getBouncedAttribute()
  {
    return $this->sent_mails()->where('bounced', true)->exists();
  }

  public function participant()
  {
    return $this->belongsTo('App\Participant');
  }

  public function event()
  {
    return $this->belongsTo('App\Event');
  }

  public function sent_mails()
  {
    return $this->hasMany('App\SentEmail');
  }

  public function registration()
  {
    return $this->belongsTo('App\Registration');
  }

  public function queue_prints()
  {
    return $this->hasMany('App\QueuePrint');
  }

  public function secure_video_views()
  {
    return $this->hasMany('App\SecureVideoView');
  }

  public function chat_participants()
  {
    return $this->hasMany('App\ChatParticipant');
  }

  public function stream_chat_messages()
  {
    return $this->hasMany('App\StreamChatMessage');
  }

  public function chat_activity_times()
  {
    return $this->hasMany('App\ChatActivityTime');
  }

  public function submitted_questions()
  {
    return $this->hasMany('App\ParticipantQuestion');
  }

  public function all_stream_chat_messages()
  {
    return $this->belongsToMany('App\StreamChatMessage');
  }

  public function meetings()
  {
    return $this->hasMany('App\StandMeeting');
  }

  public function unread_stream_chat_messages($activity_id)
  {
    $participation_id = $this->id;
    return $this->event->stream_chat_messages()
      ->where('activity_id', $activity_id)
      ->whereDoesntHave('read_by_participations', function ($query) use ($participation_id) {
        $query->where('participation_id', $participation_id);
      })->get();
  }

  public function question_requests()
  {
    return $this->belongsToMany('App\QuestionRequest');
  }

  public function unread_question_requests($activity_id)
  {
    $participation_id = $this->id;
    return $this->event->question_requests()
      ->where('activity_id', $activity_id)
      ->whereDoesntHave('received_questions', function ($query) use ($participation_id) {
        $query->where('participation_id', $participation_id);
      })->get();
  }

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }
}
