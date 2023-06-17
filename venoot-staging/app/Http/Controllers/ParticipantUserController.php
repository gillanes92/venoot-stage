<?php

namespace App\Http\Controllers;

use App\Participant;
use App\ParticipantUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ParticipantUserController extends Controller
{
    public function information()
    {

        $participants = Participant::whereJsonContains("data->email", $this->guard()->user()->email)->with('participations.event', 'participant_orders.participant_tickets.ticket.event')->get();
        // $tickets =

        return response()->json(['participants' => $participants], 200);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->guard()->user(),
            200
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:participant_users',
            'password' => 'required|string|min:8'
        ]);

        return response()->json(['participant' => ParticipantUser::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {

            Auth::guard('web')->attempt($credentials);

            return response()->json(['status' => 'success', 'user' => $this->guard()->user()], 200)->header('Authorization', $token);
        }

        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }

        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    public function participantUser()
    {
        return view('participant');
    }

    private function guard()
    {
        return Auth::guard('participants');
    }
}
