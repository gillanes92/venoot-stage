<?php

namespace App\Http\Controllers;

use App\Event;
use App\Activity;
use App\AppQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        $user = Auth::user();
        // if (Gate::allows('index-databases')) {
        return response()->json(['activities' => $event->actitivies], 200);
        // } else {
        //     return response()->json(['error' => 'unauthorized'], 403);
        // }
    }

    public function activitiesByEvent($id)
    {
        $user = Auth::user();

        $activities =  Activity::where('event_id', $id)->get();

        return response()->json(['activities' => $activities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {
        $user = Auth::user();
        $activity = Activity::create([
            'name' => $request->name,
            'location' => $request->location,
            'date' => $request->date,
            'event_id' => $event->id,
        ]);

        return response()->json(['activities' => $event->actitivies], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event, Activity $activity)
    {
        $activity->name = $request->name;
        $activity->location = $request->name;
        $activity->date = $request->date;

        if ($activity->save()) {
            return response()->json(['actitivies' => $event->actitivies], 200);
        } else {
            return response()->json(null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAppQuestions(Activity $activity)
    {
        return response()->json(['appQuestions' => $activity->appQuestions], 200);
    }

    public function addAppQuestion(Activity $activity, Request $request)
    {
        $request->validate([
            'question' => 'required|string',
        ]);

        $appQuestion = $activity->appQuestions()->create([
            'question' => $request->question,
        ]);

        return response()->json(['appQuestion' => $appQuestion], 200);
    }

    public function toggleAppQuestion(AppQuestion $appQuestion)
    {
        $appQuestion->answered = !$appQuestion->answered;
        $appQuestion->save();

        return response()->json(['appQuestion' => $appQuestion], 200);
    }

    public function deleteAppQuestion(AppQuestion $appQuestion)
    {
        $appQuestion->delete();
        return response()->json([null], 204);
    }
}
