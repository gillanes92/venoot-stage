<?php

namespace App\Mail;

use App\Event;
use App\Participant;
use App\Participation;
use App\Poll;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;


class ParticipantPoll extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'PollEventLink';
    public $participant;
    public $event;
    public $participation;
    public $poll;
    public $fromName;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, Participation $participation, Poll $poll, $fromName, $subject)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->participation = $participation;
        $this->poll = $poll;

        $this->fromName = $fromName ?? $event->name;
        $this->subject = $subject ?? "Ticket " . $this->event->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $event = $this->event;
        // $participant = $this->participant;
        $participation = $this->participation;
        // $poll = $this->poll;
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

        return $this->view('emails.participant-poll')
            ->from('mailing@mail-venoot.com', $this->fromName)
            ->subject($this->subject);
    }
}
