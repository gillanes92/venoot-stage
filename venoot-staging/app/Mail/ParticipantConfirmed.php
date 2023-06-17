<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Participant;
use App\Participation;
use App\Event;
use Illuminate\Support\Str;

class ParticipantConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'ConfirmationRequest';
    public $participant;
    public $event;
    public $participation;
    public $fromName;
    public $subject;
    public $start_date;
    public $start_time;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, Participation $participation, $isInvitee, $fromName, $subject)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->participation = $participation;

        $this->fromName = $fromName;
        $this->subject = $subject;

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

        return $this->view('emails.participant-confirmed')
            ->from($event->id == 50 ? 'contacto@summit-agro.cl' : 'mailing@mail-venoot.com', $event->id == 50 ? 'SUMMIT-AGRO Chile' : $this->fromName)
            ->subject($this->subject ?? "Confirmación {$this->event->name}");
    }
}
