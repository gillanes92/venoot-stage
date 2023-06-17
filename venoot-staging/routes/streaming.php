<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Streaming Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
  'prefix' => 'auth'
], function ($router) {

  Route::post('login', 'StreamingController@login');
  Route::post('login/events/{event}/email', 'StreamingController@loginByEmail');
  Route::group(['middleware' => 'auth:streaming'], function ($router) {
    Route::post('logout', 'StreamingController@logout');
    Route::get('refresh', 'StreamingController@refresh');
    Route::get('me', 'StreamingController@me');
  });
});

Route::group(['middleware' => 'auth:streaming'], function ($router) {
  Route::get('event', 'StreamingController@eventData');
  Route::post('meetings/{meeting}', 'StandController@takeMeeting');

  Route::post('video', 'StreamingController@videoUrl');
  Route::post('participants', 'StreamingController@participants');
  Route::post('participations/{participation}/message', 'StreamingController@message');
  Route::post('participations/{sender}/private-message/{receiver}', 'StreamingController@privateMessage');

  Route::post('participations/{participation}/sendQuestion', 'StreamingController@sendQuestion');
  Route::post('participations/{participation}/voteQuestion', 'StreamingController@voteQuestion');
  Route::post('participations/{participation}/questions/{question}', 'StreamingController@answerQuestion');

  Route::post('keepAlive', 'StreamingController@keepAlive');

  Route::post('activities', 'StreamingController@showActivity');
});
