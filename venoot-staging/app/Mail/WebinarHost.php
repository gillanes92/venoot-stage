<?php

namespace App\Mail;

use App\Event;
use App\Participant;
use App\Participation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;


class WebinarHost extends Mailable
{
  use Queueable, SerializesModels;

  public $category = 'WebinarHostEventLinks';
  public $event;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(Event $event)
  {
    $this->event = $event;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $uuid = Str::uuid();
    $event = $this->event;

    $this->withSwiftMessage(function ($message) use ($event, $uuid) {
      $headers = $message->getHeaders();
      $headers->addTextHeader('event-id', $event->id);
      $headers->addTextHeader('uuid', $uuid);
      $headers->addTextHeader('X-Mailgun-Variables', json_encode(
        [
          "env"  => config('app.env'),
          "uuid" => $uuid
        ]
      ));
    });

    return $this->view('emails.webinar-host')
      ->subject('Webinar Links');
  }
}
