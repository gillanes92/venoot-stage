<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Streaming Routes
|--------------------------------------------------------------------------
|
*/

Route::post("store", "ParticipantUserController@store");

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'ParticipantUserController@login');
    Route::group(['middleware' => 'auth:participants'], function ($router) {
        Route::post('logout', 'ParticipantUserController@logout');
        Route::get('refresh', 'ParticipantUserController@refresh');
        Route::get('me', 'ParticipantUserController@me');
    });
});

Route::group(['middleware' => 'auth:participants'], function ($router) {

    Route::get('information', 'ParticipantUserController@information');
});
