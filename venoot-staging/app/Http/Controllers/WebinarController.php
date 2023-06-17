<?php

namespace App\Http\Controllers;

use App\Event;
use App\Estimate;
use App\Reminder;
use App\Participation;
use App\Mail\WebinarInvitation;
use App\Mail\WebinarHost;
use App\Mail\WebinarParticipant;
use App\Mail\WebinarSpeaker;
use App\Jobs\ReminderMail;
use App\Participant;
use Firebase\JWT\JWT;
use Illuminate\Bus\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WebinarController extends Controller
{
    public function getCompanyWebinars()
    {
        $events = Auth::user()->company->events()->where('is_webinar', true)->with('polls')->with('estimate')->with('reminders')->orderBy('updated_at', 'desc')->get();
        return response()->json($events, 200);
    }

    public function getWebinarData()
    {
        $events = Auth::user()->company->events()->where('is_webinar', true)->without('activities')->without('sponsors')->without('landing')->without('estimate')->without('profile')->without('tickets')->without('actors')->without('collectors')->without('registerDevices')->get()->each(function ($items) {
            $items->append(['registered_participants_count']);
        });

        $package = [];
        switch (Auth::user()->package) {
            case 'free':
                $package['participants'] = 100;
                $package['mails'] = 200;
                break;

            case 'free':
                $package['participants'] = 250;
                $package['mails'] = 1250;
                break;

            case 'free':
                $package['participants'] = 500;
                $package['mails'] = 2500;
                break;

            case 'free':
                $package['participants'] = 1000;
                $package['mails'] = 5000;
                break;
        }

        return response()->json(['webinars' => $events, 'package' => $package], 200);
    }

    public function storeCompanyWebinar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasPermissionTo('edit event')) {

            $event = Event::create([
                'category' => 'Webinar',
                'banner' => $request->banner,
                'logo_event' => $request->logo_event,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'profile_id' => $request->profile_id,
                'company_id' => $user->company->id,
                'email' => $request->email,
                'is_webinar' => true,
            ]);

            $event = $this->createVonageSessionId($event);
            $event->save();

            // Mail::to($user->email)->queue(new WebinarHost($event));

            $activity = $event->activities()->create([
                'name' => 'Sala 1',
                'location' => 'Online',
                'date' => $request->start_date,
                'start_time' => $request->start_time,
                'end_time' => $request->start_time,
                'description' => "",
                'show_in_landing' => false,
            ]);

            $client = new \GuzzleHttp\Client();
            $url = 'https://www.zohoapis.com/crm/v2/functions/guardaevento/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604';

            $data = [
                "id_evento" =>  $event->id,
                "tipo" =>  "Webinar",
                "id_database" =>  $event->profile->database_id,
                "id_customer" =>  $user->customer_id,
                "nombre" =>  $event->name,
                "descripcion" =>  $event->description,
                "fecha_evento" =>  $event->start_date . " " . $event->start_time,
                "direccion" => $event->location,
                "correo" => $event->email,
                "salas" =>  [
                    0 => [
                        "id_sala" =>  $activity->id,
                        "nombre" =>  $activity->name,
                        "fecha" =>  $event->start_date . " " . $event->start_time,
                    ]
                ]
            ];

            try {
                $client->post(
                    $url,
                    [
                        'multipart' => [
                            [
                                'name' => 'data',
                                'contents' => json_encode($data)
                            ]
                        ]
                    ]
                );
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::debug($error);
            }

            return response()->json(['webinar' => $event], 200);
        }
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasPermissionTo('edit event')) {
            return response()->json(null, 403);
        }

        $estimate = Estimate::create([
            'company_id' => $user->company->id,
            'landing' => true,
            'confirmation_form' => $request->estimate['confirmation_form'],
            'mailings' => true,
            'mailings_quantity' => 500,
            'polls' => true,
            'polls_quantity' => 10,
            'register_keys' => false,
            'register_keys_quantity' => 0,
            'ticket_sale' => $request->estimate['ticket_sale'],
            'free_tickets' => false,
        ]);

        $event = $estimate->event()->create([
            'email' => $user->email,
            'category' => 'Webinar',
            'banner' => $request->banner,
            'logo_event' => $request->logo_event,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'profile_id' => $request->profile_id,
            'company_id' => $user->company->id,
            'email' => $request->email,
            'timezone' => $request->timezone,
            'is_webinar' => true,

            'webpay_accepted' => $request->webpay_accepted ?? false,
            'paypal_accepted' => $request->paypal_accepted ?? false,
            'personalize_tickets' => $request->personalize_tickets ?? false,
        ]);

        $event->refresh();

        // $client = new \GuzzleHttp\Client();
        // $url =  'http://167.172.225.137:5080/LiveApp/rest/v2/broadcasts/create'; //'https://venoot-media.work:5443/LiveApp/rest/v2/broadcasts/create';

        try {
            //   $response = $client->post($url);
            //   $data = json_decode($response->getBody()->getContents());
            //   $event->ams_streamId = $data->streamId;
            //   //rtmp://venoot-media.work/LiveApp/aRNFwm89XBDO1676897996314 ams!
            $event->landing()->create([
                'which' => -1,
                'video_url' => 'https://venoot-media.work:5443/LiveApp/play.html?name=' . $event->ams_streamId
            ]);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::debug($error);
            //   $event->landing->delete();
            //   $event->delete();
            //   $estimate->delete();
            return response()->json(['error' => $error], 501);
        }

        $event->published = true;
        $event->host_webinar_link = Str::uuid();
        $event->presenter_webinar_link = Str::uuid();
        $event = $this->createVonageSessionId($event);
        $event->save();

        // Mail::to($user->email)->queue(new WebinarHost($event));

        $activity = $event->activities()->create([
            'name' => 'Sala 1',
            'location' => 'Online',
            'date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_time' => $request->start_time,
            'description' => "",
            'show_in_landing' => false,
        ]);

        $client = new \GuzzleHttp\Client();
        $url = 'https://www.zohoapis.com/crm/v2/functions/guardaevento/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604';

        $data = [
            "id_evento" =>  $event->id,
            "tipo" =>  "Webinar",
            "id_database" =>  $event->profile->database_id,
            "id_customer" =>  $user->customer_id,
            "nombre" =>  $event->name,
            "descripcion" =>  $event->description,
            "fecha_evento" =>  $event->start_date . " " . $event->start_time,
            "direccion" => $event->location,
            "correo" => $event->email,
            "salas" =>  [
                0 => [
                    "id_sala" =>  $activity->id,
                    "nombre" =>  $activity->name,
                    "fecha" =>  $event->start_date . " " . $event->start_time,
                ]
            ]
        ];

        try {
            $client->post(
                $url,
                [
                    'multipart' => [
                        [
                            'name' => 'data',
                            'contents' => json_encode($data)
                        ]
                    ]
                ]
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::debug($error);
        }

        $event->landing;
        return response()->json(['webinar' => $event], 200);
    }

    public function user(Request $request)
    {
        $event = Event::find($request->eventID);
        switch ($request->mode) {
            case 'host':
                if ($request->token == $event->host_webinar_link) {
                    return response()->json(['mode' => 'host', 'status' => 'true', 'eventName' => $event->name], 200);
                }
                break;

            case 'presenter':
                if ($request->token == $event->presenter_webinar_link) {
                    return response()->json(['mode' => 'host', 'status' => 'true', 'eventName' => $event->name], 200);
                }
                break;

            case 'participant':
                // && in_array($request->token, $event->participations->pluck('uuid')->toArray())
                // if (true) {
                return response()->json(['mode' => 'host', 'status' => 'true', 'eventName' => $event->name, 'ams' => $event->ams_streamId], 200);
                // }        
                break;

            default:
                return response()->json(null, 403);
        }

        return response()->json(['webinar' => 'active'], 200);
    }

    public function update(Event $event, Request $request)
    {
        try {

            $event->category = $request->category;
            $event->banner = $request->banner;
            $event->logo_event = $request->logo_event;
            $event->name = $request->name;
            $event->start_date = $request->start_date;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
            $event->timezone = $request->timezone;

            $event->save();
            $event->refresh();
            $event->start_time = Carbon::parse($event->start_time)->format('H:i');
            $event->end_time = Carbon::parse($event->end_time)->format('H:i');
            return response()->json(['webinar' => $event], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json(['webinar' => $event], 200);
    }

    public function updateLanding(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:events,id',
            'landing' => 'nullable',
            'landing.which' => 'required_if:landing,true|integer|in:-1,3,4,5',
            'confirmation_form' => 'required|boolean',
            'ticket_sale' => 'required|boolean',
            'webpay_accepted' => 'required_if:ticket_sale,true|boolean',
            'paypal_accepted' => 'required_if:ticket_sale,true|boolean',
            'personalize_tickets' => 'required_if:ticket_sale,true|boolean',
            'tickets' => 'required_if:ticket_sale,true|array',
            'primary' => 'required|string',
            'secondary' => 'required|string',
            'terciary' => 'required|string',
        ]);

        // try {
        $webinar = Event::find($request->id);
        $webinar->load('landing');

        $webinar->estimate->confirmation_form = $request->confirmation_form;
        $webinar->estimate->ticket_sale = $request->ticket_sale;

        if ($request->ticket_sale) {
            $webinar->webpay_accepted = $request->webpay_accepted ?? false;
            $webinar->paypal_accepted = $request->paypal_accepted ?? false;
            $webinar->personalize_tickets = $request->personalize_tickets ?? false;
        }

        $webinar->primary = $request->primary;
        $webinar->secondary = $request->secondary;
        $webinar->terciary = $request->terciary;

        $webinar->estimate->save();
        $webinar->save();

        if ($request->landing) {
            if (!$webinar->landing) {
                $webinar->landing()->create(['which' => $request->landing['which']]);
                $webinar->load('landing');
            }

            $webinar->landing->which = $request->landing['which'];
            $webinar->landing->save();
        } else {
            if ($webinar->landing) {
                $webinar->landing->delete();
            }
        }

        if ($request->ticket_sale && $request->tickets) {
            $ticket_ids =  [];
            foreach ($request->tickets as $ticket) {
                $ticket['id'] = $ticket['id'] ?? 0;
                $t = $webinar->tickets()->updateOrCreate(['id' => $ticket['id']], $ticket);
                $ticket_ids[] = $t->id;
            }
            $webinar->tickets()->whereNotIn('id', $ticket_ids)->delete();
            $webinar->load('tickets');
        }

        return response()->json(['webinar' => $webinar], 200);
    }

    public function syncActors(Request $request, Event $event)
    {
        $request->validate([
            'actors' => 'sometimes|array',
            'actors.*' => 'integer|exists:actors,id',
        ]);

        $event->actors()->sync($request->actors);
        $event->load('actors');

        return response()->json(['actors' => $event->actors], 200);
    }

    public function syncSponsors(Request $request, Event $event)
    {
        $request->validate([
            'sponsors' => 'sometimes|array',
            'sponsors.*' => 'integer|exists:sponsors,id',
        ]);

        $event->sponsors()->sync($request->sponsors);
        $event->load('sponsors');

        return response()->json(['sponsors' => $event->sponsors], 200);
    }

    public function syncPolls(Request $request, Event $event)
    {
        $request->validate([
            'polls' => 'sometimes|array',
            'polls.*' => 'integer|exists:polls,id',
        ]);

        $event->polls()->sync($request->polls);
        $event->load('polls');

        return response()->json(['polls' => $event->polls], 200);
    }

    public function reminder(Request $request, Event $event)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
        ]);

        $job = new ReminderMail($event);
        $date = Carbon::createFromFormat('Y-m-d H:i', $request->date . " " . $request->time);
        $job->delay($date);
        $job_id = app(Dispatcher::class)->dispatch($job);

        $event->reminders()->create(['job_id' => $job_id, 'scheduled_for' => $date]);

        return response()->json(['reminders' => $event->reminders], 200);
    }

    public function sendParticipantMail(Event $event)
    {
        foreach ($event->profile->participants as $participant) {
            Mail::to($participant->data['email'])->queue(new WebinarParticipant);
        }

        return response()->json(null, 204);
    }

    public function sendSpeakerMail(Event $event, Request $request)
    {
        $request->validate([
            'emails' => 'required',
            'emails.*' => 'email'
        ]);

        foreach ($request->emails as $email) {
            Mail::to($email)->queue(new WebinarSpeaker($event, $request));
        }

        return response()->json(null, 204);
    }

    public function getMeetings()
    {
        if (!Gate::allows('only-super-admin')) {
            return response()->json(['error' => 'unauthorized'], 403);
        }

        $response = $this->access_token();

        $client = new \GuzzleHttp\Client();
        $url = "https://api.archiebot.com/api/widgets";

        if ($response) {
            $json_response = json_decode($response->getBody()->getContents());
            $access_token = $json_response->accessToken->access_token;

            $headers = [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/vnd.archiebot.v1+json',
            ];

            try {
                $response = $client->get($url, ['headers' => $headers]);
            } catch (\Exception $e) {
                $response = null;
            }

            if ($response) {
                return response()->json([json_decode($response->getBody()->getContents())], 200);
            } else {
                return response()->json(null, 503);
            }
        } else {
            return response()->json(null, 401);
        }
    }

    public function createMeeting(Request $request)
    {
        if (!Gate::allows('only-super-admin')) {
            return response()->json(['error' => 'unauthorized'], 403);
        }

        $request->validate([
            'meetingName' => 'sometimes|nullable|string|min:8',
        ]);

        $response = $this->access_token();

        if ($response) {
            $json_response = json_decode($response->getBody()->getContents());
            $access_token = $json_response->accessToken->access_token;

            $client = new \GuzzleHttp\Client();
            $url = "https://api.archiebot.com/api/widgets";

            $headers = [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/vnd.archiebot.v1+json',
            ];

            $form_params = [
                'name' => $request->meetingName,
                'type' => 'permanent',
                'timezone' => 'America/Santiago',
                'manual_confirm_registrants' => 1,
                'custom_name' => 'custom_name',
            ];

            try {
                $response = $client->post($url, ['headers' => $headers, 'form_params' => $form_params]);
            } catch (\Exception $e) {
                $response = null;
            }

            if ($response) {
                return response()->json([json_decode($response->getBody()->getContents())], 200);
            } else {
                return response()->json(null, 503);
            }
        } else {
            return response()->json(null, 401);
        }
    }

    public function close(Request $request)
    {

        if (!Gate::allows('only-super-admin')) {
            return response()->json(['error' => 'unauthorized'], 403);
        }

        $request->validate([
            'id' => 'required|integer|min:8',
        ]);

        $response = $this->access_token();

        if ($response) {
            $json_response = json_decode($response->getBody()->getContents());
            $access_token = $json_response->accessToken->access_token;

            $client = new \GuzzleHttp\Client();
            $url = "https://api.archiebot.com/api/widgets/" . $request->id;

            $headers = [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/vnd.archiebot.v1+json',
            ];

            try {
                $response = $client->delete($url, ['headers' => $headers]);
            } catch (\Exception $e) {
                $response = null;
            }

            if ($response) {
                return response()->json([json_decode($response->getBody()->getContents())], 200);
            } else {
                return response()->json(null, 503);
            }
        } else {
            return response()->json(null, 401);
        }
    }

    public function confirmationEmail(Event $event, Request $request)
    {
        if (config('app.env') == 'production' && !$event->canEmail()) {
            return response()->json(['error' => 'email_quota'], 406);
        }

        $request->validate([
            'fromName' => 'required|string',
            'subject' => 'required|string',
            'participants' => 'required|array',
            'participants.*' => 'exists:participants,id',
        ]);

        $participants = $event->profile->participants_collection
            ->whereDoesntHave('participations', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->orWhereHas('participations', function ($query) use ($event) {
                $query->where('event_id', $event->id)->whereNull('confirmed_sent_at');
            })
            ->with(['participations' => function ($query) use ($event) {
                $query->where('event_id', $event->id)->whereNull('confirmed_sent_at');
            }])
            ->get()
            ->whereIn('id', $request->participants);

        foreach ($participants as $participant) {
            $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
            $participation->confirmed_sent_at = Carbon::now();
            $participation->save();

            Mail::to($participant->data['email'])->queue(new WebinarInvitation($event, $participant, $participation, $request, $request->fromName, $request->subject));
        }

        return response()->json($participants->pluck('data')->map(function ($participant) {
            return collect($participant)->only('name', 'lastname', 'email');
        }), 200);
    }

    function latest()
    {
        $webinar = Event::all()->last();
        return response()->json(['webinar' => $webinar], 200);
    }

    function compile_data(Event $event, Request $request)
    {
        if ($request->token != 'ukD32xcBdqC4avXM') {
            return response()->json(['status' => 'Acess Key Error'], 403);
        }


        $z_7 = Participation::where('event_id', $event->id)->where('participant_id', 96697)->first()->zoho_id;
        $z_8 = Participation::where('event_id', $event->id)->where('participant_id', 96698)->first()->zoho_id;
        $z_9 = Participation::where('event_id', $event->id)->where('participant_id', 96699)->first()->zoho_id;

        $data = [
            "id_webinar" =>  185,
            "id_database" =>  $event->profile->database_id,
            "details" => [
                ['id_participant' => 96697, 'zoho_id' => $z_7, 'enter_time' => '2022-09-02 08:57:10', 'exit_time' => '2022-09-02 08:58:31', 'chat_participation' => False, 'question_to_host' => False, 'answer_host_question' => False],
                ['id_participant' => 96697, 'zoho_id' => $z_7, 'enter_time' => '2022-09-02 09:00:10', 'exit_time' => '2022-09-02 09:22:31', 'chat_participation' => True, 'question_to_host' => False, 'answer_host_question' => True],
                ['id_participant' => 96698, 'zoho_id' => $z_8, 'enter_time' => '2022-09-02 09:00:22', 'exit_time' => '2022-09-02 09:23:02', 'chat_participation' => False, 'question_to_host' => False, 'answer_host_question' => False],
                ['id_participant' => 96699, 'zoho_id' => $z_9, 'enter_time' => '2022-09-02 09:03:17', 'exit_time' => '2022-09-02 10:01:46', 'chat_participation' => True, 'question_to_host' => True, 'answer_host_question' => True],
                ['id_participant' => 96698, 'zoho_id' => $z_8, 'enter_time' => '2022-09-02 08:40:45', 'exit_time' => '2022-09-02 09:58:12', 'chat_participation' => True, 'question_to_host' => False, 'answer_host_question' => False],
                ['id_participant' => 96697, 'zoho_id' => $z_7, 'enter_time' => '2022-09-02 09:41:50', 'exit_time' => '2022-09-02 10:03:23', 'chat_participation' => False, 'question_to_host' => True, 'answer_host_question' => True],
            ]
        ];

        return response()->json([$data], 200);
    }

    public function venootWebinar()
    {
        return view('vonage');
    }

    public function emailRequest(Event $event, Request $request)
    {
        $email = 'cristobal.r.diaz@gmail.com';

        switch ($request->category) {
            case 'new_webinar':
                Mail::to($email)->queue(new WebinarHost($event));
                return response()->json(['mail_to' => $email], 200);
        }
    }

    public function confirmPositive(Event $event, $zoho_id)
    {
        $participation = $event->participations()->where('zoho_id', $zoho_id)->first();
        if ($participation) {
            $participation->confirmed_status = true;
            $participation->confirmed_at = Carbon::now()->toDateTimeString();
            $participation->save();

            return view('confirmation', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation, 'isInvitee' => false]);
        } else {
            abort(404);
        }
    }

    public function confirmNegative(Event $event, $zoho_id)
    {
        $participation = $event->participations()->where('zoho_id', $zoho_id)->first();
        if ($participation) {

            $former_confirmation_status = $participation->confirmed_status;
            $participation->confirmed_status = false;
            $participation->confirmed_at = Carbon::now()->toDateTimeString();
            $participation->save();

            if ($former_confirmation_status == true) {
                return view('unconfirmation', ['event' => $participation->event, 'participant' => $participation->participant]);
            } else {
                return view('confirmation-done', ['event' => $participation->event, 'participant' => $participation->participant]);
            }
        } else {
            abort(404);
        }
    }

    public function qrImage(Event $event, $zoho_id)
    {
        $participation = $event->participations()->where('zoho_id', $zoho_id)->first();
        if ($participation) {

            if (!Storage::disk('public')->exists('public/' . $participation->uuid . '.png')) {
                Storage::disk('public')->put('public/' . $participation->uuid . '.png', QrCode::format('png')->size(300)->generate($participation->uuid));
            }

            return response()->json(['url' => Storage::url($participation->uuid . '.png')], 200);
        } else {
            return response()->json(null, 404);
        }
    }

    public function tempVenootWebinarLanding(Request $request)
    {
        if ($request->has('token') && $request->has('mode')) {
            $participation = Participation::where('zoho_id', $request->input('token'))->first();

            if ($participation) {
                return view('webinar_temp', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation]);
            } else {
                return view('webinar_temp', ['participation' => null]);
            }
        }

        abort('404');
    }

    private function createVonageSessionId(Event $event)
    {
        if (!$event->vonage_session_id) {
            $API_KEY = config('vonage.api_key');
            $API_SECRET = config('vonage.api_secret');
            $apiObj = new OpenTok($API_KEY, $API_SECRET);
            $session = $apiObj->createSession(array('mediaMode' => MediaMode::ROUTED));
            $SESSION_ID = $session->getSessionId();
            $this->muteVonageSession($SESSION_ID);
            $event->vonage_session_id = $SESSION_ID;
        }

        return $event;
    }
}
