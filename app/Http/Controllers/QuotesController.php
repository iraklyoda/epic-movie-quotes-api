<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\StoreSearchRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotesController extends Controller
{
	public function create(StoreQuoteRequest $request)
	{
		if (!jwtUser())
		{
			return response()->json(['message' => 'token not present'], 401);
		}
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
		return response()->json('Quote created successfully', 201);
	}

	public function update(UpdateQuoteRequest $request, Quote $quote)
	{
		if (!jwtUser())
		{
			return response()->json(['message' => 'token not present'], 401);
		}
		if ($request->file('thumbnail'))
		{
			$file_name = time() . '_' . request()->file('thumbnail')->getClientOriginalName();
			$file_path = request()->file('thumbnail')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
			$quote->thumbnail = '/storage/' . $file_path;
		}
		$quote->quote = [
			'en' => $request->quote_en,
			'ka' => $request->quote_ka,
		];
		if ($quote->update())
		{
			return response()->json('Quote updated successfully', 200);
		}
	}

	public function read()
	{
		$quotes = Quote::with('comments')->with('likes')->orderBy('created_at', 'desc')->paginate(2);
		return response()->json($quotes);
	}

	public function readNumber(Request $request)
	{
		if ($request->search)
		{
			$search = $request->search;
			$quotes = null;
			if ($search[0] === '#')
			{
				$search = substr($search, 1);
				$quotes = Quote::take($request->number)->where(DB::raw('lower(quote)'), 'like', '%' . strtolower($search) . '%')->with('comments')->with('likes')->orderBy('created_at', 'desc')->get();
			}
			elseif ($search[0] === '@')
			{
				$search = substr($search, 1);
				$quotes = Quote::take($request->number)->whereHas('movie', function ($query) use ($search) {
					$query->where(DB::raw('lower(title)'), 'like', '%' . strtolower($search) . '%');
				})->with('comments')->with('likes')->orderBy('created_at', 'desc')->get();
			}
			else
			{
				$quotes = Quote::take($request->number)->whereHas('movie', function ($query) use ($search) {
					$query->where(DB::raw('lower(title)'), 'like', '%' . strtolower($search) . '%');
				})->orWhere(DB::raw('lower(quote)'), 'like', '%' . strtolower($search) . '%')->with('comments')->with('likes')->orderBy('created_at', 'desc')->get();
			}
			if ($quotes)
			{
				return $quotes;
			}
		}
		return Quote::take($request->number)->orderBy('created_at', 'desc')->get();
	}

	public function readPaginate()
	{
		$quotes = Quote::with('comments')->with('likes')->orderBy('created_at', 'desc')->paginate(2);
		return response()->json($quotes);
	}

	public function search(StoreSearchRequest $request)
	{
		$search = $request->search;
		$quotes = null;
		if ($search[0] === '#')
		{
			$search = substr($search, 1);
			$quotes = Quote::where(DB::raw('lower(quote)'), 'like', '%' . strtolower($search) . '%')->with('comments')->with('likes')->orderBy('created_at', 'desc')->paginate(2);
		}
		elseif ($search[0] === '@')
		{
			$search = substr($search, 1);
			$quotes = Quote::whereHas('movie', function ($query) use ($search) {
				$query->where(DB::raw('lower(title)'), 'like', '%' . strtolower($search) . '%');
			})->with('comments')->with('likes')->orderBy('created_at', 'desc')->paginate(2);
		}
		else
		{
			$quotes = Quote::whereHas('movie', function ($query) use ($search) {
				$query->where(DB::raw('lower(title)'), 'like', '%' . strtolower($search) . '%');
			})->orWhere(DB::raw('lower(quote)'), 'like', '%' . strtolower($search) . '%')->with('comments')->with('likes')->orderBy('created_at', 'desc')->paginate(2);
		}

		if (!$quotes->first())
		{
			return response()->json(['message' => 'not found'], 404);
		}
		else
		{
			return response()->json($quotes);
		}
	}

	public function show(Quote $quote)
	{
		return response()->json($quote);
	}

	public function destroy(Quote $quote)
	{
		if ($quote->delete())
		{
			return response()->json('Quote deleted successfully', 202);
		}
		else
		{
			return response()->json('Problem deleting film', 404);
		}
	}
}
