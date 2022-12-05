<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Movie;
use App\Models\Quote;

class QuotesController extends Controller
{
	public function create(StoreQuoteRequest $request)
	{
		$file_path = '';
		if ($request->file('thumbnail'))
		{
			$file_name = time() . '_' . request()->file('thumbnail')->getClientOriginalName();
			$file_path = request()->file('thumbnail')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}
		Quote::create([
			'movie_id' => $request->movie_id,
			'user_id'  => jwtUser()->id,
			'quote'    => [
				'en' => $request->quote_en,
				'ka' => $request->quote_ka,
			],
			'thumbnail' => '/storage/' . $file_path,
		]);
		return response('nice');
	}

	public function update(UpdateQuoteRequest $request, Quote $quote)
	{
		if ($request->file('thumbnail'))
		{
			$file_name = time() . '_' . request()->file('thumbnail')->getClientOriginalName();
			$file_path = request()->file('thumbnail')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
			$quote->image = '/storage/' . $file_path;
		}
		$quote->quote = [
			'en' => $request->quote_en,
			'ka' => $request->quote_ka,
		];
		$quote->movie_id = $request->movie_id;
		$quote->update();
	}

	public function read()
	{
		$quotes = Quote::with('comments')->with('likes')->orderBy('created_at', 'desc')->get();
		return response()->json($quotes);
	}

	public function show(Quote $quote)
	{
		return response()->json($quote);
	}

	public function readMovieQuotes(Movie $movie)
	{
		$quotes = $movie->quotes;
		return response()->json(Quote::where('movie_id', '=', $movie->id)->orderBy('created_at', 'desc')->with('likes', 'comments')->get());
	}

	public function destroy(Quote $quote)
	{
		$quote->delete();
	}
}
