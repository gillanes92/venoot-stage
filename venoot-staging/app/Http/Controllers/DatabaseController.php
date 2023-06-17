<?php

namespace App\Http\Controllers;

use App\Database;
use App\Event;
use App\Participation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Http\Requests\DatabaseRequest;
use App\Exports\DatabasesExport;
use App\Exports\VerifyMailExport;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class DatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (Gate::allows('show-database', $user)) {
            return response()->json(['databases' => $user->company->databases], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWithAccessKeys()
    {
        $user = Auth::user();
        if (Gate::allows('show-database', $user)) {
            return response()->json(['databases' => $user->company->databases->makeVisible('access_key')], 200);
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
    public function store(DatabaseRequest $request)
    {
        $user = Auth::user();
        if (Gate::allows('show-database', $user)) {

            $database = Database::create([
                'name' => $request->name,
                'fields' => $request->fields,
                'company_id' => $user->company->id,
            ]);

            $database->profiles()->create([
                'name' => 'All',
                'conditions' => [],
            ]);

            $this->sendDBDataToZoho($database, $user);

            return response()->json(['databases' => $database->company->databases, 'database_id' => $database->id], 200);
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
    public function show(Database $database)
    {
        $user = Auth::user();
        if (Gate::allows('show-database', $user)) {
            return response()->json(['database' => $database], 200);
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
    public function update(DatabaseRequest $request, Database $database)
    {
        $user = Auth::user();
        if (Gate::allows('edit-database', $database)) {
            $database->name = $request->name;
            $database->fields = $request->fields;
            $database->verifications = $request->verifications;
            $database->save();

            $this->sendDBDataToZoho($database, $user);

            return response()->json(['databases' => $database->company->databases], 200);
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
    public function destroy(Database $database)
    {
        $user = Auth::user();
        if (Gate::allows('edit-database', $user, $database)) {
            $database->delete();
            return response()->json(['databases' => $database->company->databases], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function export(Database $database)
    {
        return (new DatabasesExport($database))->download('database.xlsx');
    }

    public function databaseForApp(Event $event)
    {
        return response()->json($event->profile->database, 200);
    }

    public function exportView(Database $database)
    {
        return view('exports/database', ['database' => $database, 'events' => $database->events]);
    }

    public function verifyEmails(Database $database)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $filename = 'verify-' . Str::uuid() . '.csv';
        $file_path = Storage::disk('public')->path('verify_files/' . $filename);

        Excel::store(new VerifyMailExport($database), 'verify_files/' . $filename, 'public', \Maatwebsite\Excel\Excel::CSV);

        $mimeType = finfo_file($finfo, $file_path);

        $key = "JOjJk9p6q31nNKsTqe8Qb";
        $url = 'https://app.verificaremails.com/api/verifyApiFile?secret=' . $key . '&filename=' . $filename;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $settings['file_contents'] = new \CURLFile($file_path, $mimeType, $filename);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $settings);
        $file_id = curl_exec($ch); //you need to save this FILE ID for get file status and download reports in future

        $error = null;
        if (!$file_id) {
            $error = curl_error($ch);
        }

        curl_close($ch);

        return response()->json(['file_id' => $file_id, 'error' => $error], 200);
    }

    public function advanced(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:databases,id'
        ]);

        $databases = Database::whereIn('id', $request->ids)->get();
        $headers = $databases->flatMap(function ($database) {
            return $database->fields;
        })->unique('key')->values()->map(function ($field) {
            $field['text'] = $field['name'];
            $field['value'] = $field['key'];
            return $field;
        });

        $participants = $databases->flatMap(function ($database) {
            return $database->participants;
        })->unique(function ($participant) {
            return $participant->data['email'];
        })->values()->map(function ($participant) {
            foreach ($participant->data as $key => $value) {
                $participant[$key] = $value;
            }
            return $participant;
        });

        return response()->json(['headers' => $headers, 'participants' => $participants], 200);
    }

    function externalAccess(Database $database, Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'webinar_id' => 'sometimes|integer|exists:events,id',
        ]);

        if ($request->token != 'ukD32xcBdqC4avXM') {
            return response()->json(['status' => 'Acess Key Error'], 403);
        }

        $collection = $database->participants()->get(['id', 'data'])->map(function ($item, $key) use ($request) {
            $data = collect($item->data)->only(['name', 'lastname', 'email', 'abrev', 'telefono', 'empresa', 'especialidad', 'profesion', 'cargo', 'nacionalidad', 'charla', 'seccion', 'necesidad', 'limitante', 'comodidades', 'interes']);
            foreach (['name', 'lastname', 'email', 'abrev', 'telefono', 'empresa', 'especialidad', 'profesion', 'cargo', 'nacionalidad', 'charla', 'seccion', 'necesidad', 'limitante', 'comodidades', 'interes'] as $dkey) {
                if (!isset($data[$dkey])) {
                    $data[$dkey] = "";
                }
            }
            $data['id'] = $item->id;

            if ($request->has('webinar_id')) {
                $event = Event::find($request->webinar_id);
                $participation = Participation::where('event_id', $event->id)->where('participant_id', $item->id)->first();
                if ($participation) {
                    $data['zoho_id'] = $participation->zoho_id;
                } else {
                    $data['zoho_id'] = 'not_found';
                }
            }

            return $data;
        });


        return response()->json(['database' => $collection], 200);
    }

    function updateExternalAccess(Database $database, Request $request)
    {
        $request->validate([
            'access' => 'required|boolean'
        ]);

        if ($request->access) {

            if (is_null($database->access_key)) {
                $database->access_key = $this->regenerateKey();
            }
            $database->external_access = true;
        } else {
            $database->external_access = false;
        }
        $database->save();

        return response()->json(['external_access' => $database->external_access, 'access_key' => $database->access_key], 200);
    }

    function updateAccessKey(Database $database)
    {
        $database->access_key = $this->regenerateKey();
        $database->save();

        return response()->json(['access_key' => $database->access_key], 200);
    }

    function externalAccessView(Database $database)
    {
        $access_key = Input::get('access_key');

        if (!$database->external_access || $database->access_key != $access_key) {
            abort(403);
        }

        return view('database-external', ['database' => $database, 'access_key' => $access_key]);
    }

    private function regenerateKey()
    {
        $random_key = null;
        do {
            $random_key = Str::random(16);
        } while (Database::where('access_key', $random_key)->count() > 0);

        return $random_key;
    }

    private function sendDBDataToZoho(Database $database, User $user)
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://www.zohoapis.com/crm/v2/functions/procesabd/actions/execute?auth_type=apikey&zapikey=1003.44241de1c98b634b3705607bb21ce830.f04177502203b6aa76b3c195e9dbd604";

        $data = [
            'idDB' => $database->id,
            'idCustomer' => $user->customer_id,
            'token' => 'ukD32xcBdqC4avXM'
        ];

        Log::debug($data);

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
