<?php

namespace App\Mail;

use App\User;
use App\Participant;
use App\Participation;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;


class WebinarPasswordChange extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'WebinarPasswordChange';
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
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
        $user = $this->user;

        // $this->withSwiftMessage(function ($message) use ($user, $uuid) {
        //   $headers = $message->getHeaders();
        //   $headers->addTextHeader('user-id', $user->id);
        //   $headers->addTextHeader('uuid', $uuid);
        //   $headers->addTextHeader('X-Mailgun-Variables', json_encode(
        //     [
        //       "env"  => config('app.env'),
        //       "uuid" => $uuid
        //     ]
        //   ));
        // });

        return $this->view('emails.webinar-password-change')
            ->subject('Venoot Password Change');
    }
}
