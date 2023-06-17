<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\QuestionRequest;

class QuestionRequestEvent implements ShouldBroadcast
{
    use InteractsWithSockets;

    private $channel;
    private $question_request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $channel,  QuestionRequest $question_request)
    {
        $this->channel = $channel;
        $this->question_request = $question_request;
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
            'question_request' => $this->question_request,
        ];
    }
}
