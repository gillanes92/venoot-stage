<?php

namespace App\Events;

use App\ParticipantQuestion;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ParticipantQuestionEvent implements ShouldBroadcast
{
    use InteractsWithSockets;

    private $channel;
    private $question;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $channel, ParticipantQuestion $question)
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
