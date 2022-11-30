<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteCommentRequest;
use App\Models\Comment;
use App\Models\Quote;

class QuoteCommentsController extends Controller
{
	public function create(storeQuoteCommentRequest $request, Quote $quote)
	{
		Comment::create([
			'user_id'  => jwtUser()->id,
			'body'     => $request->body,
			'quote_id' => $quote->id,
		]);
	}
}
