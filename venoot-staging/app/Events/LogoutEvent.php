<?php

namespace App\Events;

use App\ChatParticipant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LogoutEvent implements ShouldBroadcast
{
    private $channel;
    private $chat_participant;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $channel, ChatParticipant $chat_participant)
    {
        $this->channel = $channel;
        $this->chat_participant = $chat_participant;
    }

    public function broadcastOn()
    {
        return new Channel($this->channel);
    }

    public function broadcastWith()
    {
        return [
            'chat_participant' => $this->chat_participant,
        ];
    }
}
