<?php

namespace App\Http\Controllers;

use App\Actor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\ActorRequest;
use Illuminate\Support\Facades\DB;

class ActorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show actor')) {
            return response()->json(['actors' => $user->company->actors()->orderBy('name')->orderBy('lastname')->get()], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function actorsByEvent($id)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show actor')) {

          $actors = DB::table('actor_event')
                    ->selectRaw("actors.*")
                    ->join('actors', 'actors.id', '=', 'actor_event.actor_id')
                    ->where('actor_event.event_id', $id)->get();

            return response()->json(['actors' => $actors]);
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
    public function store(ActorRequest $request)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('edit actor')) {
            $actor = Actor::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'category' => $request->category,
                'email' => $request->email,
                'job' => $request->job,
                'organization' => $request->organization,
                'description' => $request->description,
                'photo' => $request->photo,
                'links' => $request->links,
                'company_id' => $user->company->id,
            ]);

            return response()->json(['actors' => $actor->company->actors], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function show(Actor $actor)
    {
        if (Gate::allows('show-actor', $actor)) {
            return response()->json(['actor' => $actor], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function update(ActorRequest $request, Actor $actor)
    {
        $user = Auth::user();
        if (Gate::allows('edit-actor', $actor)) {
            $actor->name = $request->name;
            $actor->lastname = $request->lastname;
            $actor->category = $request->category;
            $actor->email = $request->email;
            $actor->job = $request->job;
            $actor->organization = $request->organization;
            $actor->description = $request->description;
            $actor->photo = $request->photo;
            $actor->links = $request->links;
            $actor->company_id = $request->company_id;
            $actor->save();

            return response()->json(['actors' => $actor->company->actors], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actor $actor)
    {
        $user = Auth::user();
        if (Gate::allows('edit-actor', $actor)) {
            $actor->delete();
            return response()->json(['actors' => $user->company->actors], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }
}
