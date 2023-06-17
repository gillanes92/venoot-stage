<?php

namespace App\Http\Controllers;

use App\Event;
use App\Estimate;
use App\Participant;
use App\Participation;
use App\LandingVisit;
use App\ParticipantOrder;
use App\ParticipantTicket;
use App\Poll;
use App\Answer;
use App\Ticket;
use App\Meeting;
use App\Template;
use App\ParticipantUser;
use App\ScheduledDelivery;
use App\Job;
use App\Mail\ParticipantInvitation;
use App\Mail\ParticipantConfirmed;
use App\Mail\ParticipantQr;
use App\Mail\ParticipantPoll;
use App\Mail\ParticipantTemplate;
use App\Mail\Supervision;
use App\Mail\SupervisorParticipantQr;
use App\Mail\ParticipantFree;
use App\Mail\ParticipantReconfirmed;
use App\Mail\ParticipantWelcome;
use App\Mail\ContactEmail;
use App\Mail\ExportedExcel;
use App\Mail\WebinarConfirmation;
use App\Jobs\SendMail;
use App\Jobs\SendMassMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Http\Requests\InviteesRequest;
use App\Http\Resources\AppDataResource;
use Keygen\Keygen;
use Share;
use Tracker;
use Spatie\CalendarLinks\Link;
use App\Exports\EventExport;
use App\Exports\EventConfirmationExport;

use App\Exports\EventSimpleConfirmationExport;
use App\Exports\EventSimpleSalesExport;
use App\Exports\EventSimpleConsolidatedExport;

use App\Exports\EventPollExport;
use App\Exports\EventTicketSaleExport;
use App\Exports\EventAppExport;
use App\Exports\EventBouncedExport;
use App\Exports\EventExportVideoParticipants;
use App\Events\StreamChatMessageEvent;
use App\Events\QuestionRequestEvent;
use App\Sending;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

use PDF;

use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    private function changeStaticBody(Event $event, $body)
    {
        $body = str_replace("{URL-LANDING}", url("events/{$event->id}/landing"), $body);
        $body = str_replace("{URL-CHAT}", url("venoot-chat"), $body);
        $body = str_replace("{TITULO}", $event->name, $body);
        $body = str_replace("{LOCACION}", $event->location, $body);
        $body = str_replace("{CONTACT_EMAIL}", $event->email, $body);

        return $body;
    }

    private function changeDynamicBody(Participant $participant, Participation $participation, $body, Template $template = null)
    {
        $body = str_replace("{NOMBRE}", $participant->data['name'], $body);
        $body = str_replace("{APELLIDO}", $participant->data['lastname'], $body);
        if (isset($participant->data['vocativo'])) {
            $body = str_replace("{ALOALA}", $participant->data['vocativo'], $body);
        }

        $body = str_replace("{QR-NUMERO}", $participation->uuid, $body);
        $body = str_replace("{QR-EVENTO}", url("qr/{$participation->uuid}"), $body);
        $body = str_replace("{CONFIRMAR-SI}", url("confirms/{$participation->uuid}"), $body);
        $body = str_replace("{CONFIRMAR-NO}", url("unconfirms/{$participation->uuid}"), $body);
        $body = str_replace("{INVITAR}", url("invitees/{$participation->uuid}"), $body);

        if (str_contains($body, '{QR-CODE}')) {
            Storage::disk('public')->put('public/' . $participation->uuid . '.png', QrCode::format('png')->size(300)->generate($participation->uuid));
            $body = str_replace("{QR-CODE}", Storage::url($participation->uuid . '.png'), $body);
        }

        if ($template) {
            $body = str_replace("{PDF}", url("templates/{$template->id}/{$participation->uuid}"), $body);
        }

        return $body;
    }

    public function home()
    {
        $estimates = Auth::user()->company->estimates()->orderBy('updated_at', 'desc')->get()->where('has_unpaid_ids', true)->take(5)->flatten();
        $events = Auth::user()->company->events()->orderBy('updated_at', 'desc')->get()->take(5);

        return response()->json(['estimates' => $estimates, 'events' => $events], 200);
    }

    public function database(Event $event)
    {
        return response()->json(['database' => $event->profile->database], 200);
    }

    /**
     * Redirections
     *
     */
    public function prettyUrl($prettyUrl, Request $request)
    {
        $event = Event::where('pretty_url', $prettyUrl)->first();
        if ($event) {
            return $this->landing($event, $request);
        } else {
            return abort(404);
        }
    }

    public function euroRedirect(Request $request)
    {
        return redirect('https://economiacircular.eurochile.cl?' . http_build_query($request->query()), 301);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show event')) {
            \Log::info($user->company->id);

            $events = DB::table('events')
                ->selectRaw("events.*, profiles.database_id, estimates.ticket_sale as ticket_sale, estimates.landing as estimate_landing, estimates.confirmation_form, estimates.ticket_sale,
                                  estimates.polls_quantity, estimates.app as estimate_app,
                                  CASE WHEN estimates.status = 0 THEN 'editing' WHEN events.published THEN 'published' ELSE 'pending' END as status")
                ->join('estimates', 'estimates.id', '=', 'events.estimate_id')
                ->join('profiles', 'profiles.id', '=', 'events.profile_id')
                ->leftJoin('landings', 'landings.event_id', '=', 'events.id')
                ->where('events.company_id', $user->company->id)->get();

            $events = $events->map(function ($item, $key) {
                $item->start_time = Carbon::parse($item->start_time)->format('H:i');
                $item->end_time = Carbon::parse($item->end_time)->format('H:i');
                return $item;
            });

            return response()->json(['events' => $events], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function paginatedIndex(Request $request)
    {
        $request->validate([
            'starting_index' =>  'required|integer',
            'amount' => 'required|integer'
        ]);

        $user = Auth::user();
        $events = $user->company->events()->where('is_webinar', false)->orderBy('updated_at', 'desc')->get()->map(function ($item, $key) {
            $item->start_time = Carbon::parse($item->start_time)->format('H:i');
            $item->end_time = Carbon::parse($item->end_time)->format('H:i');
            return $item;
        });

        if ($events->count() > $request->starting_index) {

            if ($events->count() >= $request->starting_index + $request->amount) {
                return response()->json(['events' => $events->slice($request->starting_index, $request->amount)]);
            } else {
                return response()->json(['events' => $events->slice($request->starting_index)]);
            }
        }

        return response()->json(['events' => null]);
    }

    public function eventsOnlyIndex()
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show event')) {
            \Log::info($user->company->id);

            $events = DB::table('events')
                ->selectRaw("events.*, profiles.database_id, estimates.ticket_sale as ticket_sale, estimates.landing as estimate_landing, estimates.confirmation_form, estimates.ticket_sale,
                               estimates.polls_quantity, estimates.app as estimate_app,
                               CASE WHEN estimates.status = 0 THEN 'editing' WHEN events.published THEN 'published' ELSE 'pending' END as status")
                ->join('estimates', 'estimates.id', '=', 'events.estimate_id')
                ->join('profiles', 'profiles.id', '=', 'events.profile_id')
                ->leftJoin('landings', 'landings.event_id', '=', 'events.id')
                ->where('events.company_id', $user->company->id)->where('is_webinar', false)->get();

            $events = $events->map(function ($item, $key) {
                $item->start_time = Carbon::parse($item->start_time)->format('H:i');
                $item->end_time = Carbon::parse($item->end_time)->format('H:i');
                return $item;
            });

            return response()->json(['events' => $events], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('edit event')) {
            $estimate = Estimate::find($request->estimate_id);

            if (!$estimate) {
                return response()->json(null, 500);
            }

            $event = $estimate->event()->create([
                'category' => $request->category,
                'banner' => $request->banner,
                'logo_event' => $request->logo_event,
                'name' => $request->name,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
                'end_date' => $request->end_date,
                'end_time' => $request->end_time,
                'description' => $request->description,
                'twitter' => $request->twitter,
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'google_plus' => $request->google_plus,
                'web' => $request->web,
                'linkedin' => $request->linkedin,
                'profile_id' => $request->profile_id,
                'estimate_id' => $request->estimate_id,
                'company_id' => $user->company->id,
                'invitees' => $request->invitees,
                'email' => $request->email,
                'secure_video' => $request->secure_video,
                'secure_video_extras' => $request->secure_video_extras,
                'video_category' => $request->video_category,
                'shared_chat' => $request->shared_chat,
                'one_to_one_chat' => $request->one_to_one_chat,
                'speaker_chat' => $request->speaker_chat,
                'quota' => $request->quota,
                'over_quota' => $request->over_quota,
                'apology' => $request->apology,
                'extra_images' => $request->extra_images,
                'has_confirmation' => $request->has_confirmation,
                'confirmation_key' => $request->confirmation_key,
                'producer' => $request->producer,
                'producer_password' => $request->producer_password,
                'pretty_url' => $request->pretty_url,
                'webpay_accepted' => $request->webpay_accepted,
                'from_subject' => $request->from_subject,
                'from_name' => $request->from_name,
                'from_email' => $request->from_email,
                'personalize_tickets' => $request->personalize_tickets
            ]);

            try {

                if ($request->actors) {
                    $event->actors()->sync(collect($request->actors)->pluck('id')->toArray());
                }

                if ($request->actitives) {
                    foreach ($request->actitives as $activity) {
                        $a = $event->activity()->create([
                            'name' => $activity->name,
                            'location' => $activity->location,
                            'date' => $activity->date,
                            'start_time' => $activity->start_time,
                            'end_time' => $activity->end_time,
                            'description' => $activity->description,
                            'video_url' => $activity->video_url,
                            'extra_link' => $activity->extra_link,
                            'show_in_landing' => $activity->show_in_landing,
                            'order' => $activity->order,
                            'style' => $activity->style,
                        ]);

                        if ($activity['actors']) {
                            $actors = collect($activity['actors']);
                            $a->actors()->sync($actors->pluck('id')->toArray());
                        }
                    }
                }

                if ($request->sponsors) {
                    $event->sponsors()->sync(collect($request->sponsors)->pluck('id')->toArray());
                }

                if ($estimate->landing && $request->landing) {
                    $event->landing()->create($request->landing);
                    $event->load('landing');
                }

                if ($request->tickets) {
                    foreach ($request->tickets as $ticket) {
                        $ticket = (object) $ticket;
                        $event->tickets()->create([
                            'name' => $ticket->name,
                            'description' => $ticket->description,
                            'quantity' => $ticket->quantity,
                            'price' => $ticket->price,
                            'available' => $ticket->available,
                            'protection' => $ticket->protection,
                            'database_id' => $ticket->database_id,
                            'key' => $ticket->key,
                            'email' => $ticket->email,
                            'protection_quantity' => $ticket->protection_quantity,
                            'currency' => $ticket->currency,
                        ]);
                    }
                }

                $event = Event::find($event->id);
                $event->start_time = Carbon::parse($event->start_time)->format('H:i');
                $event->end_time = Carbon::parse($event->end_time)->format('H:i');
                return response()->json(['event' => $event->makeVisible('producer_password')], 200);
            } catch (\Exception $e) {
                return response()->json((array) $e, 500);
            }
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function publish(Event $event, Request $request)
    {
        if ($event->estimate->status) {
            $event->published = true;
            $event->save();

            return response()->json(null, 204);
        }

        return response()->json(['error' => 'payment_required'], 402);
    }

    public function updateExtraImages(Event $event, Request $request)
    {
        try {
            $event->extra_images = $request->extra_images;
            $event->save();
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }

        return response()->json(['extra_images' => $event->extra_images], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $event = Event::find($id);

        if (Gate::allows('show-event', $event)) {
            return response()->json(['event' => $event], 200);
        } else {
            return response()->json(null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {
        if (Gate::allows('edit-event', $event)) {
            $event->category = $request->category;
            $event->banner = $request->banner;
            $event->logo_event = $request->logo_event;
            $event->name = $request->name;
            $event->location = $request->location;
            $event->start_date = $request->start_date;
            $event->start_time = $request->start_time;
            $event->end_date = $request->end_date;
            $event->end_time = $request->end_time;
            $event->description = $request->description;
            $event->twitter = $request->twitter;
            $event->facebook = $request->facebook;
            $event->instagram = $request->instagram;
            $event->google_plus = $request->google_plus;
            $event->web = $request->web;
            $event->linkedin = $request->linkedin;
            $event->invitees = $request->invitees;
            $event->email = $request->email;
            $event->quota = $request->quota;
            $event->over_quota = $request->over_quota;
            $event->apology = $request->apology;
            $event->extra_images = $request->extra_images;
            $event->has_confirmation = $request->has_confirmation;
            $event->confirmation_key = $request->confirmation_key;
            $event->producer = $request->producer;
            $event->producer_password = $request->producer_password;

            if ($request->actors) {
                $event->actors()->sync(collect($request->actors)->pluck('id')->toArray());
            }

            $activity_ids =  [];
            foreach ($request->activities as $activity) {
                $activity['id'] = $activity['id'] ?? 0;
                $a = $event->activities()->updateOrCreate(['id' => $activity['id']], $activity);
                $activity_ids[] = $a->id;

                if ($activity['actors']) {
                    $actors = collect($activity['actors']);
                    $a->actors()->sync($actors->pluck('id')->toArray());
                }
            }
            $event->activities()->whereNotIn('id', $activity_ids)->delete();

            \Log::info("sponsors");
            \Log::info($request->sponsors);

            if ($request->sponsors) {
                $event->sponsors()->sync(collect($request->sponsors)->pluck('id')->toArray());
            }

            \Log::info("landing");
            \Log::info($request->landing);
            \Log::info($event);

            if ($request->landing && $event->estimate->landing) {
                $landing = $request->landing;
                $landing['id'] = $landing['id'] ?? 0;
                $event->landing()->updateOrCreate(['id' => $landing['id']], $landing);
            }

            if ($request->tickets) {
                $ticket_ids =  [];
                foreach ($request->tickets as $ticket) {
                    $ticket['id'] = $ticket['id'] ?? 0;
                    $t = $event->tickets()->updateOrCreate(['id' => $ticket['id']], $ticket);
                    $ticket_ids[] = $t->id;
                }
                $event->tickets()->whereNotIn('id', $ticket_ids)->delete();
            }

            try {
                $event->save();
                $event->refresh();
                $event->start_time = Carbon::parse($event->start_time)->format('H:i');
                $event->end_time = Carbon::parse($event->end_time)->format('H:i');
                return response()->json(['event' => $event->makeVisible('producer_password')], 200);
            } catch (\Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $company = $event->company;
        if (Gate::allows('edit-event', $event)) {
            $event->delete();
            return response()->json(['events' => $company->events], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function addSponsors(Request $request, Event $event)
    {
        if (Gate::allows('edit-event', $event)) {
            $validated = $request->validate([
                'sponsors' => 'required|array',
                'sponsors.*' => 'integer|exists:sponsors,id',
            ]);

            if ($validated) {
                $event->sponsors()->sync($request->sponsors);
                return response()->json(['events' => $event->company->events], 200);
            } else {
                return response()->json(null, 500);
            }
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function landing(Event $event, Request $request)
    {
        if ($event->published == false) {
            return redirect('/');
        }

        $visitor = Tracker::currentSession();
        $env = config('app.env');

        ## Create or Update Visit Log
        $visit = LandingVisit::where('uuid', $visitor->uuid)->first();
        if ($visit) {
            $visit->update([
                'service' => optional($visitor->referer)->host,
                'device' => optional($visitor->device)->kind,
            ]);
        } else {
            if ($visitor->uuid) {
                $event->landing_visits()->create([
                    'uuid' => $visitor->uuid,
                    'service' => optional($visitor->referer)->host,
                    'device' => optional($visitor->device)->kind,
                ]);
            }
        }

        ## DATE FORMATS
        $geoip = geoip($request->ip());

        $localized_start_time = Carbon::parse($event->start_time, $event->timezone)->setTimezone($geoip->timezone);
        $localized_end_time = Carbon::parse($event->end_time, $event->timezone)->setTimezone($geoip->timezone);

        $start_link_date = $event->start_date . ' ' . $localized_start_time->format('H:i:s');

        ## Links to calendar
        if ($event->is_webinar) {
            $end_link_date = $event->start_date . ' ' . $localized_end_time->format('H:i:s');
        } else {
            $end_link_date = $event->end_date . ' ' . $localized_end_time->format('H:i:s');
        }

        $event->start_date = Carbon::parse($event->start_date);
        $event->end_date = Carbon::parse($event->end_date);
        $event->start_time = $localized_start_time;
        $event->end_time = $localized_end_time;
        $link = Link::create($event->name, Carbon::createFromFormat('Y-m-d H:i:s', $start_link_date), Carbon::createFromFormat('Y-m-d H:i:s', $end_link_date))
            ->description($event->category == 'Webinar' ? '<a href="' . url('/venoot-chat?uuid=!!replace!!') . '">To Webinar</a>' : $event->name)
            ->address($event->location ?: "Online");

        ## ACTORS UNIQUE COLLECTION
        $actors = collect([]);
        foreach ($event->activities as $activity) {
            foreach ($activity->actors as $actor) {
                $actors->push($actor);
            }
        }
        $actors = $actors->merge($event->actors)->unique('id');

        ## GROUPING ACTIVITIES
        $activities_by_date = $event->activities()->where('show_in_landing', true)->get()->sortByDesc('order')->sortBy('start_time')->sortBy('date')->groupBy('date');
        foreach ($activities_by_date as $date => $activities) {
            foreach ($activities as $activity) {
                $activity->carbon_date = Carbon::parse($activity->date);
                $activity->start_time = Carbon::parse($activity->start_time, 'America/Santiago')->setTimezone($geoip->timezone);
                $activity->end_time = Carbon::parse($activity->end_time, 'America/Santiago')->setTimezone($geoip->timezone);
            }
        }

        ## Invitee
        $invitee = $request->query('invitee');

        if ($event->landing) {

            switch ($event->landing->which) {
                case 0:
                    $landing = 'landing-default';
                    break;

                case 1:
                    $landing = 'landing-black';
                    break;

                case 2:
                    $landing = 'landing-panama';
                    break;

                case 3:
                    $landing = 'landing-webinar';
                    break;

                case 4:
                    $landing = 'landing-webinar2';
                    break;

                case 5:
                    $landing = 'landing-webinar3';
                    break;

                case 10:
                    $landing = 'landing-direct';
                    break;

                case 11:
                    $landing = 'landing-brazil';
                    break;

                default:
                    abort(404);
                    break;
            }

            return view($landing, ['event' => $event, 'actors' => $actors, 'activitiesByDate' => $activities_by_date, 'invitee' => $invitee, 'link' => $link, 'env' => $env, 'editor' => false, 'link_qr' => $link_qr]);
        } else {
            abort(404);
        }
    }

    public function landingEdit(Event $event, Request $request)
    {
        ## DATE FORMATS
        $event->start_date = Carbon::parse($event->start_date);
        $event->end_date = Carbon::parse($event->end_date);
        $event->start_time = Carbon::parse($event->start_time);
        $event->end_time = Carbon::parse($event->end_time);

        ## Links to calendar
        $link = Link::create($event->name, $event->start_date, $event->end_date)
            ->description($event->description)
            ->address($event->location);

        ## ACTORS UNIQUE COLLECTION
        $actors = collect([]);
        foreach ($event->activities as $activity) {
            foreach ($activity->actors as $actor) {
                $actors->push($actor);
            }
        }
        $actors = $actors->merge($event->actors)->unique('id');

        ## GROUPING ACTIVITIES
        $activities_by_date = $event->activities->sortBy('start_time')->sortBy('date')->groupBy('date');

        ## Invitee
        $invitee = $request->query('invitee');

        if ($event->landing) {

            switch ($event->landing->which) {
                    // case 1:
                    //     $landing = 'landing-black';
                    //     break;

                    // case 2:
                    //     $landing = 'landing-panama';
                    //     break;

                default:
                    $landing = 'landing-default';
                    break;
            }

            return view($landing, ['event' => $event, 'actors' => $actors, 'activitiesByDate' => $activities_by_date, 'invitee' => $invitee, 'link' => $link, 'editor' => true]);
        } else {
            abort(404);
        }
    }

    public function invite(Event $event)
    {
        ## CASTING DATES AND TIMES
        $event->start_date = Carbon::parse($event->start_date);
        $event->end_date = Carbon::parse($event->end_date);
        $event->start_time = Carbon::parse($event->start_time);
        $event->end_time = Carbon::parse($event->end_time);

        foreach ($event->profile->participants as $participant) {

            $participation = Participation::firstOrCreate(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
            $participation->invitation_sent_at = Carbon::now();
            $participation->save();

            if (true || $participant->allow_mailing && !$participant->blocked && (Auth::user()->company->allow_mailings || (Auth::user()->company->allow_verified_mailings && $participant->verified))) {
                Mail::to($participant->data['email'])
                    // ->from($event->mail, $event->name)
                    ->queue(new ParticipantInvitation($participant, $event, $participation, false));
            }
        }
        return response()->json(null, 204);
    }

    public function confirmFromForm(Request $request)
    {
        $event = Event::find($request->event_id);
        if (!$event->over_quota && $event->quota !== null && $event->participations()->where('confirmed_status', true)->count() >= $event->quota) {
            return response()->json(['type' => 'danger', 'message' => $event->apology], 200);
        }

        $participant = $event->profile->participants_collection->whereJsonContains('data->email', $request['email'])->first();

        if (($event->id == 2 || $event->id == 151) && !$participant) {
            return response()->json(['type' => 'error', 'message' => 'Este email no esta habilitado.'], 401);
        }

        unset($request->event_id);

        $data = [];
        foreach ($request->all() as $key => $value) {
            try {
                $data[$key] = trim(json_decode($value)->name);
            } catch (\Exception $e) {
                $data[$key] = trim($value);
            }
        }

        if (!$participant) {
            $participant = Participant::create([
                'data' => $data,
                'database_id' => $event->profile->database->id,
            ]);
        } else {
            $participant->update([
                'data' => $data,
            ]);
        }

        $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);

        if (!$event->has_confirmation) {
            $participation->confirmed_at = Carbon::now();
            $participation->confirmed_status = true;
        }
        $participation->save();

        if ($event->id == 107 && $participant && $participation) {
            return response()->json(['type' => 'success', 'message' => 'Su trabajo ha sido recibido con éxito.'], 200);
        }

        if ($participant && $participation) {
            if ($event->is_webinar) {
                // Log::debug($email);
                Mail::to($participant->data['email'])->queue(new WebinarConfirmation($participation->event, $participation->participant, $participation, $request));
                // $this->createParticipantUserIfNew($participation->participant);

            } else {
                $template = $participation->event->qrcode_template ?? Template::find(3);
                $body = $this->changeStaticBody($participation->event, $template->content);
                $current_body = $this->changeDynamicBody($participation->participant, $participation, $body);
                $subject = $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname'] . ' QR - ' . $participation->event->name;
                $email = $participant->data['email'];

                $isInvitee = false;
                if ($event->invitees > 0) {
                    $isInvitee = $event->participations->pluck('invitees')->flatten()->contains($email);
                }

                if ($event->id != 112) {
                    if ($event->has_confirmation) {
                        if (true || $participant->allow_mailing && !$participant->blocked && ($event->company->allow_mailings || ($event->company->allow_verified_mailings && $participant->verified))) {
                            Mail::to($event->email)
                                ->queue(new Supervision($participant, $event, $participation, $isInvitee));
                        }
                    } else {
                        $converter = new CssToInlineStyles();
                        $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));
                        if (true || $participant->allow_mailing && !$participant->blocked && ($event->company->allow_mailings || ($event->company->allow_verified_mailings && $participant->verified))) {

                            SendMail::dispatch($participant->data['email'], new ParticipantTemplate($participation->event, $participation, null, $event->from_subject ?? $subject, 'QrEventLink', $content));

                            $this->createParticipantUserIfNew($participation->participant);
                        }
                    }
                }
            }

            $geoip = geoip($request->ip());

            $localized_start_time = Carbon::parse($event->start_time, $event->timezone)->setTimezone($geoip->timezone);
            $localized_end_time = Carbon::parse($event->end_time, $event->timezone)->setTimezone($geoip->timezone);

            $start_link_date = $event->start_date . ' ' . $localized_start_time->format('H:i:s');

            ## Links to calendar
            if ($event->is_webinar) {
                $end_link_date = $event->start_date . ' ' . $localized_end_time->format('H:i:s');
            } else {
                $end_link_date = $event->end_date . ' ' . $localized_end_time->format('H:i:s');
            }

            $qr = "/qr/" . $participation->uuid;

            $link_qr = Link::create($event->name, Carbon::createFromFormat('Y-m-d H:i:s', $start_link_date), Carbon::createFromFormat('Y-m-d H:i:s', $end_link_date))
                ->description($event->category == 'Webinar' ? '<a href="' . url('/venoot-chat?uuid=' . $participation->uuid) . '">To Webinar</a>' : '<a href="' . url($qr) . '">Ir a la entrada</a>')
                ->address($event->location ?: "Online");

            return response()->json(['type' => 'success', 'message' => 'Su confirmación ha sido recibida con éxito.', 'uuid' => $participation->uuid, 'ics' => $link_qr->ics()], 200);
        } else {
            return response()->json(['type' => 'danger ', 'message' => 'No se pudo confirmar asistencia. Por favor intente de nuevo mas tarde.'], 200);
        }
    }

    public function supervisorChange(Participation $participation, $choice_index)
    {
        $event = $participation->event;
        $participation->confirmed_at = Carbon::now();
        $participation->confirmed_status = true;
        $participation->save();

        $keyed_field = array_values(array_filter($event->profile->database->fields, function ($field) use ($event) {
            return $field['key'] == $event->confirmation_key;
        }))[0];

        $participant = $participation->participant;
        $data = $participant->data;
        $data[$event->confirmation_key] = $keyed_field['choices'][$choice_index];
        $participant->data = $data;
        $participant->save();

        Mail::to($event->email)
            ->queue(new SupervisorParticipantQr($participation->participant, $participation->event, $participation, $participation->event->invitees > 0, 'accept', $keyed_field));

        return view('supervisor-desition', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation, 'status' => 'accept']);
    }

    public function supervisorAccept(Participation $participation)
    {
        $event = $participation->event;
        // if (!$event->canEmail()) {
        //     return response()->json(['error' => 'email_quota'], 406)
        // }

        $participation->confirmed_at = Carbon::now();
        $participation->confirmed_status = true;
        $participation->save();

        $keyed_field = array_values(array_filter($event->profile->database->fields, function ($field) use ($event) {
            return $field['key'] == $event->confirmation_key;
        }))[0];

        Mail::to($event->email)
            ->queue(new SupervisorParticipantQr($participation->participant, $participation->event, $participation, $participation->event->invitees > 0, 'accept', $keyed_field));

        return view('supervisor-desition', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation, 'status' => 'change']);
    }

    public function supervisorReject(Participation $participation)
    {
        $event = $participation->event;
        // if (!$event->canEmail()) {
        //     return response()->json(['error' => 'email_quota'], 406)
        // }

        $participation->confirmed_at = Carbon::now();
        $participation->confirmed_status = false;
        $participation->save();

        $keyed_field = array_values(array_filter($event->profile->database->fields, function ($field) use ($event) {
            return $field['key'] == $event->confirmation_key;
        }))[0];

        $participant = $participation->participant;
        $data = $participant->data;
        $data[$event->confirmation_key] = null;
        $participant->data = $data;
        $participant->save();

        Mail::to($event->email)
            ->queue(new SupervisorParticipantQr($participation->participant, $participation->event, $participation, $participation->event->invitees > 0, 'reject', $keyed_field));

        return view('supervisor-desition', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation, 'status' => 'reject']);
    }


    public function testSuperviseMail()
    {
        $participation = Participation::find(1017);
        $fields = $participation->event->profile->database->fields;
        return view('emails.supervision', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation, 'fields' => $fields, 'status' => 'accept']);
    }

    public function confirms($uuid)
    {
        try {
            $participation = Participation::where('uuid', $uuid)->first();
            $template = $participation->event->qrcode_template ?? Template::find(3);
            $body = $this->changeStaticBody($participation->event, $template->content);

            $mail_send = $participation->confirmed_status;

            $isInvitee = false;
            if (!$mail_send) {
                $participation->confirmed_status = true;
                $participation->confirmed_at = Carbon::now();
                $participation->save();

                $email = $participation->participant->data['email'];

                if ($participation->event->invitees > 0) {
                    $isInvitee = $participation->event->participations->pluck('invitees')->flatten()->contains($email);
                }

                if ($participation->participant->allow_mailing) {

                    $current_body = $this->changeDynamicBody($participation->participant, $participation, $body);
                    $subject = $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname'] . ' QR - ' . $participation->event->name;
                    $converter = new CssToInlineStyles();
                    $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));

                    SendMail::dispatch($participation->participant->data['email'], new ParticipantTemplate($participation->event, $participation, null, $participation->event->from_subject ?? $subject, 'QrEventLink', $content));

                    $this->createParticipantUserIfNew($participation->participant);
                }
            }

            return view('confirmation', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation, 'isInvitee' => $isInvitee]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function invitees($uuid)
    {
        $participation = Participation::where('uuid', $uuid)->first();
        if ($participation) {
            return view('invitees', ['event' => $participation->event, 'participant' => $participation->participant, 'participation' => $participation]);
        } else {
            abort(404);
        }
    }

    public function sendInvitees(InviteesRequest $request)
    {
        $participation = Participation::where('uuid', $request->uuid)->first();
        if ($participation) {
            if (empty($participation->invitees)) {
                $participation->invitees = $request->emails;
                $participation->save();

                foreach ($participation->invitees as $invitee) {
                    SendMail::dispatch($invitee, new ParticipantInvitation($participation->participant, $participation->event, $participation, $invitee));
                }

                return response()->json(['type' => 'success ', 'message' => 'Invitados ingresados. Hemos enviado un correo para la confirmación de estos. Muchas gracias.'], 200);
            } else {
                return response()->json(['type' => 'danger ', 'message' => 'Invitados ya han sido ingresados para este participante'], 200);
            }
        } else {
            return response()->json(['type' => 'danger ', 'message' => 'No se pudieron confirmar invitados. Por favor intente de nuevo mas tarde.'], 200);
        }
    }

    public function unconfirms($uuid)
    {

        try {
            $participation = Participation::where('uuid', $uuid)->first();

            if (!$participation->confirmed_status) {
                $participation->confirmed_status = false;
                $participation->confirmed_at = Carbon::now();
                $participation->save();

                return view('unconfirmation', ['event' => $participation->event, 'participant' => $participation->participant]);
            } else {
                return view('confirmation-done', ['event' => $participation->event, 'participant' => $participation->participant]);
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function invitationBrazil($uuid)
    {
        try {
            $participation = Participation::where('uuid', $uuid)->first();
            return view('landing-invitation-brazil', ['participant' => $participation->participant, 'participation' => $participation]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function confirmationBrazil($uuid)
    {
        try {
            $participation = Participation::where('uuid', $uuid)->first();
            return view('landing-confirmation-brazil', ['participant' => $participation->participant, 'participation' => $participation]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function unconfirmsBrazil($uuid)
    {
        try {
            $participation = Participation::where('uuid', $uuid)->first();

            if (!$participation->confirmed_status) {
                $participation->confirmed_status = false;
                $participation->confirmed_at = Carbon::now();
                $participation->save();
            }

            return view('unconfirmation-brazil', ['participant' => $participation->participant]);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function participants(Event $event)
    {
        $participants = $event->profile->participants_collection->with([
            'participations' => function ($query) use ($event) {
                $query->where('event_id', $event->id);
            },
        ])->get();

        $result = [];
        foreach ($participants as $participant) {
            $participant = array_merge($participant, $participant['data']);
            unset($participant['data']);

            $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null;

            if ($participation) {
                $participant['invitation_sent_at'] = $participation->invitation_sent_at;
                $participant['confirmed_status'] = $participation->confirmed_status;
                $participant['confirmed_at'] = $participation->confirmed_at;
                $participant['registered_at'] = $participation->registered_at;
                $participant['invitees'] = $participation->invitees;
            } else {
                $participant['invitation_sent_at'] = null;
                $participant['confirmed_status'] = null;
                $participant['confirmed_at'] = null;
                $participant['registered_at'] = null;
                $participant['invitees'] = null;
            }
            array_push($result, $participant);
        }
        return response()->json(['participants' => $result], 200);
    }

    public function participantsSeries(Event $event)
    {
        \Log::info("participantsSeries");

        $participants = DB::table('participations')
            ->selectRaw("participations.id as participations_id,
                          participations.uuid,
                          sent_emails.sent_mail_date, sent_emails_t2.view_mail, sent_emails_t2.view_mail_date,
                          participations.invitation_sent_at, participations.confirmed_status, participations.confirmed_sent_at,
                          participations.confirmed_at, participations.registered_at,
                          participants.*")
            ->join('participants', 'participants.id', '=', 'participations.participant_id')
            ->leftJoin(
                DB::raw("
                      (SELECT participation_id, MIN(created_at) AS sent_mail_date
                       FROM sent_emails WHERE category in ('ConfirmationRequest', 'QrEventReconfirmed')
                       GROUP BY participation_id) AS sent_emails
                  "),
                'participations.id',
                '=',
                'sent_emails.participation_id'
            )
            ->leftJoin(
                DB::raw("
                      (SELECT participation_id, sum(opens) > 0 AS view_mail, MIN(updated_at) AS view_mail_date
                       FROM sent_emails WHERE category in ('ConfirmationRequest', 'QrEventReconfirmed')
                       GROUP BY participation_id) AS sent_emails_t2
                  "),
                'participations.id',
                '=',
                'sent_emails_t2.participation_id'
            )
            ->where('participations.event_id', $event->id)->get();

        $participants = $participants->map(function ($item, $key) {
            $item->data = json_decode($item->data, true);
            return $item;
        });

        $participants = json_decode(json_encode($participants), true);

        /*$participants = $event->profile->participants_collection->with([
      'participations' => function ($query) use ($event) {
        $query->where('event_id', $event->id);
      },
      'participations.sent_mails',
    ])->get()->toArray();*/

        /*$result = [];
    foreach ($participants as $participant) {
      //\Log::info(json_encode($participant));
      $participant = array_merge($participant, $participant['data']);

      unset($participant['data']);

      \Log::info(count($participant['participations']));
      $participation = count($participant['participations']) > 0 ? $participant['participations'][0] : null;
      unset($participant['participations']);

      if ($participation) {
        $participant_sent_mails = collect($participation['sent_mails']);
        $participant['sent_mail_date'] = $participant_sent_mails->whereIn('category', ['ConfirmationRequest', 'QrEventReconfirmed'])->min('created_at');
        $participant['view_mail'] = ($participant_sent_mails->whereIn('category', ['ConfirmationRequest', 'QrEventReconfirmed'])->sum('opens') > 0);
        $participant['view_mail_date'] = $participant_sent_mails->whereIn('category', ['ConfirmationRequest', 'QrEventReconfirmed'])->where('opens', '>', 0)->min('updated_at');
        $participant['invitation_sent_at'] = $participation['invitation_sent_at'];
        $participant['confirmed_status'] = $participation['confirmed_status'];
        $participant['confirmed_at'] = $participation['confirmed_at'];
        $participant['registered_at'] = $participation['registered_at'];
        $participant['invitees'] = $participation['invitees'];
      } else {
        $participant['sent_mail_date'] = null;
        $participant['view_mail'] = false;
        $participant['view_mail_date'] = null;
        $participant['invitation_sent_at'] = null;
        $participant['confirmed_status'] = null;
        $participant['confirmed_at'] = null;
        $participant['registered_at'] = null;
        $participant['invitees'] = null;
      }
      array_push($result, $participant);
    }*/

        $collected_result = collect($participants);
        \Log::info($collected_result);
        $sent_at = $collected_result->where('sent_mail_date', '!=', NULL)->groupBy(function ($participant) {
            return Carbon::parse($participant['sent_mail_date'])->format("Y-m-d");
        })->transform(function ($participants, $date) {
            return [
                'x' => $date,
                'y' => count($participants)
            ];
        })->sortBy('x')->values();

        $seen_at = $collected_result->where('view_mail', true)->groupBy(function ($participant) {
            return Carbon::parse($participant['view_mail_date'])->format("Y-m-d");
        })->transform(function ($participants, $date) {
            return [
                'x' => $date,
                'y' => count($participants)
            ];
        })->sortBy('x')->values();


        $confirmed_at = $collected_result->where('confirmed_status', true)->groupBy(function ($participant) {
            return Carbon::parse($participant['confirmed_at'])->format("Y-m-d");
        })->transform(function ($participants, $date) {
            return [
                'x' => $date,
                'y' => count($participants)
            ];
        })->sortBy('x')->values();

        $rejected_at = $collected_result->whereStrict('confirmed_status', false)->groupBy(function ($participant) {
            return Carbon::parse($participant['confirmed_at'])->format("Y-m-d");
        })->transform(function ($participants, $date) {
            return [
                'x' => $date,
                'y' => count($participants)
            ];
        })->sortBy('x')->values();

        $collected_visits = $event->landing_visits;

        $facebook_visits = $collected_visits->filter(function ($visit) {
            return strstr($visit['service'], 'facebook');
        })->groupBy(function ($visit) {
            return Carbon::parse($visit['updated_at'])->format("Y-m-d");
        })->transform(function ($visits, $date) {
            return [
                'x' => $date,
                'y' => count($visits)
            ];
        })->sortBy('x')->values();

        $instagram_visits = $collected_visits->filter(function ($visit) {
            return strstr($visit['service'], 'instagram');
        })->groupBy(function ($visit) {
            return Carbon::parse($visit['updated_at'])->format("Y-m-d");
        })->transform(function ($visits, $date) {
            return [
                'x' => $date,
                'y' => count($visits)
            ];
        })->sortBy('x')->values();

        $linkedin_visits = $collected_visits->filter(function ($visit) {
            return strstr($visit['service'], 'linkedin');
        })->groupBy(function ($visit) {
            return Carbon::parse($visit['updated_at'])->format("Y-m-d");
        })->transform(function ($visits, $date) {
            return [
                'x' => $date,
                'y' => count($visits)
            ];
        })->sortBy('x')->values();

        $twitter_visits = $collected_visits->filter(function ($visit) {
            return strstr($visit['service'], 'twitter');
        })->groupBy(function ($visit) {
            return Carbon::parse($visit['updated_at'])->format("Y-m-d");
        })->transform(function ($visits, $date) {
            return [
                'x' => $date,
                'y' => count($visits)
            ];
        })->sortBy('x')->values();

        return response()->json(['participants' => $collected_result, 'sent' => $sent_at, 'seen' => $seen_at, 'confirmed' => $confirmed_at, 'declined' => $rejected_at, 'facebook' => $facebook_visits, 'instagram' => $instagram_visits, 'linkedin' => $linkedin_visits, 'twitter' => $twitter_visits], 200);
    }

    public function qr($uuid)
    {
        $participation = Participation::where('uuid', $uuid)->first();
        $event = $participation->event;
        //if ($event->estimate->ticket_sale) return $this->qrBought($uuid);

        if ($participation && $participation->confirmed_status) {
            $event = $participation->event;
            $event->start_date = Carbon::parse($event->start_date);
            $event->end_date = Carbon::parse($event->end_date);
            $event->start_time = Carbon::parse($event->start_time);
            $event->end_time = Carbon::parse($event->end_time);

            $view = 'ticket';
            switch ($event->id) {
                case 2:
                    $view = 'ticket-movistar';
                    break;

                case 40:
                    $view = 'ticket-40';
                    break;

                default:
                    $view = 'ticket';
                    break;
            }

            return view($view, ['uuid' => $uuid, 'event' => $participation->event, 'participant' => $participation->participant, 'start_date' => $event->start_date, 'end_date' => $event->end_date, 'start_time' => $event->start_time, 'end_time' => $event->end_time]);
        } else {
            abort(404);
        }
    }

    public function qrBrazil($uuid)
    {
        $participation = Participation::where('uuid', $uuid)->first();

        if ($participation && $participation->confirmed_status) {
            return view('qr-brazil', ['uuid' => $uuid, 'participant' => $participation->participant]);
        } else {
            abort(404);
        }
    }

    public function qrBought($uuid)
    {
        $participant_ticket = ParticipantTicket::where('uuid', $uuid)->first();

        if ($participant_ticket && $participant_ticket->participant_order->status == 1) {
            $event = $participant_ticket->participant_order->event;
            $start_date = Carbon::parse($event->start_date);
            $end_date = Carbon::parse($event->end_date);
            $start_time = Carbon::parse($event->end_time);
            $end_time = Carbon::parse($event->end_time);

            switch ($event->id) {
                case 2:
                    $view = 'ticket-movistar';
                    break;

                case 40:
                    $view = 'ticket-40';
                    break;

                default:
                    $view = 'ticket';
                    break;
            }

            return view($view, ['uuid' => $uuid, 'event' => $event, 'participant' => $participant_ticket->participant, 'start_date' => $start_date, 'end_date' => $end_date, 'start_time' => $start_time, 'end_time' => $end_time]);
        } else {
            abort(404);
        }
    }

    public function share(Event $event, $service)
    {
        $event->event_shares()->create([
            'service' => $service,
        ]);

        return redirect(Share::load(url("/events/{$event->id}/landing"), $event->title)->services($service)[$service]);
    }

    public function getPollParticipants(Event $event, Poll $poll, Request $request)
    {
        $request->validate([
            'confirmed' => 'required|boolean',
            'registered' => 'required|boolean',
        ]);

        $participants = $event->profile->participants;
        $confirmed = $request->confirmed;
        $registered = $request->registered;

        if ($confirmed) {
            if ($registered) {
                $participants = $participants->filter(function ($participant) use ($event) {
                    $participation = $participant->participations()->where('event_id', $event->id)->first();
                    return !!$participation && !!$participation->registered_at && $participation->confirmed_status;
                });
            } else {
                $participants = $participants->filter(function ($participant) use ($event) {
                    $participation = $participant->participations()->where('event_id', $event->id)->first();
                    return !!$participation && $participation->confirmed_status;
                });
            }
        }

        return response()->json(['participants' => $participants->values(), 'event_name' => $event->name], 200);
    }

    public function sendPoll(Event $event, Poll $poll, Request $request)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'required|integer|exists:participants,id',
            'fromName' => 'required|string',
            'subject' => 'required|string',
        ]);

        if (config('app.env') == 'production' && !$event->canEmail($request->participant_ids->count())) {
            return response()->json(['error' => 'email_quota'], 406);
        }

        foreach ($request->participant_ids as $participant_id) {

            $participant = Participant::find($participant_id);
            $participation = Participation::firstOrCreate(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
            $participation->save();

            if (true || $participant->allow_mailing && !$participant->blocked && (Auth::user()->company->allow_mailings || (Auth::user()->company->allow_verified_mailings && $participant->verified))) {
                SendMail::dispatch($participant->data['email'], new ParticipantPoll($participant, $event, $participation, $poll, $request->fromName, $request->subject));
            }
        }
        $event->polls()->attach($poll);

        return response()->json(['status' => 'success'], 200);
    }

    public function showPoll(Event $event, Poll $poll)
    {
        return view('poll', ['event' => $event, 'poll' => $poll]);
    }

    public function trackerInfo()
    {
        $visitor = Tracker::currentSession();

        return response()->json(['data' => $visitor], 200);
    }

    public function confirmation(Event $event, Request $request)
    {
        if (config('app.env') == 'production' && !$event->canEmail()) {
            return response()->json(['error' => 'email_quota'], 406);
        }

        $request->validate([
            'scheduleEmails' => 'sometimes|boolean',
            'date' => 'required_if:scheduleEmails,true|string',
            'time' => 'required_if:scheduleEmails,true|string',
            'fromName' => 'sometimes|string',
            'participants' => 'required|array',
            'participants.*' => 'exists:participants,id',
            'template_id' => 'nullable|exists:templates,id'
        ]);

        $template = Template::find($request->template_id) ?? $event->confirmation_template ?? Template::find(1);

        try {
            $this->useZohoEmail($event, $template, $request->subject, $request->participants);
            return response()->json(null, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
        // $body = $this->changeStaticBody($event, $template->content);

        // $participants = $event->profile->participants_collection
        //     ->whereDoesntHave('participations', function ($query) use ($event) {
        //         $query->where('event_id', $event->id);
        //     })
        //     ->orWhereHas('participations', function ($query) use ($event) {
        //         $query->where('event_id', $event->id)->whereNull('confirmed_sent_at');
        //     })
        //     ->with(['participations' => function ($query) use ($event) {
        //         $query->where('event_id', $event->id)->whereNull('confirmed_sent_at');
        //     }])
        //     ->get()
        //     ->whereIn('id', $request->participants);

        // if ($request->scheduleEmails) {
        //     $date = new Carbon($request->date . ' ' . $request->time);
        // } else {
        //     $date = null;
        // }

        // SendMassMail::dispatch(Auth::user(), $event, $request, $participants, 'ConfirmationRequest', $body, $date);

        // return response()->json($participants->pluck('data')->map(function ($participant) {
        //     return collect($participant)->only('name', 'lastname', 'email');
        // }), 200);
    }

    public function reconfirmation(Event $event, Request $request)
    {
        if (config('app.env') == 'production' && !$event->canEmail(count($request->participants))) {
            return response()->json(['error' => 'email_quota'], 406);
        }

        $request->validate([
            'fromName' => 'sometimes|string',
            'participants' => 'required|array',
            'participants.*' => 'exists:participants,id',
            'template_id' => 'nullable|exists:templates,id'
        ]);

        $template = Template::find($request->template_id) ?? $event->reconfirmation_template ?? Template::find(2);
        $body = $this->changeStaticBody($event, $template->content);

        $participants = $event->profile->participants_collection->whereHas('participations', function ($query) use ($event) {
            $query->where('event_id', $event->id)->where('confirmed_sent_at', '!=', null)->whereNull('confirmed_at');
        })->with(['participations' => function ($query) use ($event) {
            $query->where('event_id', $event->id);
        }])->get()->whereIn('id', $request->participants);

        SendMassMail::dispatch(Auth::user(), $event, $request, $participants, 'ConfirmationRequest', $body);

        return response()->json(null, 204);
    }

    public function resendQr(Event $event, Request $request)
    {
        if (config('app.env') == 'production' && !$event->canEmail(count($request->participants))) {
            return response()->json(['error' => 'email_quota'], 406);
        }

        $request->validate([
            'fromName' => 'sometimes|string',
            'participants' => 'required|array',
            'participants.*' => 'exists:participants,id',
            'template_id' => 'nullable|exists:templates,id'
        ]);

        $template = Template::find($request->template_id) ?? $event->qrcode_template ?? Template::find(3);
        $body = $this->changeStaticBody($event, $template->content);
        $participants = $event->profiledParticipations->where('confirmed_status', true)->with('participant')->get()->pluck('participant')->whereIn('id', $request->participants);

        SendMassMail::dispatch(Auth::user(), $event, $request, $participants, 'QRLinkResend', $body);

        return response()->json($template->name, 200);
    }

    public function participantsConfirmation(Event $event)
    {
        $participants = $event->profile->participants_collection
            ->whereDoesntHave('participations', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })->orWhereHas('participations', function ($query) use ($event) {
                $query->where('event_id', $event->id)->whereNull('confirmed_sent_at');
            })->without('participations')
            ->get();

        return response()->json(['participants' => $participants], 200);
    }

    public function participantsReconfirmation(Event $event)
    {
        $participants = $event->profile->participants_collection->whereHas('participations', function ($query) use ($event) {
            $query->where('event_id', $event->id)->where('confirmed_sent_at', '!=', null)->whereNull('confirmed_at');
        })->get();

        return response()->json(['participants' => $participants], 200);
    }

    public function participantsQr(Event $event)
    {
        $participants = $event->profiledParticipations->where('confirmed_status', true)->with('participant')->get()->pluck('participant');
        return response()->json(['participants' => $participants], 200);
    }

    public function tickets(Event $event)
    {
        $tickets = $event->tickets->where('left', '>', 0);
        return response()->json(['tickets' => $tickets], 200);
    }

    public function free(Event $event, Request $request)
    {
        if (config('app.env') == 'production' && !$event->canEmail()) {
            return response()->json(['error' => 'email_quota'], 406);
        }

        $request->validate([
            'participants' => 'required|array',
            'participants.*' => 'sometimes|integer|exists:participants,id',
            'template_id' => 'nullable|exists:templates,id',
            'ticket_id' => 'required|integer|exists:tickets,id',
        ]);

        $participants = $event->participants()->whereIn('participants.id', $request->participants)->get();
        $ticket_db = Ticket::find($request->ticket_id);
        if ($ticket_db->left < $participants->count()) {
            return response()->json(null, 410);
        }

        $template = $request->template_id ? Template::find($request->template_id) : $event->free_template;
        if ($template) {
            $body = $this->changeStaticBody($event, $template->content);
            $converter = new CssToInlineStyles();
        }

        foreach ($participants as $participant) {
            $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
            $participation->save();

            $number = Keygen::numeric(14)->prefix(mt_rand(1, 9))->generate();
            while (ParticipantOrder::where('number', $number)->count() > 0) {
                $number = Keygen::numeric(14)->prefix(mt_rand(1, 9))->generate();
            }

            $participant_order = ParticipantOrder::create([
                'number' => $number,
                'event_id' => $event->id,
                'participant_id' => $participant->id,
                'buyData' => array(['id' => $request->ticket_id, 'amount' => 1, 'discount' => null, 'free' => true]),
                'return_url' => null,
                'status' => 1
            ]);

            // Creating Tickets
            ParticipantTicket::create([
                'ticket_id' => $request->ticket_id,
                'discount_id' => null,
                'participant_order_id' => $participant_order->id,
                'uuid' => (string) Str::uuid(),
                'free' => true
            ]);

            if (true || $participant->allow_mailing && !$participant->blocked && (Auth::user()->company->allow_mailings || (Auth::user()->company->allow_verified_mailings && $participant->verified))) {
                if ($template) {
                    $current_body = $this->changeDynamicBody($participation->participant, $participation, $body);
                    $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));
                    $subject = $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname'] . ' Ticket de Cortesía - ' . $participation->event->name;

                    SendMail::dispatch($participation->participant->data['email'], new ParticipantTemplate($participation->event, $participation, null, $participation->event->from_subject ?? $subject, 'FreeTicket', $content));
                } else {
                    SendMail::dispatch($participant->data['email'], new ParticipantFree($participant_order->participant, $participant_order->event, $participation, $participant_order->participant_tickets));
                }
            }
        }
    }

    public function scheduled(Event $event, Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:"H:i"',
            'type' => 'required|string|in:confirmation,qr,reconfirmation',
        ]);

        $date = new Carbon($request->date . " " . $request->time);

        $participants = [];
        switch ($request->type) {
            case 'confirmation':
                $participants = $event->profile->participantsCollection->whereNotIn('id', $event->participations()->whereNotNull('confirmed_sent_at')->pluck('participant_id'))->get();
                foreach ($participants as $participant) {

                    $participation = Participation::firstOrCreate(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);

                    $isInvitee = false;
                    if ($event->invitees > 0) {
                        $isInvitee = $event->participations->pluck('invitees')->flatten()->contains($participant->data['email']);
                    }

                    if (!$participation->confirmed_sent_at) {
                        if (true || $participant->allow_mailing && !$participant->blocked && (Auth::user()->company->allow_mailings || (Auth::user()->company->allow_verified_mailings && $participant->verified))) {
                            SendMail::dispatch($participant->data['email'], new ParticipantConfirmed($participant, $event, $participation, $isInvitee, null, null), $date);
                            $participation->confirmed_sent_at = $date;
                            $participation->save();
                        }
                    }
                }
                return response()->json($data ?? null, 200);

            case 'qr':
                foreach ($event->participations()->whereNotNull('confirmed_at')->get() as $participation) {

                    $isInvitee = false;
                    $email = $participation->participant->data['email'];
                    if ($participation->event->invitees > 0) {
                        $isInvitee = $participation->event->participations->pluck('invitees')->flatten()->contains($email);
                    }

                    if ($participation->participant->allow_mailing && !$participation->participant->blocked && (Auth::user()->company->allow_mailings || (Auth::user()->company->allow_verified_mailings && $participation->participant->verified))) {
                        SendMail::dispatch($participation->participant->data['email'], new ParticipantQr($participation->participant, $participation->event, $participation, $isInvitee), $date);
                    }
                }
                return response()->json(null, 204);

            case 'reconfirmation':
                $participants = $event->profile->participantsCollection->whereNotIn('id', $event->participations()->whereNotNull('confirmed_sent_at')->pluck('participant_id'))->get();
                break;
        }

        foreach ($event->participations()->whereNotNull('confirmed_at')->get() as $participation) {

            $isInvitee = false;
            $email = $participation->participant->data['email'];
            if ($participation->event->invitees > 0) {
                $isInvitee = $participation->event->participations->pluck('invitees')->flatten()->contains($email);
            }

            if ($participation->participant->allow_mailing) {
                SendMail::dispatch($participation->participant->data['email'], new ParticipantReconfirmed($participation->participant, $participation->event, $participation, $isInvitee, $request->fromName, $request->subject));
            }
        }
        return response()->json(null, 204);
    }

    public function confirmationPreview(Event $event)
    {
        $participant = (object) [
            'data' => [
                'name' => '{Nombre}',
                'lastname' => '{Apellido}',
            ]
        ];

        $participation = (object) [
            'uuid' => null,
        ];

        $start_date = Carbon::parse($event->start_date)->locale('es');
        $start_time = Carbon::parse($event->start_time)->locale('es');

        return view('emails.participant-confirmed', [
            'subheader' => 'Subheader',
            'subject' => 'Subject',
            'event' => $event,
            'participant' => $participant,
            'participation' => $participation,
            'start_date' => $start_date,
            'start_time' => $start_time,
        ]);
    }

    public function qrPreview(Event $event)
    {
        $participant = (object) [
            'data' => [
                'name' => '{Nombre}',
                'lastname' => '{Apellido}',
            ]
        ];

        $participation = (object) [
            'uuid' => null,
        ];

        $start_date = Carbon::parse($event->start_date)->locale('es');
        $start_time = Carbon::parse($event->start_time)->locale('es');
        $end_date = Carbon::parse($event->end_date)->locale('es');
        $end_time = Carbon::parse($event->end_time)->locale('es');

        return view('emails.participant-qr', [
            'subject' => 'Subject',
            'event' => $event,
            'participant' => $participant,
            'participation' => $participation,
            'start_date' => $start_date,
            'start_time' => $start_time,
            'end_date' => $end_date,
            'end_time' => $end_time,
        ]);
    }

    public function reconfirmationPreview(Event $event)
    {
        $participant = (object) [
            'data' => [
                'name' => '{Nombre}',
                'lastname' => '{Apellido}',
            ]
        ];

        $participation = (object) [
            'uuid' => null,
        ];

        $start_date = Carbon::parse($event->start_date)->locale('es');
        $start_time = Carbon::parse($event->start_time)->locale('es');

        return view('emails.participant-reconfirmed', [
            'subject' => 'Subject',
            'event' => $event,
            'participant' => $participant,
            'participation' => $participation,
            'start_date' => $start_date,
            'start_time' => $start_time,
        ]);
    }

    public function landingPreview(Event $event, $which)
    {
        if (!$event->landing || !Auth::guard('api')->user()) {
            return redirect('/events');
        }

        $env = config('app.env');

        ## DATE FORMATS
        $event->start_date = Carbon::parse($event->start_date);
        $event->end_date = Carbon::parse($event->end_date);
        $event->start_time = Carbon::parse($event->start_time);
        $event->end_time = Carbon::parse($event->end_time);

        ## Links to calendar
        $link = Link::create($event->name, $event->start_date, $event->end_date)
            ->description($event->description)
            ->address($event->location);

        ## ACTORS UNIQUE COLLECTION
        $actors = collect([]);
        foreach ($event->activities as $activity) {
            foreach ($activity->actors as $actor) {
                $actors->push($actor);
            }
        }
        $actors = $actors->merge($event->actors)->unique('id');

        ## GROUPING ACTIVITIES
        $activities_by_date = $event->activities->sortBy('start_time')->sortBy('date')->groupBy('date');

        ## Invitee
        $invitee = null;

        switch ($which) {
            case 1:
                $landing = 'landing-black';
                break;

            case 2:
                $landing = 'landing-panama';
                break;

            default:
                $landing = 'landing-default';
                break;
        }

        return view($landing, ['event' => $event, 'actors' => $actors, 'activitiesByDate' => $activities_by_date, 'invitee' => $invitee, 'link' => $link, 'editor' => false, 'env' => $env]);
    }

    public function emails(Event $event)
    {
        return response()->json(['emails' => $event->sentMails], 200);
    }

    public function updateLanding(Event $event, Request $request)
    {
        $request->validate([
            'landing' => 'required|integer|in:0,1,2',
        ]);

        try {
            $event->landing->which = $request->landing;
            $event->landing->save();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(null, 500);
        }
    }

    public function indexImages(Event $event)
    {
        $images = ['banner' => Storage::url($event->banner), 'logo' => Storage::url($event->logo_event)];

        $gallery = [];
        if ($event->landing) {
            foreach ($event->landing->images as $image) {
                array_push($gallery, Storage::url($image));
            }
        }

        $extra_images = [];
        foreach ($event->extra_images as $image) {
            array_push($extra_images, Storage::url($image));
        }

        $images['gallery'] = $gallery;
        $images['extra_images'] = $extra_images;

        return response()->json(['images' => $images], 200);
    }

    public function syncCollectors(Event $event, Request $request)
    {
        $request->validate([
            'collectors' => 'sometimes|array|nullable',
            'collectors.*' => 'integer|exists:users,id'
        ]);

        $event->collectors()->sync($request->collectors);
        $event->refresh();

        return response()->json(['collectors' => $event->collectors], 200);
    }

    public function collectors(Event $event)
    {
        return response()->json(['collectors' => $event->collectors], 200);
    }

    public function app(Event $event, $code)
    {
        $participant = $event->profile->participants_collection->whereJsonContains('data->email', $code)->get()->first();
        $participation = null;
        if ($participant) {
            $participation = Participation::where('event_id', $event->id)->where('participant_id', $participant->id)->get()->first();
        }

        if ($participation && $event->estimate->app) {
            return new AppDataResource($participation);
        }

        return response()->json($participant, 403);
    }

    public function export(Event $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
        ]);

        $file_name = Str::uuid();
        (new EventExport($event))->store('public/' . $file_name . '.xlsx');
        SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));

        return response()->json(['fileName' => $file_name], 200);

        // return (new EventExport($event))->download('event-confirmation.xlsx');
    }

    public function exportVideoParticipants(Event $event)
    {
        // $request->validate([
        //     'to' => 'required|email',
        // ]);

        // $file_name = Str::uuid();
        // (new EventExport($event))->store('public/' . $file_name . '.xlsx');
        // Mail::to($request->to)->queue(new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));
        // return response()->json(['fileName' => $file_name], 200);

        // return response()->json(['chat_activity_times' => $chat_activity_times], 200);
        return (new EventExportVideoParticipants($event))->download('event-video-participants.xlsx');
    }

    // public function testExcel(Event $event)
    // {
    // $chat_activity_times = $event->chat_activity_times()
    //     ->with(['participant', 'activity'])
    //     ->orderBy('participation_id', 'ASC')
    //     ->orderBy('created_at', 'ASC')
    //     ->get();

    // return view('exports.event-video-times', ['event' => $event, 'chat_activity_times' => $chat_activity_times]);
    //     return (new EventExportVideoParticipants($event))->download('event-video-participants.xlsx');
    // }

    public function exportConfirmation(Event $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
        ]);

        $file_name = Str::uuid();

        Log::debug($file_name);

        (new EventConfirmationExport($event))->store('public/' . $file_name . '.xlsx');
        //SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));

        Log::debug('exportConfirmation Gotten');

        return response()->json(['fileName' => $file_name], 200);

        //return (new EventConfirmationExport($event))->download('event-confirmation.xlsx');
    }

    public function exportSimpleConfirmation(Event $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'key' => 'required',
        ]);

        if ($request->key != '501a934f-aea1-426e-8e8d-3304b48eceac') return response()->json(['status' => 'failure'], 403);

        $file_name = Str::uuid();
        (new EventSimpleConfirmationExport($event))->store('public/' . $file_name . '.xlsx');
        SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));
        return response()->json(['fileName' => $file_name], 200);
    }

    public function exportSimpleSales(Event $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'key' => 'required',
        ]);

        if ($request->key != '501a934f-aea1-426e-8e8d-3304b48eceac') return response()->json(['status' => 'failure'], 403);

        $file_name = Str::uuid();
        (new EventSimpleSalesExport($event))->store('public/' . $file_name . '.xlsx');
        SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));
        return response()->json(['fileName' => $file_name], 200);
    }

    public function exportSimpleConsolidated(Event $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'key' => 'required|uuid',
        ]);

        if ($request->key != '501a934f-aea1-426e-8e8d-3304b48eceac') return response()->json(['status' => 'failure'], 403);

        $file_name = Str::uuid();
        (new EventSimpleConsolidatedExport($event))->store('public/' . $file_name . '.xlsx');
        SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));
        return response()->json(['fileName' => $file_name], 200);
    }

    public function exportPoll(Event $event, Poll $poll)
    {
        $questions = $poll->questions();
        $questions_count = $questions->count();
        $poll->complete = 0;
        Answer::whereIn('question_id', $questions->pluck('id'))->get()
            ->groupBy('participant_id')
            ->each(function ($answers) use ($poll, $questions_count) {
                if ($answers->count() == $questions_count) {
                    $poll->complete++;
                }
            });

        return (new EventPollExport($event, $poll))->download('event-poll.xlsx');
    }

    public function exportTicketSale(Event $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
        ]);

        $file_name = Str::uuid();
        (new EventTicketSaleExport($event))->store('public/' . $file_name . '.xlsx');
        SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));
        return response()->json(['fileName' => $file_name], 200);

        //return (new EventTicketSaleExport($event))->download('event-ticketsale.xlsx');
    }

    public function exportApp(Event
    $event, Request $request)
    {
        $request->validate([
            'to' => 'required|email',
        ]);

        $file_name = Str::uuid();
        (new EventAppExport($event))->store('public/' . $file_name . '.xlsx');
        SendMail::dispatch($request->to, new ExportedExcel($event, 'Venoot', null, Storage::url($file_name . '.xlsx')));
        return response()->json(['fileName' => $file_name], 200);

        //return (new EventAppExport($event))->download('event-app.xlsx');
    }

    public function testCorreoAsView()
    {
        // $event = Event::find(6);
        // $participant = Participant::find(1027);
        // $participation = Participation::find(1025);

        // $start_date = Carbon::parse($event->start_date)->locale('es');
        // $start_time = Carbon::parse($event->start_time)->locale('es');

        return view('emails.verification', ['user' => Auth::user()]);
    }

    public function contactMail(Event $event, Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'subject' => 'required|string|min:2',
            'body' => 'required|string'
        ]);

        SendMail::dispatch($event->email, new ContactEmail($request->name, $request->email, $request->subject, $request->body));
        return response()->json($request->message, 200);
    }

    public function producerLogin(Request $request)
    {
        // return response()->json(null, 401);
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|string|min:6',
        //     'event_id' => 'required|exists:events,id'
        // ]);
        // return response()->json(null, 401);

        $event = Event::where('producer', $request->email)->where('producer_password', $request->password)->first();

        if ($event) {
            return response()->json(['event_id' => $event->id, 'title' => $event->name, 'email' => $event->producer, 'password' => $event->producer_password], 200);
        } else {
            return response()->json(null, 401);
        }
    }

    public function push(Event $event, Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string',
        //     'password' => 'required|string',
        //     'body' => 'required|string'
        // ]);

        if ($event->producer_password != $request->password) {
            return response()->json(null, 403);
        }

        $notification = $event->push_notifications()->create([
            'title' => $event->name,
            'body' => $request->body,
        ]);

        $channelName =  'event-channel-' . $event->id;
        $expo = \ExponentPhpSDK\Expo::normalSetup();

        $notifiable_participants = $event->profile->participants_collection->whereNotNull('expo_token')->get();

        if ($notifiable_participants->count() > 0) {

            $data = [
                'title' => $event->name,
                'body' => $request->body,
                'data' => json_encode(
                    array(
                        'type' => 'evento',
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'body' => $notification->body,
                        'created_at' => $notification->created_at
                    )
                )
            ];

            foreach ($notifiable_participants as $participant) {
                $expo->subscribe($channelName, $participant->expo_token);
            }

            $expo->notify($channelName, $data);

            foreach ($notifiable_participants as $participant) {
                $expo->unsubscribe($channelName, $participant->expo_token);
            }
        }

        return response()->json($notifiable_participants, 200);
    }

    public function push_notifications(Event $event)
    {
        return $event->push_notifications->slice(-10)->reverse()->values();
    }

    public function people(Event $event)
    {
        return response()->json(['asistentes' => $event->profile->participants], 200);
    }

    public function unreadMessages(Event $event, Request $request)
    {
        $unread_messages = $event->chat_messages()->where('sender_id', $request->sender_id)->where('reciever_id', $request->reciever_id)->where('read', false)->get();

        foreach ($unread_messages as $unread_message) {
            $unread_message->read = true;
            $unread_message->save();
        }

        return response()->json($unread_messages, 200);
    }

    public function messages(Event $event, Request $request)
    {
        $message = $event->chat_messages()->create([
            'sender_type' => $request->sender_type,
            'sender_id' => $request->sender_id,
            'sender_name' => $request->sender_name,
            'reciever_type' => $request->reciever_type,
            'reciever_id' => $request->reciever_id,
            'message' => $request->message,
        ]);

        $channelName =  'participant-' . $request->reciever_id;
        $expo = \ExponentPhpSDK\Expo::normalSetup();

        $participant = $event->profile->participants_collection->where('id', $request->reciever_id)->first();

        if ($participant && !!$participant->expo_token) {

            $data = [
                'title' => $request->sender_name,
                'body' => $request->message,
                'data' => json_encode(
                    array(
                        'type' => 'chat',
                        'sender_id' => $message->sender_id,
                    )
                )
            ];

            $expo->subscribe($channelName, $participant->expo_token);
            $expo->notify($channelName, $data);
            $expo->unsubscribe($channelName, $participant->expo_token);
        }

        return response()->json(null, 204);
    }

    public function participantsForApp(Event $event)
    {
        $participants = $event->profile->participants->transform(function ($participant) use ($event) {
            $participation = $event->participations->where('participant_id', $participant->id)->first();
            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);

            if ($participation) {
                $participant = array_merge($participant, $participation->toArray());
                unset($participant['participant']);
                $participant['participation_id'] = $participant['id'];
                $participant['id'] = $participant['participant_id'];
                unset($participant['participant_id']);
            }

            return $participant;
        });

        return response()->json(['participants' => $participants], 200);
    }

    public function participantsForControlPanel(Event $event)
    {
        $participants = DB::table('participations')
            ->selectRaw("participations.id as participation_id,
                          participations.uuid,
                          participations.invitation_sent_at, participations.confirmed_status, participations.confirmed_sent_at,
                          participations.confirmed_at, participations.registered_at,
                          participants.*")
            ->join('participants', 'participants.id', '=', 'participations.participant_id')
            ->where('participations.event_id', $event->id)->get();

        $participants = $participants->transform(function ($participant) use ($event) {
            $participant = json_decode(json_encode($participant), true);
            $participant = array_merge($participant, json_decode($participant['data'], true));

            unset($participant['data']);
            return $participant;
        });

        \Log::info($participants);

        return response()->json(['participants' => $participants], 200);
    }

    public function confirmFromApp(Event $event, Request $request)
    {
        if (!$event->over_quota && $event->quota !== null && $event->participations()->where('confirmed_status', true)->count() >= $event->quota) {
            return response()->json(['type' => 'danger', 'message' => $event->apology], 409);
        }

        $participant = $event->profile->participants_collection->whereJsonContains('data->email', $request['email'])->first();
        unset($request->event_id);

        $data = [];
        foreach ($request->all() as $key => $value) {
            try {
                $data[$key] = json_decode($value)->name;
            } catch (\Exception $e) {
                $data[$key] = $value;
            }
        }

        if (!$participant) {
            $participant = Participant::create([
                'data' => $data,
                'database_id' => $event->profile->database->id,
            ]);
        }

        $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);

        if ($participation->confirmed_status == true) {
            return response()->json(['type' => 'danger', 'message' => 'Este correo ya se encuentra registrado!'], 200);
        }

        if (!$event->has_confirmation) {
            $participation->confirmed_at = Carbon::now();
            $participation->confirmed_status = true;
        }
        $participation->save();

        if ($participant && $participation) {

            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);
            $participant = array_merge($participant, $participation->toArray());
            unset($participant['participant']);
            $participant['participation_id'] = $participant['id'];
            $participant['id'] = $participant['participant_id'];
            unset($participant['participant_id']);

            return response()->json(['participant' => $participant], 200);
        } else {
            return response()->json(null, 500);
        }
    }

    public function registerFromControlPanel(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            // 'activity_id' => 'required|string|exists:activities'
        ]);

        $participation = Participation::where('uuid', $request->uuid)->first();

        if (!!$participation->registered_at) {

            $participant = $participation->participant;
            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);

            if ($participation) {
                $participant = array_merge($participant, $participation->toArray());
                unset($participant['participant']);
                $participant['participation_id'] = $participant['id'];
                $participant['id'] = $participant['participant_id'];
                unset($participant['participant_id']);
            }

            return response()->json(['participant' => $participant], 409);
        }

        if ($participation) {
            $participation->registered_at = Carbon::now()->toDateTimeString();
            $participation->save();

            // Registration::create(['participation_id' => $participation->id, 'activity_id' => $request->activity_id, 'user_id' => Auth::user()->id]);

            $participant = $participation->participant;
            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);

            if ($participation) {
                $participant = array_merge($participant, $participation->toArray());
                unset($participant['participant']);
                $participant['participation_id'] = $participant['id'];
                $participant['id'] = $participant['participant_id'];
                unset($participant['participant_id']);
            }

            return response()->json(['participant' => $participant], 200);
        }
    }

    public function fullFromApp(Event $event, Request $request)
    {
        if (!$event->over_quota && $event->quota !== null && $event->participations()->where('confirmed_status', true)->count() >= $event->quota) {
            return response()->json(['type' => 'danger', 'message' => $event->apology], 409);
        }

        $participant = $event->profile->participants_collection->whereJsonContains('data->email', $request['email'])->first();
        if ($participant) {
            $participation = $event->profiled_participations->where('participant_id', $participant->id)->first();
            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);

            if ($participation) {
                $participant = array_merge($participant, $participation->toArray());
                unset($participant['participant']);
                $participant['participation_id'] = $participant['id'];
                $participant['id'] = $participant['participant_id'];
                unset($participant['participant_id']);
            }

            return response()->json(['participant' => $participant], 409);
        }

        $data = [];
        foreach ($request->all() as $key => $value) {
            try {
                $data[$key] = json_decode($value)->name;
            } catch (\Exception $e) {
                $data[$key] = $value;
            }
        }

        if (!$participant) {
            $participant = Participant::create([
                'data' => $data,
                'database_id' => $event->profile->database->id,
            ]);
        }

        $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);

        if (!$event->has_confirmation) {
            $participation->confirmed_at = Carbon::now();
            $participation->confirmed_status = true;
        }
        $participation->registered_at = Carbon::now();
        $participation->save();

        $event->queue_prints()->create([
            'participation_uuid' => $participation->uuid
        ]);

        if ($participant && $participation) {

            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);
            $participant = array_merge($participant, $participation->toArray());
            unset($participant['participant']);
            $participant['participation_id'] = $participant['id'];
            $participant['id'] = $participant['participant_id'];
            unset($participant['participant_id']);

            return response()->json(['participant' => $participant], 200);
        } else {
            return response()->json(null, 500);
        }
    }

    public function queuePrint(Event $event, Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid|exists:participations,uuid'
        ]);

        $event->queue_prints()->create([
            'participation_uuid' => $request->uuid
        ]);

        return response()->json(null, 204);
    }

    public function checkPrints(Event $event)
    {
        $activePrints = $event->queue_prints()->where('printed', false)->get();
        $event->queue_prints()->where('printed', false)->update(['printed' => true]);

        return response()->json(['queued' => $activePrints], 200);
    }

    ## EXPORTS
    public function dashboardExport(Event $event)
    {
        return (new EventExport($event))->download('event.xlsx');
    }

    public function exportBounced(Event $event)
    {
        return (new EventBouncedExport($event))->download('bounced.xlsx');
    }

    public function suppressEmails(Event $event, Request $request)
    {
        $request->validate([
            'reasons' => 'required|array',
            'types' => 'required|array'
        ]);

        $suppress_emails = $event->sent_emails()->where('bounced', true)->whereIn('bounce_type', $request->types)->get()->each(function ($suppressed_email) {
            $participant = $suppressed_email->participation->participant;
            $participant->allow_mailing = false;
            $participant->save();
        });

        return response()->json($suppress_emails, 200);
    }

    public function voluntarySupression($uuid)
    {
        $participation = Participation::where('uuid', $uuid)->first();

        if ($participation) {
            $participant = $participation->participant;
            $participant->blocked = true;
            $participant->save();

            return response()->json(['status' => 'removed'], 200);
        }

        return response()->json(['status' => 'not found'], 404);
    }

    public function signIn(Event $event, Request $request)
    {
        if (!$event->over_quota && $event->quota !== null && $event->participations()->where('confirmed_status', true)->count() >= $event->quota) {
            return response()->json(['type' => 'danger', 'message' => $event->apology], 200);
        }

        $participant = $event->profile->participants_collection->whereJsonContains('data->email', $request['email'])->first();
        if ($participant) {
            return response()->json(['type' => 'error', 'message' => 'conflict'], 409);
        }

        $data = [];
        foreach ($request->all() as $key => $value) {
            $data[$key] = $value;
        }

        $participant = Participant::create([
            'data' => $data,
            'database_id' => $event->profile->database->id,
        ]);

        $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);

        if (!$event->has_confirmation) {
            $participation->confirmed_at = Carbon::now();
            $participation->confirmed_status = true;
        }
        $participation->save();

        foreach ($event->localization as $event_id) {
            if ($event_id) {
                $new_participation = Participation::firstOrNew(['event_id' => $event_id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);
                if (!$event->has_confirmation) {
                    $new_participation->confirmed_at = Carbon::now();
                    $new_participation->confirmed_status = true;
                }
                $new_participation->save();
            }
        }

        if ($participant && $participation) {
            return response()->json(['type' => 'success', 'message' => 'signedIn'], 200);
        } else {
            return response()->json(['type' => 'danger ', 'message' => 'error'], 500);
        }
    }

    public function getAppQuestions(Event $event)
    {
        $questions = $event->app_questions;
        if ($event->localization) {
            foreach ($event->localization as $event_id) {
                if ($event_id) {
                    $questions = $questions->concat(Event::find($event_id)->app_questions);
                }
            }
        }
        return response()->json(['appQuestions' => $questions], 200);
    }

    public function createMeetingWindow(Event $event, Request $request)
    {
        $request->validate([
            'host_id' => 'required|exists:participants,id',
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'interval' => 'required|in:[5,10,15,20,25,30,35,40,45,50,55,60]'
        ]);

        $end_time = Carbon::parse($request->end_time);
        $current_start_time = Carbon::parse($request->start_time);
        $current_end_time = Carbon::parse($request->start_time);
        $current_end_time->add($request->interval, 'minutes');

        while ($current_end_time->lte($end_time)) {
            $meeting = $event->meetings()->create([
                'host_id' => $request->host_id,
                'date' => $request->date,
                'start_time' => $current_start_time,
                'end_time' => $current_end_time,
            ]);

            $meeting->save();

            $current_start_time->add($request->interval, 'minutes');
            $current_end_time->add($request->interval, 'minutes');
        }

        return response()->json(['meetings' => $event->meetings->sortBy('start_time')->sortBy('date')->sortBy('name')->values()], 200);
    }

    public function indexMeetings(Event $event)
    {
        return response()->json(['meetings' => $event->meetings->sortBy('start_time')->sortBy('date')->sortBy('name')->values()], 200);
    }

    public function deleteMeeting(Event $event, Meeting $meeting)
    {
        $meeting->delete();
        return response()->json(['meetings' => $event->meetings->sortBy('start_time')->sortBy('date')->sortBy('name')->values()], 200);
    }

    public function assignAttendant(Event $event, Meeting $meeting, Request $request)
    {
        $request->validate([
            'attendant_id' => 'nullable|exists:participants,id',
        ]);

        $meeting->attendant_id = $request->attendant_id;
        $meeting->save();
        $meeting->refresh();

        return response()->json(['attendant' => $meeting->attendant], 200);
    }

    public function takeMeeting(Event $event, Meeting $meeting, Request $request)
    {
        $request->validate([
            'attendant_id' => 'required|exists:participants,id',
        ]);

        if ($meeting->attendant) {
            return response()->json(null, 409);
        }

        $meeting->attendant_id = $request->attendant_id;
        $meeting->save();
        $meeting->refresh();

        return response()->json(null, 200);
    }

    public function participantsSecureVideo(Event $event, Request $request)
    {
        $request->validate([
            'qr_code' => 'required|uuid'
        ]);

        $participation = $event->participations()->where('uuid', $request->qr_code)->first();

        if ($participation) {

            $participation->secure_video_views()->create([
                'event_id' => $event->id,
                'video_url' => $event->landing->video_url,
                'seen_at' => Carbon::now(),
            ]);
            $participation->save();

            return response()->json(['success' => true, 'url' => $event->landing->video_url], 200);
        } else {
            return response()->json(['success' => false], 200);
        }
    }

    public function participantsDoneVideo(Event $event, Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid'
        ]);

        $participation = $event->participations()->where('uuid', $request->uuid)->first();

        if ($participation) {

            $secure_video_view = $participation->secure_video_views->last();
            $secure_video_view->update([
                'stopped_at' => Carbon::now(),
            ]);
            $secure_video_view->save();

            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 200);
        }
    }

    public function getSecureVideoViews(Event $event)
    {
        $secure_video_views = $event->secure_video_views()
            ->select('participants.data->name as name', 'participants.data->lastname as lastname', 'participants.data->email as email', 'video_url', 'seen_at', 'stopped_at')
            ->leftJoin('participations', 'secure_video_views.participation_id', '=', 'participations.id')
            ->leftJoin('participants', 'participations.participant_id', '=', 'participants.id')
            ->get();

        return response()->json(['secure_video_views' => $secure_video_views], 200);
    }

    public function participantByQr(Event $event, Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid'
        ]);

        $participation = $event->participations()->where('uuid', $request->uuid)->first();

        if ($participation) {
            return response()->json(['success' => true, 'participant' => $participation->participant->data], 200);
        } else {
            return response()->json(['success' => false], 200);
        }
    }

    public function streamChatMessages(Event $event)
    {
        return response()->json(['chat_messages' => $event->stream_chat_messages], 200);
    }

    public function sendStreamChatMessages(Event $event, Request $request)
    {
        $request->validate([
            'participation_id' => 'required|exists:participations,id',
            'activity_id' => 'nullable|exists:activities,id',
            'message' => 'required|string'
        ]);

        $streamChatMessage = $event->stream_chat_messages()->create([
            'participation_id' => $request->participation_id,
            'activity_id' => $request->activity_id,
            'message_data' => [
                'id' => $request->participation_id,
                'type' => 'message',
                "text" => $request->message,
                "name" => 'Speaker',
                "color" => "#000"
            ]
        ]);

        $activity_id = $streamChatMessage->activity_id ?? "null";
        $channel = 'event-' . $streamChatMessage->event_id . '-activity-' . $activity_id . ($event->shared_chat ? '-user-' . $request->participation_id : '');
        broadcast(new StreamChatMessageEvent($channel, $streamChatMessage))->toOthers();
        return response()->json(['status' => 'success'], 200);
    }

    public function questionRequest(Event $event, Request $request)
    {
        $request->validate([
            'activity_id' => 'nullable|exists:activities,id',
            'message' => 'required|string',
            'question_limit' => 'required|integer',
            'per_person_question_limit' => 'required|integer',
            'vote_instead' => 'bool'
        ]);

        $question_request = $event->question_requests()->create([
            'activity_id' => $request->filled('activity_id') ? $request->activity_id : null,
            'message' => $request->message,
            'question_limit' => $request->question_limit,
            'per_person_question_limit' => $request->per_person_question_limit,
            'vote_instead' => $request->vote_instead ?? false,
        ]);

        if ($request->activity_id) {
            broadcast(new QuestionRequestEvent('event-' . $question_request->event_id . '-activity-' . $question_request->activity_id, $question_request));
        } else {
            broadcast(new QuestionRequestEvent('event-' . $question_request->event_id, $question_request));
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function submittedQuestions(Event $event)
    {
        return response()->json(['submitted_questions' => $event->submitted_questions]);
    }

    public function questionVotes(Event $event)
    {
        return response()->json(['questionVotes' => $event->question_votes], 200);
    }

    public function updateDefaultTemplate(Event $event, Request $request)
    {
        $request->validate([
            'type' => 'required|in:confirmation,reconfirmation,qr,free',
            'template_id' => 'nullable|exists:templates,id'
        ]);

        switch ($request->type) {
            case 'confirmation':
                $event->confirmation_id = $request->template_id;
                break;

            case 'reconfirmation':
                $event->reconfirmation_id = $request->template_id;
                break;

            case 'qr':
                $event->qrcode_id = $request->template_id;
                break;

            case 'free':
                $event->free_id = $request->template_id;
                break;
        }

        $event->save();
        return response()->json(null, 204);
    }

    public function advanced(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:events,id',
            'confirmed' => 'required|in:none,true,false,uninvited',
            'registered' => 'required|in:none,true,false'
        ]);

        $events = Event::whereIn('id', $request->ids)->get();

        $headers = $events->flatMap(function ($event) {
            return $event->profile->database->fields;
        })->unique('key')->values()->map(function ($field) {
            $field['text'] = $field['name'];
            $field['value'] = $field['key'];
            return $field;
        });

        $participants = $events->flatMap(function ($event) use ($request) {

            $query = $event->participations();

            switch ($request->confirmed) {
                case "true":
                    $query->where('confirmed_status', true);
                    break;

                case "false":
                    $query->whereNotNull('confirmed_sent_at')->where('confirmed_status', '!=',  true);
                    break;

                case "uninvited":
                    $query->whereNull('confirmed_sent_at')->whereNull('confirmed_at');
                    break;
            }

            switch ($request->registered) {
                case "true":
                    $query->whereNotNull('registered_at');
                    break;

                case "false":
                    $query->whereNull('registered_at');
                    break;
            }

            return $query->get();
        })
            ->map(function ($participation) {
                return $participation->participant;
            })
            ->unique(function ($participant) {
                return $participant->data['email'];
            })->values()->map(function ($participant) {
                foreach ($participant->data as $key => $value) {
                    $participant[$key] = $value;
                }
                return $participant;
            });

        return response()->json(['headers' => $headers, 'participants' => $participants], 200);
    }

    private function createParticipantUserIfNew(Participant $participant)
    {
        $participant_user = ParticipantUser::where('email', $participant->data['email'])->first();
        if (!$participant_user) {
            $password = Str::random(8);
            $participant_user = ParticipantUser::create([
                'name' => $participant->data['name'],
                'lastname' => $participant->data['lastname'],
                'email' => $participant->data['email'],
                'password' => Hash::make($password)
            ]);

            $participant_user->save();
            SendMail::dispatch($participant->data['email'], new ParticipantWelcome($participant_user, $password));
        }
    }

    public function apiLanding(Event $event)
    {
        $event = $event->makeHidden(['id', 'inviteed', 'has_confirmation', 'confirmation_key', 'producer', 'qr_template', 'confirmation_id', 'reconfirmation_id', 'qrcode_id', 'reqrcode_id', 'pretty_url', 'from_email', 'from_name', 'from_subject', 'sent_mail_count', 'view_mail_count', 'bounced_mail_count', 'twitter_shares', 'facebook_shares', 'linkedin_shares', 'created_at', 'updated_at', 'profile_id', 'devices', 'referers', 'profile', 'collectors', 'registerDevices']);

        unset($event->register_devices);

        $event->estimate = $event->estimate->makeHidden([
            'id', 'landing', 'mailings', 'mailings_quantity', 'polls', 'polls_quantity', 'register_keys', 'register_keys_quantity', 'free_tickets', 'status', 'company_id', 'created_at', 'updated_at', 'estimate_id', 'webpay_client_order_id', 'administration', 'graphical_design', "registering",
            "lanyards", "credentials", "collectors_half", "collectors_full", "development", "confirmed_amount", "lanyard_amount", "credential_amount", "collector_half_amount", "collector_full_amount", 'net_total', 'unpaid_ids', 'has_unpaid_ids', 'extras'
        ]);
        return response()->json($event);
    }

    public function scheduledDeliveriesIndex(Event $event)
    {

        return response()->json(['scheduled' => $event->scheduled_deliveries->reverse()->values()], 200);
    }

    public function scheduledDelivery(ScheduledDelivery $scheduled_delivery)
    {
        return response()->json(['scheduled' => $scheduled_delivery], 200);
    }

    public function scheduledDeliveriesDestroy(ScheduledDelivery $scheduled_delivery)
    {
        if ($scheduled_delivery->running) {
            return response()->json(['status' => 'running'], 409);
        }

        Job::whereIn('id', $scheduled_delivery->jobs)->delete();
        $scheduled_delivery->delete();

        return response()->json(['status' => 'success'], 200);
    }

    public function templateToPDF(Template $template, $uuid)
    {
        $participation = Participation::with('participant')->where('uuid', $uuid)->first();

        if ($participation) {
            $template = Template::find($template->id);
            $content = $template->content;
            $content = preg_replace('/<link.*>/U', '', $content);
            $body = $this->changeStaticBody($participation->event, $content);
            $current_body = $this->changeDynamicBody($participation->participant, $participation, $body, $template);
            $converter = new CssToInlineStyles();
            $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));

            $pdf = PDF::loadHTML($content);
            return $pdf->stream('template.pdf');
        }
    }

    public function templateToHTML(Template $template, $uuid)
    {
        $participation = Participation::with('participant')->where('uuid', $uuid)->first();

        if ($participation) {
            $template = Template::find($template->id);
            $content = $template->content;
            // $content = preg_replace('/<link.*>/U', '', $content);
            $body = $this->changeStaticBody($participation->event, $content);
            $current_body = $this->changeDynamicBody($participation->participant, $participation, $body, $template);
            $converter = new CssToInlineStyles();
            $content =  $converter->convert($current_body, file_get_contents(__DIR__ . '/bootstrap.min.css'));

            return view('template');
        }

        return abort(404);
    }

    public function rawTemplateToHTML(Template $template)
    {
        $template = Template::find($template->id);

        if ($template) {
            $content = $template->content;
            $converter = new CssToInlineStyles();
            $content =  $converter->convert($content, file_get_contents(__DIR__ . '/bootstrap.min.css'));

            return view('template');
        }

        return abort(404);
    }

    public function qrTest($uuid)
    {
        Storage::disk('public')->put('public/' . $uuid . '.png', QrCode::format('png')->size(300)->generate($uuid));
        return Storage::url($uuid . '.png');
    }

    private function useZohoEmail(Event $event, Template $template, $subject, $participant_ids)
    {
        $sending = $event->sendings()->create([
            'template_id' => $template->id,
            'subject' => $subject
        ]);
        $sending->participations()->sync($participant_ids);

        $formatted_ids = [];
        foreach ($participant_ids as $id) {
            $participation = Participation::where('event_id', $event->id)->where('participant_id', $id)->first();
            array_push($formatted_ids, ["zoho_id" =>  $participation->zoho_id]);
        }

        $client = new \GuzzleHttp\Client();
        $url = "https://integraciones.ndt.cl/venoot/enviaCampanaZoho.php";

        $data = [
            'id_evento' => $event->id,
            'id_envio' => $sending->id,
            'asunto' => $subject,
            'destinatarios' => $formatted_ids
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
    }
}
