<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddNotificationEvent implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public $notification;

	public $receiver;

	public function __construct($notification)
	{
		$this->notification = $notification;
		$this->receiver = $notification['to_id'];
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new PrivateChannel('add-notification.' . $this->receiver);
	}

	public function broadcastAs()
	{
		return 'new-notification';
	}
}
