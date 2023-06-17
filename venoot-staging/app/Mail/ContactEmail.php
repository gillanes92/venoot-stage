<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'ContactEmail';
    public $name;
    public $email;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $subject, $body)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
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

        return $this->view('emails.contact', ['name' => $this->name, 'email' => $this->email, 'subject' => $this->subject, 'body' => $this->body])
            ->from('mailing@mail-venoot.com', $this->name)
            ->subject($this->subject);
    }
}
