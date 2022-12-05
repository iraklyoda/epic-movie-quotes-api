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
		$like = Like::where('user_id', JwtUser()->id)->where('quote_id', $quote->id)->first();
		if (!$like)
		{
			$like = [
				'user_id'  => jwtUser()->id,
				'quote_id' => $quote->id,
			];
			if (jwtUser()->id !== $request->quote_author)
			{
				$notification = [
					'from_id' => $request->user_id,
					'to_id'   => $request->quote_author,
					'type'    => $request->type,
				];
				event(new AddNotificationEvent($notification));
				Notification::create($notification);
			}
			event(new AddLikeEvent($like));
			Like::create($like);
			return response()->json('liked');
		}
		else
		{
			event(new AddLikeEvent($like));
			$like->delete();
			return response()->json('deleted');
		}
	}
}
