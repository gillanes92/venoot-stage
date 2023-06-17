<?php

namespace App\Jobs;

use DateTime;
use App\Event;
use App\Mail\WebinarReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;

class ReminderMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $participations = $this->event->profiledParticipations->where('confirmed_status', true)->with('participant')->get();
        foreach ($participations as $participation) {
            Mail::selectConfig($participation->participant->data['email'])->send(new WebinarReminder($this->event, $participation->participant, $participation));
        }
    }
}
