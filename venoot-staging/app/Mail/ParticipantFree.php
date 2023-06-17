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


class ParticipantFree extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'QrEventLink';
    public $participant;
    public $event;
    public $participation;
    public $isInvitee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, Participation $participation, $isInvitee)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->participation = $participation;
        $this->isInvitee = $isInvitee;
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

        return $this->view('emails.participant-free')
            ->from('mailing@mail-venoot.com', $this->event->name)
            ->subject("Ticket de Cortesia - {$this->event->name}");
    }
}
