<?php

namespace App\Http\Controllers;

use App\Event;
use App\Stand;
use App\StandManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StandManagerController extends Controller
{
  private function access_token($email = null, $password = null)
  {
    $client = new \GuzzleHttp\Client();
    $url = "https://api.archiebot.com/api/oauth/access_token";

    $form_params['grant_type'] = 'password';
    $form_params['client_id'] = config('livewebinar.client_id');
    $form_params['client_secret'] = config('livewebinar.client_secret');
    $form_params['username'] = $email ?? config('livewebinar.login');
    $form_params['password'] = $password ?? config('livewebinar.password');

    try {
      $response = $client->post($url, ['form_params' => $form_params]);
      $json_response = json_decode($response->getBody()->getContents());
      return $json_response->accessToken->access_token;
    } catch (\Exception $e) {
      return null;
    }
  }

  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if ($token = $this->guard()->attempt($credentials)) {

      Auth::guard('stands')->attempt($credentials);

      return response()->json(['status' => 'success', 'user' => $this->guard()->user()], 200)->header('Authorization', $token);
    }

    return response()->json(['error' => 'login_error'], 401);
  }

  public function me()
  {
    return response()->json(
      $this->guard()->user(),
      200
    );
  }

  public function refresh()
  {
    if ($token = $this->guard()->refresh()) {
      return response()
        ->json(null, 204)
        ->header('Authorization', $token);
    }

    return response()->json(['error' => 'empty_request'], 200);
  }

  public function logout()
  {
    $this->guard()->logout();

    return response()->json(null, 204);
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Event $event)
  {
    return response()->json($event->managers, 200);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Stand $stand, Request $request)
  {
    $request->validate([
      'role' => 'sometimes|string',
      'name' => 'required|string',
      'last_name' => 'required|string',
      'email' => 'required|email|unique:stand_managers',
      'job' => 'sometimes|string',
      'company' => 'sometimes|string',
      'password' => 'required|string|min:8|confirmed',
    ]);

    $manager = $request->only(['role', 'name', 'last_name', 'email', 'job', 'company']);
    $manager['password'] = Hash::make($request->password);
    $manager['lw_password'] = Str::title(Str::random(16)) . rand(10, 99) . '@';

    $access_token = $this->access_token();

    $client = new \GuzzleHttp\Client();
    $url = "https://api.archiebot.com/api/users";

    if ($access_token) {
      $headers = [
        'Authorization' => 'Bearer ' . $access_token,
        'Accept' => 'application/vnd.archiebot.v1+json',
      ];

      $form_params = [
        'package_id' => 39,
        'email' => $manager['email'],
        'password' =>  $manager['lw_password'],
        'country_code_iso2' => 'CL',
        'confirmed' => 1,
        'profile' => [
          'first_name' => $manager['name'],
          'last_name' => $manager['last_name'],
          'company' => $manager['company'],
        ],
      ];

      try {
        $response = $client->post($url, ['headers' => $headers, 'form_params' => $form_params]);
        $json_response = json_decode($response->getBody()->getContents());
        $manager['lw_id'] = $json_response->data->id;

        $stand_manager = $stand->manager()->create($manager);
        $stand_manager->refresh();
      } catch (\Exception $e) {
        $response = null;
        return response()->json($e, 503);
      }
      return response()->json($stand_manager, 200);
    } else {
      return response()->json(null, 401);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\StandManager  $standManager
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, StandManager $standManager)
  {
    $request->validate([
      'role' => 'sometimes|string',
      'name' => 'sometimes|string',
      'last_name' => 'sometimes|string',
      'email' => 'sometimes|email|unique:stand_managers',
      'job' => 'sometimes|string',
      'company' => 'sometimes|string',
      'password' => 'sometimes|string|min:8|confirmed',
    ]);

    $manager = $request->only(['role', 'name', 'last_name', 'email', 'job', 'company']);
    if ($request->password) {
      $manager['password'] = Hash::make($request->password);
    }

    $standManager->update($manager);
    $standManager->refresh();

    return response()->json($standManager->makeVisible(['password']), 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\StandManager  $standManager
   * @return \Illuminate\Http\Response
   */
  public function show(StandManager $standManager)
  {
    return response()->json($standManager, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\StandManager  $standManager
   * @return \Illuminate\Http\Response
   */
  public function destroy(StandManager $standManager)
  {
    $standManager->delete();
    return response()->json(null, 204);
  }

  private function guard()
  {
    return Auth::guard('stands');
  }
}
