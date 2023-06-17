<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Database;
use App\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Database $database, ProfileRequest $request)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('create database')) {

            $profile = $database->profiles()->create([
                'name' => $request->name,
                'conditions' => $request->conditions,
            ]);

            return response()->json(['profile' => $profile], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Database $database, Profile $profile, ProfileRequest $request)
    {
        if (Gate::allows('edit-database', $database)) {
            $profile->name = $request->name;
            $profile->conditions = $request->conditions;
            if ($profile->save()) {
                return response()->json(['profile' => $profile], 200);
            } else {
                return response()->json(null, 500);
            }
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function participants(Profile $profile)
    {
        return response()->json(['participants' => $profile->participants], 200);
    }

    public function database(Profile $profile)
    {
        return response()->json(['database' => $profile->database], 200);
    }
}
