<?php

namespace App\Http\Controllers;

use App\Events\AddLikeEvent;
use App\Events\AddNotificationEvent;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\Request;

class LikesController extends Controller
{
	public function create(Request $request, Quote $quote)
	{
		if (!jwtUser())
		{
			return response()->json(['message' => 'token not present'], 401);
		}
		$like = Like::where('user_id', JwtUser()->id)->where('quote_id', $quote->id)->first();
		if (!$like)
		{
			$like = [
				'user_id'  => jwtUser()->id,
				'quote_id' => $quote->id,
			];
			if (jwtUser()->id !== $quote->user_id)
			{
				$notification = [
					'from_id' => jwtUser()->id,
					'to_id'   => $quote->user_id,
					'type'    => 'like',
				];
				event(new AddNotificationEvent($notification));
				Notification::create($notification);
			}
			event(new AddLikeEvent($like));
			Like::create($like);
			return response()->json('Like created successfully', 201);
		}
		else
		{
			event(new AddLikeEvent($like));
			$like->delete();
			return response()->json('Like deleted successfully', 202);
		}
	}
}
