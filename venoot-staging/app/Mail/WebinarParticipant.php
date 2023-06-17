<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class WebinarParticipant extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $uuid = Str::uuid();

    $this->withSwiftMessage(function ($message) use ($uuid) {
      $headers = $message->getHeaders();
      $headers->addTextHeader('uuid', $uuid);
      $headers->addTextHeader('X-Mailgun-Variables', json_encode(
        [
          "env"  => config('app.env'),
          "uuid" => $uuid
        ]
      ));
    });

    return $this->view('emails.webinar-participant')
      ->subject('Webinar Participant Mail');
  }
}
