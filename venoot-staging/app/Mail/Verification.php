<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

use App\User;

class Verification extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'VerificationLink';
    public $verifyUrl;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verifyUrl, User $user)
    {
        $this->verifyUrl = $verifyUrl;
        $this->user = $user;
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

        return $this->view('emails.verification', ['verifyUrl' => $this->verifyUrl, 'user' => $this->user]);
    }
}
