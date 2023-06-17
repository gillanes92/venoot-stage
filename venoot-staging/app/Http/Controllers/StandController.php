<?php

namespace App\Http\Controllers;

use App\Stand;
use App\Event;
use App\Mail\MeetingTaken;
use App\StandMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StandController extends Controller
{

  private function access_token()
  {
    $client = new \GuzzleHttp\Client();
    $url = "https://api.archiebot.com/api/oauth/access_token";

    $form_params['grant_type'] = 'password';
    $form_params['client_id'] = config('livewebinar.client_id');
    $form_params['client_secret'] = config('livewebinar.client_secret');
    $form_params['username'] = config('livewebinar.login');
    $form_params['password'] = config('livewebinar.password');

    try {
      $response = $client->post($url, ['form_params' => $form_params]);
      $json_response = json_decode($response->getBody()->getContents());
      return $json_response->accessToken->access_token;
    } catch (\Exception $e) {
      return null;
    }
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Event $event)
  {
    return response()->json($event->stands, 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Event $event, Request $request)
  {
    $request->validate([
      'type' => 'sometimes|string|in:small,medium,large,xlarge',
      'name' => 'required|string',
      'description' => 'required|string',
      'color' => 'required|string',
      'multimedia' => 'sometimes|array',
      'multimedia.*' => 'nullable|string',
      'interval' => 'sometimes|integer'
    ]);

    $stand = $event->stands()->create(
      $request->only(['type', 'name', 'description', 'color', 'multimedia', 'interval'])
    );
    $stand->refresh();
    // $this->createMeetings($stand);
    // $stand->makeHidden('event')->meetings;

    return response()->json($stand, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Stand  $stand
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Stand $stand)
  {
    $request->validate([
      'type' => 'sometimes|string|in:small,medium,large,xlarge',
      'name' => 'sometimes|string',
      'description' => 'sometimes|string',
      'color' => 'sometimes|string',
      'multimedia' => 'sometimes|array',
      'multimedia.*' => 'nullable|string',
      'interval' => 'sometimes|integer'
    ]);

    $stand->update(
      $request->only(['type', 'name', 'description', 'color', 'multimedia', 'interval'])
    );
    $stand->refresh();

    return response()->json(['stand' => $stand], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Stand  $stand
   * @return \Illuminate\Http\Response
   */
  public function show(Stand $stand)
  {
    return response()->json(['stand' => $stand], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Stand  $stand
   * @return \Illuminate\Http\Response
   */
  public function destroy(Stand $stand)
  {
    $stand->delete();
    return response()->json(null, 204);
  }

  public function meetings(Stand $stand)
  {
    return response()->json($stand->meetings, 200);
  }

  public function meeting(StandMeeting $meeting)
  {
    return response()->json($meeting, 200);
  }

  public function updateMeeting(Request $request, StandMeeting $meeting)
  {
    $request->validate([
      'participation_id' => 'sometimes|nullable|exists:participations',
      'status' => 'sometimes|in:closed,open,taken',
    ]);

    $meeting->update($request->only(['participation_id', 'status']));

    return response()->json($meeting, 200);
  }

  public function updateMeetings(Request $request)
  {
    $request->validate([
      'meetings' => 'required|array',
      'meetings.*' => 'integer|exists:stand_meetings,id',
      'participation_id' => 'sometimes|nullable|exists:participations,id',
      'status' => 'sometimes|in:closed,open,taken',
    ]);

    $meetings = StandMeeting::whereIn('id', $request->meetings);
    $meetings->update($request->only(['participation_id', 'status']));
    return response()->json($meetings->get(), 200);
  }

  public function takeMeeting(StandMeeting $meeting)
  {
    $user = Auth::guard('streaming')->user();

    if ($meeting->status == 'taken') {
      return response()->json(['error' => 'taken', 'message' => 'meeting is already taken'], 409);
    }

    if ($user->meetings()->where('stand_id', $meeting->stand_id)->exists()) {
      return response()->json(['error' => 'conflict', 'message' => 'user already has meeting with the same stand'], 409);
    }

    $stand_manager = $meeting->stand->manager;
    if (!$stand_manager) {
      return response()->json(['error' => 'empty', 'message' => 'stand has no manager'], 409);
    }

    $access_token = $this->access_token();
    $client = new \GuzzleHttp\Client();
    $url = "https://api.archiebot.com/api/users/" . $stand_manager->lw_id . "/widgets";

    if ($access_token) {

      $headers = [
        'Authorization' => 'Bearer ' . $access_token,
        'Accept' => 'application/vnd.archiebot.v1+json',
      ];

      $form_params = [
        'name' => $meeting->stand->name . " " . $meeting->start,
        'agenda' => $meeting->start . " - " . $meeting->end,
        'type' => 'scheduled',
        'duration' => $meeting->stand->interval,
        'timezone' => 'America/Santiago',
        'manual_confirm_registrants' => 1,
      ];

      try {
        $response = $client->post($url, ['headers' => $headers, 'form_params' => $form_params]);
        $json_response = json_decode($response->getBody()->getContents());

        $meeting->manager_link = $json_response->data->hosted_at->host;
        $meeting->participant_link = $json_response->data->hosted_at->presenter;

        $meeting->status = 'taken';
        $meeting->participation_id = $user->id;
        $meeting->save();

        Mail::to($user->participant->data['email'])->send(new MeetingTaken($meeting, 'participant'));
        Mail::to($meeting->manager->email)->send(new MeetingTaken($meeting, 'host'));
      } catch (\Exception $e) {
        $response = null;
        return response()->json($e->getMessage(), 503);
      }
    } else {
      return response()->json(null, 401);
    }

    return response()->json(['meeting' => $meeting, 'token' => $access_token], 200);
  }

  private function createMeetings(Stand $stand)
  {
    $event = $stand->event;
    $activities_by_date = $event->activities()->where('show_in_landing', true)->get()->sortByDesc('order')->sortBy('start_time')->sortBy('date')->groupBy('date');
    $meetings = [];
    foreach ($activities_by_date as $date => $activities) {
      $start_time = $activities->map(function ($activity) {
        return $activity->start_time;
      })->min();
      $end_time = $activities->map(function ($activity) {
        return $activity->end_time;
      })->max();

      $start_date = Carbon::createFromFormat('Y-m-d H:i',  $date . ' ' . $start_time, 'UTC');
      $end_date = Carbon::createFromFormat('Y-m-d H:i',  $date . ' ' . $end_time, 'UTC');
      $meeting_intervals = \Carbon\CarbonPeriod::since($start_date)->minutes($stand->interval)->until($end_date)->toArray();
      $meetings[$date] = [];
      for ($i = 0; $i < count($meeting_intervals) - 1; $i++) {
        $meeting = $stand->meetings()->create([
          'start' => $meeting_intervals[$i],
          'end' => $meeting_intervals[$i + 1],
        ]);
        $meeting->refresh();
        array_push($meetings[$date], $meeting);
      }
    }

    return response()->json($meetings, 200);
  }
}
