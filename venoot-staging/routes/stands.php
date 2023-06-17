<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Stand Manager Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
  'prefix' => 'auth'
], function ($router) {

  Route::post('login', 'StandManagerController@login');
  Route::group(['middleware' => 'auth:stands'], function ($router) {
    Route::post('logout', 'StandManagerController@logout');
    Route::get('refresh', 'StandManagerController@refresh');
    Route::get('me', 'StandManagerController@me');
  });
});

Route::group(['middleware' => 'auth:stands'], function ($router) {
  Route::match(['put', 'patch'], 'stands/meetings/{meeting}', 'StandController@updateMeeting');
  Route::match(['put', 'patch'], 'stands/meetings', 'StandController@updateMeetings');
});
