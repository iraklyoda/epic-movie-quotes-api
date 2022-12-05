<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddLikeEvent implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $like;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($like)
	{
		$this->like = $like;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new Channel('add-like');
	}

	public function broadcastAs()
	{
		return 'new-like';
	}
}
