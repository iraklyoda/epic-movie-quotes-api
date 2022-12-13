<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Events\AddNotificationEvent;
use App\Http\Requests\StoreQuoteCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;

class QuoteCommentsController extends Controller
{
	public function create(storeQuoteCommentRequest $request, Quote $quote)
	{
		if (!jwtUser())
		{
			return response()->json(['message' => 'token not present'], 401);
		}
		$comment = [
			'user_id'  => jwtUser()->id,
			'body'     => $request->body,
			'quote_id' => $quote->id,
		];
		if (jwtUser()->id !== $quote->user_id)
		{
			$notification = [
				'from_id' => jwtUser()->id,
				'to_id'   => $quote->user_id,
				'type'    => 'comment',
			];
			event(new AddNotificationEvent($notification));
			Notification::create($notification);
		}
		event(new AddCommentEvent($comment));
		if (Comment::create($comment))
		{
			return response()->json('Comment created successfully', 201);
		}
	}
}
