<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Livewebinar Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@loginByLink');
});

Route::group(['middleware' => 'auth:livewebinar'], function ($router) {
    Route::get('events/{event}/streamChatMessages', 'EventController@streamChatMessages');
    Route::post('events/{event}/streamChatMessages', 'EventController@sendStreamChatMessages');
    Route::get('events/{event}/activeInChat', 'StreamingController@activeParticipants');

    Route::get('events/{event}/polls', 'PollController@eventIndex');
    Route::post('events/{event}/questions/{question}', 'StreamingController@sendPollQuestion');
    Route::post('events/{event}/questionRequest', 'EventController@questionRequest');
    Route::get('events/{event}/answers', 'StreamingController@getAnswers');

    Route::get('events/{event}/submittedQuestions', 'EventController@submittedQuestions');
});
