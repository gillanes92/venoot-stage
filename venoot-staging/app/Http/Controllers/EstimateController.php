<?php

namespace App\Http\Controllers;

use App\Estimate;
use App\Constant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\EstimateRequest;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show event')) {
            $estimates = $user->company->estimates
                ->where('estimate_id', null)
                ->map(function ($item, $key) {
                    if ($item->event) {
                        $item->event->start_time = Carbon::parse($item->event->start_time)->format('H:i');
                        $item->event->end_time = Carbon::parse($item->event->end_time)->format('H:i');
                    }
                    return $item;
                })->values();
            return response()->json(['estimates' => $estimates], 200);
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
    public function store(EstimateRequest $request)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('create event')) {

            $mailings_quantity = $request->mailings ? $request->mailings_quantity : 0;
            $polls_quantity = $request->polls ? $request->polls_quantity : 0;
            $register_keys_quantity = $request->mailings ? $request->register_keys_quantity : 0;

            $estimate = $user->company->estimates()->create([
                'landing' => $request->landing,
                'confirmation_form' => $request->confirmation_form,
                'mailings' => $request->mailings,
                'mailings_quantity' => $mailings_quantity,
                'polls' => $request->polls,
                'polls_quantity' => $polls_quantity,
                'register_keys' => $request->register_keys,
                'register_keys_quantity' => $register_keys_quantity,
                'ticket_sale' => $request->ticket_sale,
                'free_tickets' => $request->free_tickets,
                'administration' => $request->administration,
                'graphical_design' => $request->graphical_design,
                'registering' => $request->registering,
                'lanyards' => $request->lanyards,
                'credentials' => $request->credentials,
                'collectors_half' => $request->collectors_half,
                'collectors_full' => $request->collectors_full,
                'development' => $request->development,
                'confirmed_amount' => $request->confirmed_amount,
                'lanyard_amount' => $request->lanyard_amount,
                'credential_amount' => $request->credential_amount,
                'collector_half_amount' => $request->collector_half_amount,
                'collector_full_amount' => $request->collector_full_amount,
                'app' => $request->app,
            ]);

            return response()->json(['estimate' => $estimate], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Estimate  $estimate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estimate $estimate)
    {
        $company = $estimate->company;
        if (Gate::allows('edit-event', $estimate)) {
            $estimate->delete();
            return response()->json(['estimates' => $company->estimates], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }
}
