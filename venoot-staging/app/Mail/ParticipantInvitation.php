<?php

namespace App\Mail;

use App\Participant;
use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Participation;
use Illuminate\Support\Str;

class ParticipantInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'Invitation';
    public $participant;
    public $event;
    public $participation;
    public $invitee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, Participation $participation, $invitee)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->participation = $participation;
        $this->invitee = $invitee;
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

        return $this->markdown('emails.participant-invitation')
            ->from('mailing@mail-venoot.com', $this->event->name)
            ->subject("Invitacion {$this->event->name}");
    }
}
