<?php

namespace App\Http\Controllers;

use App\Chatter;
use App\Event;
use App\Events\WebinarJoinRequestEvent;
use App\Events\WebinarJoinResponseEvent;
use App\Participation;
use App\WebinarJoinRequest;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use OpenTok\OpenTok;
use OpenTok\Role;
use GuzzleHttp\Exception\ClientException;

class ChatterController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:participant,presenter,host',
                'token' => 'required|uuid',
                'presenter_uuid' => 'nullable|uuid',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'login_error'], 401);
        }

        $API_KEY = config('vonage.api_key');
        $API_SECRET = config('vonage.api_secret');
        $apiObj = new OpenTok($API_KEY, $API_SECRET);

        $event = null;
        switch ($request->type) {
            case 'participant':
                $participation = Participation::where('uuid', $request->token)->first();

                if ($participation) {
                    $event = $participation->event;
                }

                if ($event) {
                    $chatter = Chatter::firstOrCreate(['token' => $request->token], ['event_id' => $event->id, 'type' => $request->type]);
                    $token = $this->guard()->login($chatter);
                    return response()->json([
                        'type' => 'participant',
                        'event' => [
                            'id' => $event->id,
                            'name' => $event->name,
                        ]
                    ], 200)->header('Authorization', $token);
                }
                break;

            case 'presenter':
                $event = Event::where('presenter_webinar_link', $request->token)->first();

                if ($event) {
                    $chatter = Chatter::firstOrCreate(['token' => $request->token, 'presenter_uuid' => $request->presenter_uuid], ['event_id' => $event->id, 'type' => $request->type]);
                    $token = $this->guard()->login($chatter);
                    return response()->json([
                        'type' => 'presenter',
                        'presenter_uuid' => $request->presenter_uuid,
                        'session_id' => $event->vonage_session_id,
                        'token' => null,
                        'event' => [
                            'id' => $event->id,
                            'name' => $event->name,
                        ]
                    ], 200)->header('Authorization', $token);
                }
                break;

            case 'host':
                $event = Event::where('host_webinar_link', $request->token)->first();
                $vonage_token = $apiObj->generateToken($event->vonage_session_id, ['role' => Role::MODERATOR]);

                if ($event) {
                    $chatter = Chatter::firstOrCreate(['token' => $request->token], ['event_id' => $event->id, 'type' => $request->type]);
                    $token = $this->guard()->login($chatter);
                    return response()->json(
                        [
                            'type' => 'host',
                            'session_id' => $event->vonage_session_id,
                            'token' => $vonage_token,
                            'event' => [
                                'id' => $event->id,
                                'name' => $event->name,
                            ],
                            'join_requests' => $event->join_requests()->with(['chatter'])->get(),
                        ],
                        200
                    )->header('Authorization', $token);
                }
                break;
        }

        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(null, 204);
    }

    public function refresh()
    {

        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(null, 204)
                ->header('Authorization', $token);
        }

        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    public function me()
    {
        return response()->json(['data' => $this->guard()->user()->toArray()], 200);
    }

    public function join(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'message' => 'required|string|nullable',
        ]);

        $user = $this->guard()->user();
        if ($user->type == 'presenter') {
            broadcast(new WebinarJoinRequestEvent($user, $request->name, $request->message));
            $user->join_requests()->create(['name' => $request->name, 'message' => $request->message]);

            return response()->json(null, 204);
        }

        return response()->json(null, 403);
    }

    public function responseJoin(Request $request)
    {
        $request->validate([
            'status' => 'required|boolean',
            'presenter_uuid' => 'required|uuid',
        ]);

        $API_KEY = config('vonage.api_key');
        $API_SECRET = config('vonage.api_secret');
        $apiObj = new OpenTok($API_KEY, $API_SECRET);

        $user = $this->guard()->user();
        $event = Event::find($user->event_id);
        if ($user->type == 'host') {

            $vonage_token = null;
            if ($request->status) {
                $vonage_token = $apiObj->generateToken($event->vonage_session_id, ['role' => Role::PUBLISHER]);
            }
            broadcast(new WebinarJoinResponseEvent($request->presenter_uuid, $event->id, $vonage_token));
            Chatter::where('presenter_uuid', $request->presenter_uuid)->first()->join_requests()->delete();       
            return response()->json(null, 204);
        }

        return response()->json(null, 403);
    }

    private function guard()
    {
        return Auth::guard('chatter');
    }
}
