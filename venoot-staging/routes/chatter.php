<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Chatter Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'ChatterController@login');
    Route::group(['middleware' => 'auth:chatter'], function ($router) {
        Route::post('logout', 'ChatterController@logout');
        Route::get('refresh', 'ChatterController@refresh');
        Route::get('me', 'ChatterController@me');
    });
});

Route::group(['middleware' => 'auth:chatter'], function ($router) {
    Route::post('webinars/join', 'ChatterController@join');
    Route::post('webinars/response-join', 'ChatterController@responseJoin');
    //   Route::post('meetings/{meeting}', 'StandController@takeMeeting');

    //   Route::post('video', 'ChatterController@videoUrl');
    //   Route::post('participants', 'ChatterController@participants');
    //   Route::post('participations/{participation}/message', 'ChatterController@message');
    //   Route::post('participations/{sender}/private-message/{receiver}', 'ChatterController@privateMessage');

    //   Route::post('participations/{participation}/sendQuestion', 'ChatterController@sendQuestion');
    //   Route::post('participations/{participation}/voteQuestion', 'ChatterController@voteQuestion');
    //   Route::post('participations/{participation}/questions/{question}', 'ChatterController@answerQuestion');

    //   Route::post('keepAlive', 'ChatterController@keepAlive');

    //   Route::post('activities', 'ChatterController@showActivity');
});
