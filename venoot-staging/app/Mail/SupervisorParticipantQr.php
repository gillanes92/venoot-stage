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


class SupervisorParticipantQr extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'SupervisedQrEventLink';
    public $participant;
    public $event;
    public $participation;
    public $isInvitee;
    public $fromName;
    public $subject;
    public $start_date;
    public $start_time;
    public $status;
    public $keyed_field;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, Participation $participation, $isInvitee, $status, $keyed_field)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->participation = $participation;
        $this->isInvitee = $isInvitee;

        $this->start_date = Carbon::parse($this->event->start_date)->locale('es');
        $this->start_time = Carbon::parse($this->event->start_time)->locale('es');

        $this->status = $status;
        $this->keyed_field = $keyed_field;
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

        return $this->view('emails.supervisor-participant-qr')
            ->from('mailing@mail-venoot.com', $this->event->name)
            ->subject("Ticket " . $this->event->name);
    }
}
