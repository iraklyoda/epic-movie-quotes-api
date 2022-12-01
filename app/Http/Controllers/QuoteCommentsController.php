<?php

namespace App\Http\Controllers;

use App\Events\AddCommentEvent;
use App\Http\Requests\StoreQuoteCommentRequest;
use App\Models\Comment;
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
		event(new AddCommentEvent($comment));
		Comment::create($comment);
	}
}
