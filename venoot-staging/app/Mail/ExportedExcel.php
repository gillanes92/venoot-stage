<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Participant;
use App\Participation;
use App\Event;
use Illuminate\Support\Str;

class ExportedExcel extends Mailable
{
    use Queueable, SerializesModels;

    public $category = 'ExcelRequest';
    public $event;
    public $fromName;
    public $subject;
    public $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event, $fromName, $subject, $attachment)
    {
        $this->event = $event;
        $this->fromName = $fromName;
        $this->subject = $subject;
        $this->attachment = $attachment;
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
            $headers->addTextHeader('category', $this->category);
            $headers->addTextHeader('uuid', $uuid);
            $headers->addTextHeader('X-Mailgun-Variables', json_encode(
                [
                    "env"  => config('app.env'),
                    "uuid" => $uuid
                ]
            ));
        });

        return $this->view('emails.exported-excel')
            ->from('mailing@mail-venoot.com', $this->fromName)
            ->subject($this->subject ?? "Reporte {$this->event->name}");
    }
}
