<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebinarJoinResponseEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $presenter_uuid;
    public $event_id;
    public $token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($presenter_uuid, $event_id, $token)
    {
        $this->presenter_uuid = $presenter_uuid;
        $this->event_id = $event_id;
        $this->token = $token;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $presenter_uuid = $this->presenter_uuid;
        $event_id = $this->event_id;
        return new PrivateChannel("presenter.$presenter_uuid.event.$event_id");
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['token' => $this->token];
    }
}
