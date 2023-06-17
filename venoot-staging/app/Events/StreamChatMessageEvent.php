<?php

namespace App\Events;

use App\StreamChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StreamChatMessageEvent implements ShouldBroadcast
{
    // use Dispatchable, InteractsWithSockets, SerializesModels;
    use InteractsWithSockets;

    private $channel;
    private $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $channel, $message)
    {
        $this->channel = $channel;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel($this->channel);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}
