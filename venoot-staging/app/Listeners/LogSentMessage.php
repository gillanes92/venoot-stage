<?php

namespace App\Listeners;

// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Contracts\Queue\ShouldQueue;
use jdavidbakr\MailTracker\Events\EmailSentEvent;
use Illuminate\Support\Facades\Auth;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EmailSentEvent $event)
    {
        $sentEmail = $event->sent_email;
        $sentEmail->event_id = $sentEmail->getHeader('event-id');
        $sentEmail->participation_id = $sentEmail->getHeader('participation-id');
        $sentEmail->category = $sentEmail->getHeader('category');
        $sentEmail->uuid = $sentEmail->getHeader('uuid');

        if (Auth::check()) {
            $sentEmail->user_id = Auth::user()->id;
        }

        $sentEmail->save();
    }
}
