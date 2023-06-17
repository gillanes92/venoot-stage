<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Event;
use App\Ticket;
use App\Participant;
use App\Participation;
use App\ParticipantOrder;
use App\ParticipantTicket;
use App\Template;
use App\Jobs\SendMail;
use App\Mail\ParticipantQrs;
use App\Mail\ParticipantTemplate;
use Illuminate\Http\Request;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\PayerInfo;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Carbon;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Redirect;
use URL;
use Keygen;

require_once(dirname(__FILE__) . "/../../../nusoap/nusoap.php");

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PayPalController extends Controller
{
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret']
        ));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    private function changeStaticBody(Event $event, $body)
    {
        // https://venoot-stage.work/venoot-chat?uuid=611f24c3-73fe-4127-9be2-5fb204968a42

        $body = str_replace("{URL-LANDING}", url("events/{$event->id}/landing"), $body);
        $body = str_replace("{URL-CHAT}", url("venoot-chat"), $body);
        $body = str_replace("{TITULO}", $event->name, $body);
        $body = str_replace("{LOCACION}", $event->location, $body);

        return $body;
    }

    private function changeDynamicBody(Participant $participant, Participation $participation, ParticipantTicket $participant_ticket, $body)
    {
        $body = str_replace("{NOMBRE}", $participant->data['name'], $body);
        $body = str_replace("{APELLIDO}", $participant->data['lastname'], $body);

        $body = str_replace("{TICKET-NUMERO}", $participant_ticket->uuid, $body);
        $body = str_replace("{QR-NUMERO}", $participation->uuid, $body);
        $body = str_replace("{QR-EVENTO}", url("qr/{$participation->uuid}"), $body);
        $body = str_replace("{CONFIRMAR-SI}", url("confirms/{$participation->uuid}"), $body);
        $body = str_replace("{CONFIRMAR-NO}", url("unconfirms/{$participation->uuid}"), $body);
        $body = str_replace("{INVITAR}", url("invitees/{$participation->uuid}"), $body);

        if (str_contains($body, '{QR-CODE}')) {
            Storage::disk('public')->put('public/' . $participation->uuid . '.png', QrCode::format('png')->size(300)->generate($participation->uuid));
            $body = str_replace("{QR-CODE}", Storage::url($participation->uuid . '.png'), $body);
        }

        return $body;
    }

    public function payWithpaypal(Event $event, Request $request)
    {
        $request->validate([
            'participants' => 'required|array',
            'participants.*' => 'required|array',
            'participants.*.name' => 'required|string|max:50',
            'participants.*.lastname' => 'required|string|max:50',
            'participants.*.email' => 'required|email',
            'participants.*.primary' => 'required|boolean',
            'return_url' => 'required|url',

            'tickets' => 'required|array',
            'tickets.*.amount' => 'required|integer|min:0',
            'tickets.*.id' => 'required|exists:tickets,id',

            'tickets.*.discount' => 'nullable',
            'tickets.*.discount.id' => 'sometimes|integer|exists:discounts,id'
        ]);

        // Checking spaces
        foreach ($request->tickets as $ticket) {
            $ticket = (object) $ticket;
            $ticket_db = Ticket::find($ticket->id);
            if ($ticket->amount > $ticket_db->left) {
                return response()->json(['uuid' => null], 200);
            }
        }

        // // Add participant to event
        $primary = null;
        $participants = array();

        foreach ($request->participants as $possible_participant) {
            $participant = $event->participants()->whereJsonContains('data->email', $possible_participant['email'])->first();

            if (!$participant) {
                $participant = $event->profile->database->participants()->create(['data' => $possible_participant]);
            } else {
                $participant->update(['data' => collect($possible_participant)->forget('primary')]);
            }

            array_push($participants, $participant);

            $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
            $participation->confirmed_sent_at = Carbon::now();
            $participation->confirmed_at = Carbon::now();
            $participation->confirmed_status = true;
            $participation->save();

            if ($possible_participant['primary']) {
                $primary = $participant;
            }
        }

        $participants = collect($participants);
        if (!$primary) {
            $primary = $participants->first();
        }

        // Creating Order
        $buyData = collect($request->tickets)->filter(function ($value, $key) {
            return $value['amount'] > 0;
        });
        $number = Keygen::numeric(14)->prefix(mt_rand(1, 9))->generate();
        while (ParticipantOrder::where('number', $number)->count() > 0) {
            $number = Keygen::numeric(14)->prefix(mt_rand(1, 9))->generate();
        }

        $payment = new Payment();

        $participant_order = ParticipantOrder::create([
            'number' => $number,
            'event_id' => $event->id,
            'participant_id' => $primary->id,
            'buyData' => $buyData->map(function ($ticket) {
                return collect($ticket)->only(['id', 'amount', 'discount'])->all();
            }),
            'return_url' => $request->return_url,
        ]);

        // Creating Tickets
        $total_index = 0;
        foreach ($request->tickets as $ticket) {
            $ticket = (object) $ticket;
            $ticket_db = Ticket::find($ticket->id);
            if ($ticket->amount <= $ticket_db->left) {
                for ($i = 0; $i < $ticket->amount; ++$i) {
                    ParticipantTicket::create([
                        'ticket_id' => $ticket->id,
                        'participant_id' => $event->personalize_tickets ? $participants[$total_index]->id : $primary->id,
                        'discount_id' => $ticket->discount['id'] ?? null,
                        'participant_order_id' => $participant_order->id,
                        'uuid' => (string) Str::uuid(),
                    ]);

                    ++$total_index;
                }
            }
        }

        $buyOrder = $participant_order->number;
        $amountToBePaid =  $participant_order->total;


        // Is ticket Free?
        if ($amountToBePaid == 0) {
            $participant_order->status = 1;
            $participant_order->save();

            $participation = Participation::where('event_id', $participant_order->event->id)->where('participant_id', $participant_order->participant->id)->first();
            Mail::to($participant_order->participant->data['email'])
                ->queue(new ParticipantQrs($participant_order->participant, $participant_order->event, $participation, $participant_order));

            return response()->json(['paypal_url' => 'free'], 200);
        }

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($amountToBePaid);
        $redirect_urls = new RedirectUrls();
        /** Specify return URL **/
        $redirect_urls->setReturnUrl(URL::route('status'))
            ->setCancelUrl(URL::route('status'));

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setInvoiceNumber($buyOrder);

        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        $payment->create($this->_api_context);
        $participant_order->webpay_token = $payment->getId(); //getToken TRY THIS TONIght
        $participant_order->save();

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        if (isset($redirect_url)) {
            return response()->json(['paypal_url' => $redirect_url], 200);
        }

        return response()->json(['paypal_url' => null], 419);
    }

    public function getPaymentStatus(Request $request)
    {
        if (empty($request->paymentId)) {
            abort(403);
        }

        if (empty($request->PayerID) || empty($request->token)) {
            return redirect($url);
        }

        $participant_order = ParticipantOrder::where(['webpay_token' => $request->paymentId])->first();

        if (!$participant_order) {
            abort(403);
        }


        $payment = Payment::get($request->paymentId, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);
        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            $participant_order->response_code = 0;
            $param = 'venoot_ticket_status=paid';
            $participant_order->status = 1;

            $uuids = [];
            $tickets = [];
            $names = [];
            $remote = [];

            if ($participant_order->event->personalize_tickets) {

                foreach ($participant_order->participant_tickets as $ticket) {
                    $participation = Participation::where('event_id', $participant_order->event->id)->where('participant_id', $ticket->participant->id)->first();

                    array_push($names, $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname']);
                    array_push($tickets, $ticket->ticket->name);
                    array_push($uuids, $participation->uuid);
                    array_push($remote, !in_array($ticket->ticket->id, [1]));

                    $template = null;
                    if ($participation->event->id == 40) {
                        if (in_array($ticket->ticket->id, [1, 2])) {
                            $template = Template::find(6);
                        } else {
                            $template = Template::find(7);
                        }
                    } else {
                        $template = $participation->event->qrcode_template ?: Template::find(3);
                    }

                    $body = $this->changeStaticBody($participation->event, $template->content);
                    $current_body = $this->changeDynamicBody($participation->participant, $participation, $ticket, $body);
                    $subject = $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname'] . ' TICKET - ' . $participation->event->name;
                    $converter = new CssToInlineStyles();
                    $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));

                    SendMail::dispatch($participation->participant->data['email'], new ParticipantTemplate($participation->event, $participation, null, $participation->event->from_subject ?? $subject, 'QrEventLink', $content));
                }
            } else {
                $participation = Participation::where('event_id', $participant_order->event->id)->where('participant_id', $participant_order->participant->id)->first();
                Mail::to($participant_order->participant->data['email'])
                    ->queue(new ParticipantQrs($participant_order->participant, $participant_order->event, $participation, $participant_order));
            }
        } else {
            $participant_order->status = 3;
            $param = 'venoot_ticket_status=rejected';
        }

        $participant_order->save();

        $query = parse_url($participant_order->return_url, PHP_URL_QUERY);
        $names = join(',', $names);
        $tickets = join(',', $tickets);
        $uuids = join(',', $uuids);
        $remote = join(',', $remote);
        if ($query) {
            $url = $participant_order->return_url . '&' . $param . '&names=' . $names . '&tickets=' . $tickets . '&uuids=' . $uuids . "&remote=" . $remote;
        } else {
            $url = $participant_order->return_url . '?' . $param . '&names=' . $names . '&tickets=' . $tickets . '&uuids=' . $uuids . "&remote=" . $remote;
        }

        return redirect($url);
    }
}
