<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Order;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('show product')) {
            return response()->json(['orders' => $user->company->orders], 200);
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
    public function store(OrderRequest $request)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo('edit product')) {
            Order::create([
                'event_publish' => $request->event_publish,
                'event_landing' => $request->event_landing,
                'poll_publish' => $request->poll_publish,
                'mailings' => $request->mailings,
                'register_keys' => $request->register_keys,
                'register_days' => $request->register_days,
                'company_id' => $user->company->id,
            ]);

            return response()->json(['orders' => $user->company->orders], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {
        $user = Auth::user();
        if (Gate::allows('edit-product', $order)  && $order->status < 1) {
            $order->event_publish = $request->event_publish;
            $order->event_landing = $request->event_landing;
            $order->poll_publish = $request->poll_publish;
            $order->mailings = $request->mailings;
            $order->register_keys = $request->register_keys;
            $order->register_days = $request->register_days;
            $order->save();

            return response()->json(['orders' => $user->company->orders], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $user = Auth::user();
        if (Gate::allows('edit-product', $order) && $order->status < 1) {
            $order->delete();
            return response()->json(['orders' => $user->company->orders], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }

    public function pay(Order $order)
    {
        $user = Auth::user();
        if (Gate::allows('edit-product', $order) && $order->status < 1) {
            $order->status = 1;
            $order->save();

            return response()->json(['orders' => $user->company->orders], 200);
        } else {
            return response()->json(['error' => 'unauthorized'], 403);
        }
    }
}
