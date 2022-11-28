<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
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
			'quote'    => [
				'en' => $request->quote_en,
				'ka' => $request->quote_ka,
			],
			'thumbnail' => '/storage/' . $file_path,
		]);
		return response('nice');
	}

	public function read()
	{
		$quotes = Quote::all();
		return response()->json($quotes);
	}

	public function readMovieQuotes(Movie $movie)
	{
		$quotes = $movie->quotes();
		return response()->json($movie->quotes);
	}
}
