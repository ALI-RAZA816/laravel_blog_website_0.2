<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public function __construct(public Message $message)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin-messages')];
    }

    public function broadcastAs(): string
    {
        return 'NewMessageReceived';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id'      => $this->message->id,
                'name'    => $this->message->name,
                'email'   => $this->message->email,
                'subject' => $this->message->subject,
                'message' => $this->message->message,
            ],
        ];
    }
}
