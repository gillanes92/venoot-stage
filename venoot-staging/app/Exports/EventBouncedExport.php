<?php

namespace App\Exports;

use App\Event;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class EventBouncedExport implements FromView
{
    use Exportable;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $bounced_emails = $this->event->sent_emails()->where('bounced', true)->get();

        return view('exports.event_bounced', [
            'event' => $this->event, 'bounced_emails' => $bounced_emails, 'now' => Carbon::now()
        ]);
    }
}
