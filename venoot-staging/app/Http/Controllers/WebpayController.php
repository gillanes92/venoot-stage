<?php

namespace App\Http\Controllers;

use App\WebpayClientOrder;
use App\Event;
use App\Estimate;
use App\Template;
use App\ParticipantOrder;
use App\ParticipantTicket;
use App\Participant;
use App\Participation;
use App\Ticket;
use App\Jobs\SendMail;
use App\Mail\ParticipantQrs;
use App\Mail\ParticipantTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\CalendarLinks\Link;
use Keygen;

require_once __DIR__ . '/../../../nusoap/nusoap.php';

class WebpayController extends Controller
{
    private function changeStaticBody(Event $event, $body)
    {
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

    public function __construct()
    {
        if (app()->environment('production')) {
            WebpayPlus::configureForProduction(config('services.transbank.webpay_plus_cc'), config('services.transbank.webpay_plus_api_key'));
        } else {
            WebpayPlus::configureForTesting();
        }
    }

    public function loadOrderTotals(Event $event)
    {
        $user = Auth::user();
        if (!$user->hasRole('super-admin')) {
            return response()->json(null, 403);
        }

        $orders = $event->paid_participant_orders()->get()->map(function ($order, $key) {
            return ['id' => $order->id, 'number' => $order->number, 'facto' => $order->facto_link, 'total' => $order->total, 'fecha' => $order->updated_at];
        });

        return response()->json($orders, 200);
    }

    public function payment(Event $event)
    {
        $user = Auth::user();
        $guard = Auth::guard();
        if ($user->hasPermissionTo('pay')) {

            // Obtenemos Precio
            $amount = 0;
            foreach (Estimate::whereIn('id', $event->estimate->unpaid_ids)->get() as $estimate) {
                $amount += $estimate->net_total;
            }

            if ($amount <= 0) {
                return response()->json(['error' => 'paid'], 200);
            }

            // Agregamos una transaccion con un numero de orden aleatorio
            $client_order = $user->webpayClientOrders()->create([
                'estimates' => $event->estimate->unpaid_ids,
            ]);
            $client_order->order = "13" . str_pad((string) $client_order->id, 11, "0", STR_PAD_LEFT);
            $client_order->save();

            // Debes además, registrar las URLs a las cuales volverá el cliente durante y después del flujo de Webpay
            $response = (new Transaction)->create($client_order->order, Hash::make($guard->tokenById($user->id)), $amount, url('/') . '/api/webpay/response');

            // Asociar transaccion con url y token!
            $client_order->response_url = $response->getUrl();
            $client_order->token = $response->getToken();
            $client_order->save();

            return response()->json(['webpay' => ['url' => $response->getUrl(), 'token' => $response->getToken()]], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function response(Request $request)
    {
        // Recibir respuesta
        $result = [];

        // Comprobar respuesta
        if (isset($request->token_ws)) {
            $webpayClientOrder = WebpayClientOrder::where('token', $request->token_ws)->first();
            try {
                $result = (new Transaction)->commit($request->token_ws);
            } catch (\Exception $e) {
                return redirect('events');
            }

            $webpayClientOrder->response_code = $result->responseCode;
            $webpayClientOrder->save();
        } else if (isset($request->TBK_TOKEN)) {
            try {
                $result = (new Transaction)->status($request->TBK_TOKEN);

                if ($result->responseCode == null && $result->status == "INITIALIZED") {
                    $webpayClientOrder = WebpayClientOrder::where('token', $request->TBK_TOKEN)->first();
                    $webpayClientOrder->response_code = -100;
                    $webpayClientOrder->save();
                } else {
                    return redirect('events');
                }
            } catch (\Exception $e) {
                return redirect('events');
            }
        }

        if ($webpayClientOrder) {
            if ($webpayClientOrder->response_code == 0) {
                foreach (Estimate::whereIn('id', $webpayClientOrder->estimates)->get() as $estimate) {
                    $estimate->webpay_client_order_id = $webpayClientOrder->id;
                    $estimate->status = 1;
                    $estimate->save();
                }
                return redirect('paid?order=' . $webpayClientOrder->order . '&status=paid');
            } else if ($webpayClientOrder->response_code == -100) {
                return redirect('paid?order=' . $webpayClientOrder->order . '&status=annulled');
            } else {
                return redirect('paid?order=' . $webpayClientOrder->order . '&status=rejected');
            }
        } else {
            return redirect('events');
        }
    }

    public function buyTickets(Event $event, Request $request)
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

        // Add participants to event
        $primary = null;
        $participants = array();

        foreach ($request->participants as $possible_participant) {
            $participant = $event->participants()->whereJsonContains('data->email', $possible_participant['email'])->first();

            if (!$participant) {
                $participant = $event->profile->database->participants()->create(['data' => $possible_participant]);
            } else {
                $participant->data = array_merge($participant->data, $possible_participant);
                $participant->save();
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
        $buyData = collect($request->tickets);

        $number = Keygen::numeric(14)->prefix(mt_rand(1, 9))->generate();
        while (ParticipantOrder::where('number', $number)->count() > 0) {
            $number = Keygen::numeric(14)->prefix(mt_rand(1, 9))->generate();
        }

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

        // Agregamos una transaccion con un numero de orden aleatorio
        $buyOrder = $participant_order->number;

        //Obtenemos Precio
        $amount = $participant_order->total;

        //If it is free we jump to the end
        if ($amount == 0) {
            $participant_order->status = 1;
            $participant_order->save();

            $uuids = [];
            $tickets = [];
            $names = [];
            $remote = [];

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

            $query = parse_url($participant_order->return_url, PHP_URL_QUERY);
            $names = join(',', $names);
            $tickets = join(',', $tickets);
            $uuids = join(',', $uuids);
            $remote = join(',', $remote);
            if ($query) {
                $url = $participant_order->return_url . '&venoot_ticket_status=paid' . '&names=' . $names . '&tickets=' . $tickets . '&uuids=' . $uuids . "&remote=" . $remote;
            } else {
                $url = $participant_order->return_url . '?venoot_ticket_status=paid' . '&names=' . $names . '&tickets=' . $tickets . '&uuids=' . $uuids . "&remote=" . $remote;
            }
            
            return response()->json(['webpay' => ['type' => 'free', 'url' => $url]], 200);
        }

        // Debes además, registrar las URLs a las cuales volverá el cliente durante y después del flujo de Webpay
        $response = (new Transaction)->create($participant_order->number, Hash::make($participant_order->created_at . $participant_order->id), $amount, url('/') . "/api/events/{$event->id}/webpay/response");

        // Asociar transaccion con el token!
        $participant_order->response_url = $response->getUrl();
        $participant_order->webpay_token = $response->getToken();
        $participant_order->save();

        return response()->json(['webpay' => ['url' => $response->getUrl(), 'token' => $response->getToken()]], 200);
    }

    // public function responseTickets(Request $request)
    // {
    //     // Recibir respuesta
    //     $result = $this->plus->getTransactionResult($request->token_ws);

    //     // Comprobar respuesta
    //     $participant_order = ParticipantOrder::where(['webpay_token' => $request->token_ws, 'number' => $result->buyOrder])->first();

    //     if ($participant_order) {
    //         $participant_order->response_code = $result->detailOutput->responseCode;
    //         $participant_order->save();
    //         $this->plus->acknowledgeTransaction();
    //         echo RedirectorHelper::redirectBackNormal($result->urlRedirection);
    //     } else {
    //         abort(403);
    //     }
    // }

    public function responseTickets(Request $request)
    {
        // Recibir respuesta
        $result = [];

         // Comprobar respuesta
        if (isset($request->token_ws)) {
            $participant_order = ParticipantOrder::where('webpay_token', $request->token_ws)->first();
            try {
                $result = (new Transaction)->commit($request->token_ws);
            } catch (\Exception $e) {
                return redirect('events');
            }
            $participant_order->response_code = $result->responseCode;
            $participant_order->save();
        } else if (isset($request->TBK_TOKEN)) {
            try {
                $result = (new Transaction)->status($request->TBK_TOKEN);

                if ($result->responseCode == null && $result->status == "INITIALIZED") {
                    $participant_order = ParticipantOrder::where('webpay_token', $request->TBK_TOKEN)->first();
                    $participant_order->response_code = -100;
                    $participant_order->save();
                } else {
                    return redirect('events');
                }
            } catch (\Exception $e) {
                return redirect('events');
            }
        }

        $uuids = [];
        $tickets = [];
        $names = [];
        $remote = [];
        if ($participant_order->response_code == 0) {
            $param = 'venoot_ticket_status=paid';
            $participant_order->status = 1;

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

            if (!$participant_order->facto_link && $participation->event->id != 40) {
                $pdf_link = $this->factoEmit($participant_order);
                $participant_order->facto_link = $pdf_link;
            }
        } else if ($participant_order->response_code == -100) {
            $param = 'venoot_ticket_status=annulled';
            $participant_order->status = 2;
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

    private function factoEmit(ParticipantOrder $participant_order)
    {
        $buyData = $participant_order->buyData;
        $detalles = [];
        $total = 0;

        foreach ($buyData as $ticket) {
            if ($ticket['amount'] == 0) continue;

            $ticketDetails = Ticket::find($ticket['id']);
            $total += $ticketDetails->price * $ticket['amount'];
            array_push(
                $detalles,
                array(
                    "cantidad" => $ticket['amount'],
                    "glosa" => $ticketDetails->name,
                    "monto_unitario" => $ticketDetails->price,
                    "exento_afecto" => 1
                )
            );
        }

        if (config('app.env') == 'production') {
            $client = new \nusoap_client("https://conexion.facto.cl/documento.php?wsdl");
            $client->setCredentials("76.688.252-8/3651", "24603f9180709b2c64fdeb70d3e8c7b6", "basic");
        } else {
            $client = new \nusoap_client("https://conexion.facto.cl/simulacion.php?wsdl");
            $client->setCredentials("76.688.252-8/3651", "24603f9180709b2c64fdeb70d3e8c7b6", "basic");
        }

        $param = array(
            "documento" => array(
                "opciones" => array(
                    "valores_brutos" => "1"
                ),
                "encabezado" => array(
                    "tipo_dte" => 39,
                    "fecha_emision" => Carbon::now()->toDateString(),
                    "condiciones_pago" => 0,
                    "receptor_email" => $participant_order->participant->data['email'],
                ),
                "detalles" => array("detalle" => $detalles),
                "totales" => array(
                    "total_final" => $total
                )
            )
        );

        // dd($param);

        $response = $client->call("emitirDocumento", $param);
        // dd($response);

        $err = $client->getError();
        if ($err) {
            return '<p><b>Error: ' . $err . '</b></p>';
        } else {
            return $response['enlaces']['dte_pdf'];
        }
    }
}
