<?php

namespace App\Mail;

use App\Database;
use App\Event;
use App\Participant;
use App\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class TicketSupervisionAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'SupervisedTicketAccepted';
    public $participant;
    public $event;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, $status)
    {
        $this->category = $status ? 'SupervisedTicketAccepted' : 'SupervisedTicketRejected';
        $this->participant = $participant;
        $this->event = $event;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $event = $this->event;
        $uuid = Str::uuid();

        $this->withSwiftMessage(function ($message) use ($event, $uuid) {
            $headers = $message->getHeaders();
            $headers->addTextHeader('event-id', $event->id);
            $headers->addTextHeader('participation-id', null);
            $headers->addTextHeader('category', $this->category);
            $headers->addTextHeader('uuid', $uuid);
            $headers->addTextHeader('X-Mailgun-Variables', json_encode(
                [
                    "env"  => config('app.env'),
                    "uuid" => $uuid
                ]
            ));
        });

        return $this->view('emails.ticket-supervisor-accepted')
            ->from('mailing@mail-venoot.com', 'Venoot')
            ->subject("Supervisión {$this->event->name} - {$this->participant->name} {$this->participant->lastname}");
    }
}
