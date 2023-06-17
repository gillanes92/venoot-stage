<?php

namespace App\Mail;

use App\ParticipantUser;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParticipantWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'Welcome';
    public $participant_user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ParticipantUser $participant_user, $password)
    {
        $this->participant_user = $participant_user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.participant-welcome')
            ->from('mailing@mail-venoot.com', "Venoot")
            ->subject("Bienvenido a Venoot!");
    }
}
