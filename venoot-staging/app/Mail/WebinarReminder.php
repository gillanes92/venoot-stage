<?php

namespace App\Mail;

use App\Event;
use App\Participant;
use App\Participation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Spatie\CalendarLinks\Link;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebinarReminder extends Mailable
{
  use Queueable, SerializesModels;

  public $user;
  public $event;
  public $participant;
  public $participation;
  public $fromName;
  public $link;
  public $subject;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(Event $event, Participant $participant, Participation $participation, Request $request, $fromName = null, $subject = null)
  {
    $this->user = Auth::user();
    $this->event = $event;
    $this->participant = $participant;
    $this->participation = $participation;

    $geoip = geoip($request->ip());
    $localized_start_time = Carbon::parse($event->start_time, 'America/Santiago')->setTimezone($geoip->timezone);
    $start_link_date = $event->start_date . ' ' . $localized_start_time->format('H:i:s');
    $this->link = Link::create($event->name, Carbon::createFromFormat('Y-m-d H:i:s', $start_link_date), Carbon::createFromFormat('Y-m-d H:i:s', $start_link_date));

    $this->fromName = $fromName ?? $this->event->name;
    $this->subject = $subject ?? 'Webinar - ' . $this->event->name;
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

    return $this->view('emails.webinar-reminder')
        ->from('mailing@email-venoot.com', $this->fromName)
        ->subject($this->subject);
  }
}