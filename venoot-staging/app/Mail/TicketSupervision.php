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

class TicketSupervision extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'SupervisedTicket';
    public $participant;
    public $event;
    public $fields;
    public $database;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, Database $database)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->fields = $database->fields;
        $this->database = $database;
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

        return $this->view('emails.ticket-supervisor')
            ->from('mailing@mail-venoot.com', 'Venoot')
            ->subject("Supervisión {$this->event->name}: {$this->participant->name} {$this->participant->lastname}");
    }
}
