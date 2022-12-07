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
		$comment = [
			'user_id'  => jwtUser()->id,
			'body'     => $request->body,
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
		event(new AddCommentEvent($comment));
		Comment::create($comment);
	}
}
