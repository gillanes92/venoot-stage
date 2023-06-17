<?php

namespace App\Events;

use App\Chatter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WebinarJoinRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatter;
    public $name;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chatter $chatter, $name, $message)
    {
        $this->chatter = $chatter;
        $this->name = $name;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $event_id = $this->chatter->event_id;
        return new PrivateChannel("host.event.$event_id");
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['presenter_uuid' => $this->chatter->presenter_uuid, 'name' => $this->name, 'message' => $this->message];
    }
}
