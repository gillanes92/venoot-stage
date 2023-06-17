<?php

namespace App\Http\Controllers;

use App\SsoToken;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {

            Auth::guard('web')->attempt($credentials);

            // IS USER EMAIL VERIFIED?
            if (!$this->guard()->user()->email_verified_at) {
                return response()->json(['error' => 'email_not_verified'], 401);
            }

            return response()->json(['status' => 'success', 'user' => $this->guard()->user()], 200)->header('Authorization', $token);
        }

        return response()->json(['error' => 'login_error'], 401);
    }

    public function loginByLink(Request $request)
    {
        try {
            $event = Event::where('ams_streamId', $request->ams_streamId)->first();
            if ($event) {
                $token = $this->guard()->tokenById($event->company->users()->first()->id);
                return response()->json(['event_id' => $event->id], 200)->header('Authorization', $token);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return response()->json(['error' => 'not_logged'], 401);
    }

    public function ssoTokenRequest()
    {
        try {
            $sso_token = $this->guard()->user()->sso_tokens()->create(['sso_token' => (string) Str::uuid()]);
            return response()->json(['sso_token' => $sso_token->sso_token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'not_logged']);
        }
    }

    public function ssoTokenLogin(Request $request)
    {
        $request->validate([
            'sso_token' => 'required|uuid|exists:sso_tokens,sso_token'
        ]);

        $sso_token = SsoToken::where('sso_token', $request->sso_token)->first();

        if ($sso_token) {
            if (Carbon::parse($sso_token->created_at)->diffInSeconds(Carbon::now()) > 60) {
                $sso_token->delete();

                return response()->json(['error' => 'not_logged']);
            } else {
                $token = $this->guard()->tokenById($sso_token->user->id);
                return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
            }
        }

        return response()->json(['error' => 'not_logged']);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    public function user()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->guard()->user()
        ]);
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

    private function guard()
    {
        return Auth::guard();
    }
}
