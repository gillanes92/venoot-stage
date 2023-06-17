<?php

namespace App\Http\Controllers;

use App\Database;
use App\Device;
use App\Participant;
use App\Participation;
use App\Registration;
use App\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Requests\ParticipantRequest;
use Illuminate\Http\Request;
use App\ParticipantOrder;
use App\Exports\ParticipantExport;

use Carbon\Carbon;
use PDF;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Database $database)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasPermissionTo('show participant')) {
            return response()->json(['participants' => $database->participants()->with('participant_orders')->get()], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function participationsWithEvent($email)
    {
        $database_ids = Auth::user()->company->databases()->pluck('id');
        $participants = Participant::whereJsonContains('data->email', $email)->whereIn('database_id', $database_ids)->with('participations.event')->get();
        $orders = ParticipantOrder::whereIn('participant_id', $participants->pluck('id'))->get();
        return response()->json(['participations' => $participants, 'orders' => $orders], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Database $database, ParticipantRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasPermissionTo('edit participant')) {

            $participant = Participant::create([
                'data' => $request->data,
                'database_id' => $database->id,
            ]);

            $client = new \GuzzleHttp\Client();
            $url = "https://www.zohoapis.com/crm/v2/functions/procesabd/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604";

            try {
                $client->post($url, [
                    'idDB' => $database->id,
                    'idCustomer' => $user->customer_id,
                    'token' => 'ukD32xcBdqC4avXM'
                ]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::debug($error);
            }

            return response()->json(['participants' => $database->participants], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function massStore(Database $database, Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasPermissionTo('edit participant')) {

            $request->validate([
                'participants' => "required|array",
            ]);

            foreach ($request->participants as $participant) {
                $this->store($database, new ParticipantRequest($participant));
            }

            $client = new \GuzzleHttp\Client();
            $url = "https://www.zohoapis.com/crm/v2/functions/procesabd/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604";

            try {
                $client->post($url, [
                    'idDB' => $database->id,
                    'idCustomer' => $user->customer_id,
                    'token' => 'ukD32xcBdqC4avXM'
                ]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::debug($error);
            }

            return response()->json(['participants' => $database->participants], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function storeFromControlPanel(Database $database, ParticipantRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->hasPermissionTo('edit participant')) {

            $participant = Participant::create([
                'data' => $request->data,
                'database_id' => $database->id,
            ]);

            $participant = array_merge($participant->toArray(), $participant->data);
            unset($participant['data']);

            $client = new \GuzzleHttp\Client();
            $url = "https://www.zohoapis.com/crm/v2/functions/procesabd/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604";

            try {
                $client->post($url, [
                    'idDB' => $database->id,
                    'idCustomer' => $user->customer_id,
                    'token' => 'ukD32xcBdqC4avXM'
                ]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::debug($error);
            }

            return response()->json(['participant' => $participant], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Database $database, Participant $participant)
    {
        if (Gate::allows('show-participant', $database, $participant)) {
            return response()->json(['participant' => $participant], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Database $database, Participant $participant, ParticipantRequest $request)
    {
        $user = Auth::user();
        if (Gate::allows('edit-participant', $participant)) {
            $participant->data = $request->data;
            $participant->save();

            $client = new \GuzzleHttp\Client();
            $url = "https://www.zohoapis.com/crm/v2/functions/procesabd/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604";

            try {
                $client->post($url, [
                    'idDB' => $database->id,
                    'idCustomer' => $user->customer_id,
                    'token' => 'ukD32xcBdqC4avXM'
                ]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::debug($error);
            }

            return response()->json(['participants' => $database->participants], 200);
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
    public function destroy(Database $database, Participant $participant)
    {
        $user = Auth::user();
        if (Gate::allows('edit-participant', $participant)) {
            $participant->delete();

            $client = new \GuzzleHttp\Client();
            $url = "https://www.zohoapis.com/crm/v2/functions/procesabd/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604";

            try {
                $client->post($url, [
                    'idDB' => $database->id,
                    'idCustomer' => $user->customer_id,
                    'token' => 'ukD32xcBdqC4avXM'
                ]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
                Log::debug($error);
            }

            return response()->json(['participants' => $database->participants], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function export(Participant $participant)
    {
        return (new ParticipantExport($participant))->download('participant.xlsx');
    }

    // public function webExport(Participant $participant)
    // {
    //     $databases = $participant->database->company->databases;

    //     return view('exports.participant', [
    //         'databases' => $databases, 'participant' => $participant
    //     ]);
    // }

    public function register(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'device_id' => 'required|string:exists:devices,ident'
        ]);

        $participation = Participation::where('uuid', $request->uuid)->first();

        if ($participation) {
            $participation->registered_at = Carbon::now();
            $participation->save();

            $device = Device::where('ident', $request->device_id)->first();

            Registration::create(['participation_id' => $participation->id, 'activity_id' => $device->activity->id, 'user_id' => Auth::user()->id]);

            return response()->json(['participant' => $participation->participant], 200);
        }
    }

    public function confirmsFromApp(Event $event, Participant $participant)
    {
        $participation = Participation::firstOrCreate(['event_id' => $event->id, 'participant_id' => $participant->id], ['uuid' => (string) Str::uuid()]);

        if ($participation->confirmed_status) {
            return response()->json(null, 409);
        }

        $participation->confirmed_status = true;
        $participation->confirmed_at = Carbon::now()->toDateTimeString();
        $participation->save();

        $participant = array_merge($participant->toArray(), $participant->data);
        unset($participant['data']);
        $participant = array_merge($participant, $participation->toArray());
        unset($participant['participant']);
        $participant['participation_id'] = $participant['id'];
        $participant['id'] = $participant['participant_id'];
        unset($participant['participant_id']);

        return response()->json(['participant' => $participant], 200);
    }

    public function registerFromApp(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'device_id' => 'required|string|exists:devices,ident'
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

            $device = Device::where('ident', $request->device_id)->first();

            Registration::create(['participation_id' => $participation->id, 'activity_id' => $device->activity->id, 'user_id' => Auth::user()->id]);

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

    public function subscribe(Participant $participant, Request $request)
    {
        $request->validate([
            'expo_token' => 'required|string'
        ]);

        $participant->expo_token = $request->expo_token;
        $participant->save();

        return response()->json(null, 204);
    }

    public function unsubscribe(Participant $participant)
    {
        $participant->expo_token = null;
        $participant->save();

        return response()->json(null, 204);
    }

    public function printQrWithTemplate($uuid)
    {
        //  * Unless otherwise mentioned, all dimensions are in points (1/72 in).  The
        //  * coordinate origin is in the top left corner, and y values increase
        //  * downwards.

        $data = [
            'defaultPaperSize' => array(0, 0, 172, 213),
            'qrSize' => 100
        ];

        PDF::setOptions($data);

        $participation = Participation::where('uuid', $uuid)->first();
        // return view('templates/' . ($participation->event->qr_template ?? 'qr_default'), ['participation' => $participation]);

        $pdf = PDF::loadView('templates/' . ($participation->event->qr_template ?? 'qr_default'), ['participation' => $participation, 'data' => (object) $data]);
        return $pdf->setPaper($data['defaultPaperSize'])->stream(($participation->event->qr_template ?? 'qr_default') . '.pdf');
    }

    public function testPrintQrWithTemplate($uuid)
    {
        $data = [
            'defaultPaperSize' => array(0, 0, 204, 184),
            'qrSize' => 100
        ];

        PDF::setOptions($data);

        $participation = Participation::where('uuid', $uuid)->first();
        // return view('templates/' . ($participation->event->qr_template ?? 'qr_default'), ['participation' => $participation]);

        $pdf = PDF::loadView('templates/' . ($participation->event->qr_template ?? 'qr_default'), ['participation' => $participation, 'data' => (object) $data]);
        return $pdf->setPaper($data['defaultPaperSize'])->stream(($participation->event->qr_template ?? 'qr_default') . '.pdf');
    }

    public function get(Participation $participation)
    {
        return response()->json(['participation' => $participation], 200);
    }

    public function addZohoId(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'webinar_id' => 'required|integer|exists:events,id',
            'participants' => 'required|array',
            'participants.*.id' => 'required|integer|exists:participants,id',
            'participants.*.zoho_id' => 'required|string',
        ]);

        if ($request->token != 'ukD32xcBdqC4avXM') {
            return response()->json(['status' => 'access_key_error'], 403);
        }

        $event = Event::find($request->webinar_id);
        $result = [];
        foreach ($request->participants as $p) {
            $participant = Participant::find($p['id']);

            if ($participant->database_id == $event->profile->database_id) {
                $participation = Participation::firstOrNew(['event_id' => $event->id, 'participant_id' => $p['id']], ['uuid' => (string) Str::uuid()]);
                $participation->zoho_id = $p['zoho_id'];
                $participation->save();

                array_push($result, $p['zoho_id']);
            }            
        }

        return response()->json(['status' => 'success', 'added' => $result], 200);
    }
}
