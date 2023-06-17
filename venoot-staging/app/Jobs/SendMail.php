<?php

namespace App\Jobs;

use DateTime;
use App\ScheduledDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $mailable;
    protected $date;
    protected $scheduled_delivery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, Mailable $mailable, DateTime $date = null, ScheduledDelivery $scheduled_delivery = null)
    {
        $this->to = $to;
        $this->mailable = $mailable;
        $this->date = $date;
        $this->scheduled_delivery = $scheduled_delivery;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->date) {
            $job_id = Mail::selectConfig($this->to)->later($this->date, $this->mailable);

            $jobs = $this->scheduled_delivery->jobs;
            array_push($jobs, $job_id);
            $this->scheduled_delivery->jobs = $jobs;
            $this->scheduled_delivery->save();

        } else {
            Mail::selectConfig($this->to)->send($this->mailable);
        }
    }
}
