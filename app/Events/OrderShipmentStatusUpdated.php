<?php

namespace App\Events;

use App\Models\User\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderShipmentStatusUpdated implements ShouldBroadcast, ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	public $user;
	public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, $message)
    {
		$this->user = $user;
		$this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
		Log::error("频道：{$this->user->id}");
		return [
            new PrivateChannel("App.Models.User.{$this->user->id}"),
        ];
    }

	public function broadcastAs(): string
	{
		Log::error('事件的广播名字');
		return 'order.shipment.status.updated';
	}

	public function broadcastWith()
	{
		Log::error("返回数据的格式");
		return [
			'message' => $this->message
		];
	}
}
