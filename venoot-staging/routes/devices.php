<?php
/*
|--------------------------------------------------------------------------
| Ticket App Device Routes
|--------------------------------------------------------------------------
|
*/

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'DeviceController@login');
});

Route::group(['middleware' => 'auth:devices'], function ($router) {
    Route::post('logout', 'DeviceController@logout');
    Route::get('user', 'DeviceController@user');
    Route::post('refresh', 'DeviceController@refresh');

    Route::get('events', 'DeviceController@events');
    Route::post('activity', 'DeviceController@activity');
    Route::get('participants', 'DeviceController@participants');
    Route::post('confirm', 'DeviceController@confirm');
    Route::post('register', 'DeviceController@register');
    Route::post('search', 'DeviceController@search');
    Route::post('print', 'DeviceController@print');
});
