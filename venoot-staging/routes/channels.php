<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('event.{event_id}', function ($chatter, $event_id) {
    if ($chatter->event_id == $event_id) {
        return $chatter;
    }
}, ['guards' => ['chatter']]);

Broadcast::channel('host.event.{event_id}', function ($chatter, $event_id) {
    if ($chatter->event_id == $event_id && $chatter->type == 'host') {
        return $chatter;
    }
}, ['guards' => ['chatter']]);

Broadcast::channel('presenter.{presenter_uuid}.event.{event_id}', function ($chatter, $presenter_uuid, $event_id) {
    if ($chatter->event_id == $event_id && $chatter->type == 'presenter') {
        return $chatter;
    }
}, ['guards' => ['chatter']]);

// Broadcast::channel('event-{event_id}-activity-{activity_id}', function ($participation, $event_id) {
//     if ($participation->event_id == $event_id) {
//         return ['id' => $participation->id, 'name' => $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname'], 'hash' => md5(trim(strtolower($participation->participant->data['lastname'])))];
//     }
// });

// Broadcast::channel('participant-{participation_id}', function ($participation, $participation_id) {
//     if ($participation->id == $participation_id) {
//         // return ['id' => $participation->id, 'name' => $participation->participant->data['name'] . ' ' . $participation->participant->data['lastname']];
//         return ["status" => "success"];
//     }
// });
