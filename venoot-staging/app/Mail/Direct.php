<?php

namespace App\Mail;

use App\Event;
use App\Participant;
use App\Participation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;


class Direct extends Mailable
{
    use Queueable, SerializesModels;
    public $category = "TestEmail";
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body)
    {
        $this->body = $body;
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
            $headers->addTextHeader('event-id', null);
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

        return $this->from('mailing@mail-venoot.com', 'Venoot')
            ->view('emails.direct')
            ->subject("Test - Plantilla de Correo");
    }
}
