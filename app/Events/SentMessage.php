<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SentMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender_id;
    public $receiver_id;
    public string $message;

    /**
     * Create a new event instance.
     */
    public function __construct($data)
    {
        $this->message = $data['message'];
        $this->sender_id = $data['sender_id'];
        $this->receiver_id = $data['receiver_id'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel("chat.{$this->sender_id}.{$this->receiver_id}");
    }

    public function broadcastAs(): string
    {
        return 'message-sent';
    }
}
