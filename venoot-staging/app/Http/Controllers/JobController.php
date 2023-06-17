<?php

namespace App\Http\Controllers;

use App\Event;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller
{
    public function eventJobsIndex(Event $event)
    {
        return response()->json(['jobs' => $event->jobs], 200);
    }

    public function delete(Job $job)
    {
        if (Gate::allows('delete-jobs', $job)) {
            $job->delete();
            return response()->json(null, 204);
        }

        return response()->json(null, 500);
    }
}
