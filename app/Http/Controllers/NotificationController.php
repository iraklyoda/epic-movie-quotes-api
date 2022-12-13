<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
	public function read()
	{
		$notifications = Notification::where('to_id', JwtUser()->id)->orderBy('id', 'desc')->get();
		return response()->json($notifications);
	}

	public function markRead()
	{
		$notifications = Notification::where('read', '=', 0)->where('to_id', JwtUser()->id);
		$notifications->update(['read' => 1]);
		return response()->json('Notifications marked as read successfully!', 200);
	}
}
