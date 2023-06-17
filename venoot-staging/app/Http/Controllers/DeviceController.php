<?php

namespace App\Http\Controllers;

use App\Device;
use App\Event;
use App\Participant;
use App\Participation;
use App\Registration;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function index(Event $event)
    {
        return response()->json(['devices' => $event->registerDevices], 200);
    }

    public function store(Event $event, Request $request)
    {
        $request->validate([
            'ident' => 'required|string',
            'activity_id' => 'required|integer|exists:activities,id'
        ]);

        $user = Auth::user();
        $user->devices()->delete();

        $event->registerDevices()->create(['user_id' => $user->id, 'ident' => $request->ident, 'activity_id' => $request->activity_id]);
        return response()->json(['devices' => $event->registerDevices], 200);
    }

    public function destroy(Device $device)
    {
        $event = $device->event;

        if ($device->delete()) {
            return response()->json(['devices' => $event->registerDevices], 200);
        }

        return response()->json(['error' => true], 500);
    }

    public function events()
    {
        $events = auth()->user()->user->company->events()->without([
            'sponsors',
            'landing',
            'estimate',
            'profile',
            'tickets',
            'actors',
            'collectors',
            'registerDevices',
            'referers',
            'devices',
        ])
            ->where('category', '!=', 'Webinar')
            ->where('published', true)
            ->select('id', 'name')
            ->get()
            ->makeHidden(['sent_mail_count', 'view_mail_count', 'bounced_mail_count', 'status', 'sent_mail_count', 'twitter_shares', 'facebook_shares', 'linkedin_shares', 'referers', 'estimate',]);

        return response()->json(['events' => $events], 200);
    }

    public function activity(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'activity_id' => 'nullable|exists:activities,id',
        ]);

        $device = auth()->user();
        $device->event_id = $request->event_id;
        $device->activity_id = $request->activity_id;
        $device->save();

        return response()->json([],  204);
    }

    public function participants()
    {
        $event_id = auth()->user()->event->id;
        $participants = Participant::where('database_id', auth()->user()->event->profile->database_id)
            ->with(['participations' => function ($query) use ($event_id) {
                $query->where('event_id', $event_id);
            }])
            ->get();

        $results = [];
        foreach ($participants as $participant) {

            if ($participant->participations->count() == 0) {
                $participant->participation = $participant->participations()->create(['event_id' => $event_id, 'uuid' => (string) Str::uuid()]);
            } else {
                $participant->participation = $participant->participations->first();
                unset($participant->participations);
            }

            array_push($results, [
                'id' => $participant->participation->id,
                'uuid' => $participant->participation->uuid,
                'name' => $participant->data['name'],
                'lastname' => $participant->data['lastname'],
                'email' => $participant->data['email'],
                'confirmed_at' => $participant->participation->confirmed_at,
                'registered_at' => $participant->participation->registered_at,
            ]);
        }

        return response()->json(['participants' => $results], 200);
    }

    public function search(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|min:3',
        ]);

        $lowerslug = strtolower($request->slug);
        $event_id = auth()->user()->event->id;
        $participants = Participant::where('database_id', auth()->user()->event->profile->database_id)
            ->where(function ($query) use ($lowerslug) {
                $query->where('data->name', 'ILIKE', '%' . $lowerslug . '%');
                $query->orwhere('data->lastname', 'ILIKE', '%' . $lowerslug . '%');
                $query->orwhere('data->email', 'ILIKE', '%' . $lowerslug . '%');
            })
            ->with(['participations' => function ($query) use ($event_id) {
                $query->where('event_id', $event_id);
            }])
            ->get();

        $results = [];
        foreach ($participants as $participant) {
            if ($participant->participations->count() == 0) {
                $participation = $participant->participations()->create(['event_id' => $event_id, 'uuid' => (string) Str::uuid()]);
            } else {
                $participation = $participant->participations->first();
            }
            unset($participant->participations);

            array_push($results, [
                'id' => $participant->id,
                'participation_id' => $participation->id,
                'uuid' => $participation->uuid,
                'name' => $participant->data['name'],
                'lastname' => $participant->data['lastname'],
                'email' => $participant->data['email'],
                'confirmed_at' => $participation->confirmed_at,
                'registered_at' => $participation->registered_at,
            ]);
        }

        return response()->json(['count' => count($results), 'participants' => $results], 200);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        $participation = Participation::where('event_id', auth()->user()->event->id)
            ->where('uuid', $request->uuid)
            ->first();

        if ($participation) {
            if (!$participation->confirmed_at) {
                $participation->confirmed_at = Carbon::now()->toDateTimeString();
                $participation->confirmed_status = true;
                $participation->save();

                return response()->json(['participant' => [
                    'id' => $participation->id,
                    'uuid' => $participation->uuid,
                    'name' => $participation->participant->data['name'],
                    'lastname' => $participation->participant->data['lastname'],
                    'email' => $participation->participant->data['email'],
                    'confirmed_at' => $participation->confirmed_at,
                    'registered_at' => $participation->registered_at,
                ]], 200);
            }

            return response()->json([], 409);
        }

        return response()->json([], 404);
    }

    public function register(Request $request)
    {
        $request->validate([
            'uuid' => 'sometimes|nullable|uuid',
            'rut' => 'sometimes|nullable|string',
            'action' => 'required|string',
            'document' => 'sometimes|nullable|string',
        ]);

        if ($request->action == 'participations') {
            if ($request->document == 'qr' && $request->qr) {

                $participation = Participation::where('event_id', auth()->user()->event->id)
                    ->where('uuid', $request->uuid)->first();

                    if ($participation && auth()->user()->activity_id) {
                        $participant = $participation->participant;
                        $activity_key = 'actividad_' . auth()->user()->activity_id;
                        $participant->data[$activity_key] = 'si';
                        $participant->save();
                        $participant->refresh();

                        return response()->json(['participant' => [
                            'id' => $participation->id,
                            'uuid' => $participation->uuid,
                            'name' => $participation->participant->data['name'],
                            'lastname' => $participation->participant->data['lastname'],
                            'email' => $participation->participant->data['email'],
                            'confirmed_at' => $participation->confirmed_at,
                            'registered_at' => $participation->registered_at,
                        ]], 200);
                    }
            }

            elseif ($request->document == 'carnet' && $request->rut && auth()->user()->activity_id) {

                $participant = Participant::firstOrCreate(['data->rut' => $request->rut], ['database_id' => auth()->user()->event->profile->database_id]);
                $activity_key = 'actividad_' . auth()->user()->activity_id;
                $participant->data = array_merge($participant->data, [$activity_key => 'si']);
                $participant->save();
                $participation = Participation::firstOrCreate(['participant_id' => $participant->id, 'event_id' => auth()->user()->event->id] , ['uuid' => (string) Str::uuid()]);

                if (!$participation->confirmed_at) {
                    $participation->confirmed_at = Carbon::now()->toDateTimeString();
                    $participation->confirmed_status = true;
                }

                if (!$participation->registered_at) {
                    $participation->registered_at = Carbon::now()->toDateTimeString();
                    $participation->save();
                }

                return response()->json(['participant' => [
                    'id' => $participation->id,
                    'uuid' => $participation->uuid,
                    'email' => $participation->participant->data['rut'],
                    'confirmed_at' => $participation->confirmed_at,
                    'registered_at' => $participation->registered_at,
                ]], 200);
            }

        }

        elseif ($request->action == 'register'){

            if ($request->document == 'qr' && $request->qr) {
                $participation = Participation::where('event_id', auth()->user()->event->id)
                ->where('uuid', $request->uuid)->first();    
            }

            elseif ($request->document == 'carnet' && $request->rut) {
                $participant = Participant::where('data->rut', $request->rut)->where('database_id', auth()->user()->event->profile->database_id)->first();

                if ($participant) {
                    $participation = Participation::where('participant_id', $participant->id)->where('event_id', auth()->user()->event->id)->first();
                } else {
                    $participation = null;
                }
            }

            if ($participation && !$participation->registered_at) {
                if (!$participation->confirmed_at) {
                    $participation->confirmed_at = Carbon::now()->toDateTimeString();
                    $participation->confirmed_status = true;
                }

                $participation->registered_at = Carbon::now()->toDateTimeString();
                $participation->save();

                // Registration::create(['participation_id' => $participation->id, 'activity_id' => optional(auth()->user()->activity)->id, 'user_id' => auth()->user()->user->id]);

                auth()->user()->event->queue_prints()->create([
                    'participation_uuid' => $participation->uuid
                ]);

                return response()->json(['participant' => [
                    'id' => $participation->id,
                    'uuid' => $participation->uuid,
                    'name' => $participation->participant->data['name'],
                    'lastname' => $participation->participant->data['lastname'],
                    'email' => $participation->participant->data['email'],
                    'confirmed_at' => $participation->confirmed_at,
                    'registered_at' => $participation->registered_at,
                ]], 200);
            }
        }

        if ($participation) {
            return response()->json(['participant' => [
                'id' => $participation->id,
                'uuid' => $participation->uuid,
                'name' => $participation->participant->data['name'],
                'lastname' => $participation->participant->data['lastname'],
                'email' => $participation->participant->data['email'],
                'confirmed_at' => $participation->confirmed_at,
                'registered_at' => $participation->registered_at,
            ]], 409);
        }

        return response()->json([], 404);
    }

    public function print(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid'
        ]);

        $event = auth()->user()->event;
        $participation = Participation::where('uuid', $request->uuid)->where('event_id', $event->id)->first();

        if ($participation) {
            $event->queue_prints()->create([
                'participation_uuid' => $request->uuid
            ]);
    
            return response()->json(null, 204);
        }
        
        return response()->json(null, 404);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user !== null && Hash::check($request->password, $user->password)) {
            $device = Device::firstOrCreate(['ident' => $request->ident], ['user_id' => $user->id]);
            $token = auth('devices')->login($device);

            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
    }

    public function logout()
    {
        auth('devices')->logout();
        return response()->json([
            'status' => 'success',
        ], 200);
    }

    public function user()
    {
        $user = auth()->user();
        $user = $user->toArray();
        unset($user['user']);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function refresh()
    {
        if ($token = auth('devices')->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }

        return response()->json(['error' => 'refresh_token_error'], 401);
    }
}
