<?php

namespace App\Mail;

use App\Participant;
use App\StandMeeting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MeetingTaken extends Mailable
{
  use Queueable, SerializesModels;

  public $event;
  public $meeting;
  public $manager;
  public $stand;
  public $participant;
  public $profile;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(StandMeeting $meeting, Participant $participant, $profile)
  {
    $this->event = $meeting->stand->event;
    $this->meeting = $meeting;
    $this->manager = $meeting->stand->manager;
    $this->stand = $meeting->stand;
    $this->participant = $participant;
    $this->profile = $profile;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->markdown('emails.meeting_taken');
  }
}
