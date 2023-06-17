<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Participation;
use App\Event;
use Illuminate\Support\Str;

class ParticipantTemplate extends Mailable
{
  use Queueable, SerializesModels;

  public $category;
  public $fromName;
  public $fromEmail;
  public $subject;
  public $event;
  public $participation;
  public $body;
  public $start_date;
  public $start_time;

  /**
   * Create a new message instance.
   *
   * @return void
   * */

  public function __construct(Event $event, Participation $participation, $fromName, $subject, $category, $body)
  {
    $this->event = $event;
    $this->participation = $participation;

    $this->category = $category;

    $this->fromName = $event->from_name ?? $fromName;
    $this->subject = $subject;
    $this->fromEmail = $event->from_email ?? 'mailing@email-venoot.com';
    $this->body = $body;

    $this->start_date = Carbon::parse($this->event->start_date)->locale('es');
    $this->start_time = Carbon::parse($this->event->start_time)->locale('es');
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $event = $this->event;
    $participation = $this->participation;
    $uuid = Str::uuid();

    $this->withSwiftMessage(function ($message) use ($event, $participation, $uuid) {
      $headers = $message->getHeaders();
      $headers->addTextHeader('event-id', $event->id);
      $headers->addTextHeader('participation-id', $participation->id);
      $headers->addTextHeader('category', $this->category);
      $headers->addTextHeader('uuid', $uuid);
      $headers->addTextHeader('X-Mailgun-Variables', json_encode(
        [
          "env"  => config('app.env'),
          "uuid" => $uuid
        ]
      ));
    });

    return $this->view('emails.direct')
      ->from($this->fromEmail, $this->fromName)
      ->subject($this->subject);
  }
}
