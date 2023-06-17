<?php

namespace App\Events;

use App\Question;
use App\StreamChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PollQuestionEvent implements ShouldBroadcast
{
    use InteractsWithSockets;

    private $channel;
    private $question;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $channel, Question $question)
    {
        $this->channel = $channel;
        $this->question = $question;
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
            'question' => $this->question,
        ];
    }
}
