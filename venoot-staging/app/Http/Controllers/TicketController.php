<?php

namespace App\Http\Controllers;

use App\Event;
use App\Ticket;
use App\AcceptedUuid;
use App\Database;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Mail\TicketSupervision;
use App\Mail\TicketSupervisionAccepted;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function eventIndex(Event $event)
    {
        $tickets = $event->tickets()->with('database')->where('available', true)->get()->sortBy('id');

        foreach ($tickets as $ticket) {
            foreach ($ticket->discounts as $discount) {
                if ($discount->trigger == 'cupon') {
                    $discount->trigger_value = '';
                }
            }

            $ticket->discount = 0;
        }

        if ($ticket->protection == 'key') {
            $ticket->keyName = collect($ticket->database->fields)->filter(function($field) use($ticket) { return $field['key'] === $ticket->key ?: 'email'; })->first()['name'];
        }

        return response()->json([

            'event' => [
                'title' => $event->name,
                'personalize' => $event->personalize_tickets,
                'start_date' => $event->start_date,
                'start_time' => $event->start_time,
            ],

            'tickets' => $tickets->values(),
        ]);
    }

    public function companyIndex()
    {
        $company = Auth::user()->company;
        return response()->json(['tickets' => $company->tickets], 200);
    }

    public function ticketDiscounts(Ticket $ticket)
    {
        return response()->json(['discounts' => $ticket->discounts()->without('tickets')->get()], 200);
    }

    public function checkKey(Ticket $ticket, Request $request)
    {
        $request->validate([
            'key' => 'required|string'
        ]);

        if ($ticket->protection != 'key' || !$ticket->database || !$ticket->key) {
            return response()->json(['status' => false, 'reason' => 'mismatch'], 200);
        }

        $participant = $ticket->database->participants()->whereJsonContains('data->' . $ticket->key, $request->key)->first();

        return response()->json(['participant' => $participant], 200);
    }

    public function checkEmail(Ticket $ticket, Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        if ($ticket->protection != 'email' || !$ticket->database || !$ticket->email) {
            return response()->json(['status' => false], 200);
        }

        $data = $request->all();
        $data['uuid'] = (string) Str::uuid();
        $participant = $ticket->database->participants()->whereJsonContains('data->email', $request->email)->first();
        if (!$participant) {
            $participant = $ticket->database->participants()->create([
                'data' => $data
            ]);

            Mail::to($ticket->email)
                ->queue(new TicketSupervision($participant, $ticket->event, $ticket->database));

            return response()->json(null, 204);
        }

        return response()->json(null, 409);
    }

    public function acceptParticipant(Database $database, Event $event, $uuid)
    {
        $participant = $database->participants()->whereJsonContains('data->uuid', $uuid)->first();

        if ($participant) {
            AcceptedUuid::create(['uuid' => $uuid]);

            Mail::to($participant->data['email'])
                ->queue(new TicketSupervisionAccepted($participant, $event, 'accept'));

            return view('supervisor-desition', ['participant' => $participant, 'event' => $event, 'status' => 'accept']);
        }
    }

    public function rejectParticipant(Database $database, Event $event, $uuid)
    {
        $participant = $database->participants()->whereJsonContains('data->uuid', $uuid)->first();

        if ($participant) {
            Mail::to($participant->data['email'])
                ->queue(new TicketSupervisionAccepted($participant, $event, 'reject'));

            return view('supervisor-desition', ['participant' => $participant, 'event' => $event, 'status' => 'reject']);
        }
    }

    public function ticketsByEvent($id)
    {
        $user = Auth::user();

        $tickets =  Ticket::where('event_id', $id)->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function checkAccess($uuid)
    {
        $accepted = AcceptedUuid::where('uuid', $uuid)->first();

        if ($accepted) {
            return response()->json(null, 204);
        }

        return response()->json(null, 403);
    }
}
