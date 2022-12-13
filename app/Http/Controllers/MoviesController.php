<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
	public function create(StoreMovieRequest $request)
	{
		if (!jwtUser())
		{
			return response()->json('not authorized', 401);
		}
		$file_path = '';
		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}

		$movie = Movie::create([
			'image'   => '/storage/' . $file_path,
			'user_id' => jwtUser()->id,
			'genres'  => json_encode($request->genres),
			'title'   => [
				'en' => $request->title_en,
				'ka' => $request->title_ka,
			],
			'director' => [
				'en' => $request->director_en,
				'ka' => $request->director_ka,
			],
			'description' => [
				'en' => $request->description_en,
				'ka' => $request->description_ka,
			],
		]);
		return response()->json('Movie created successfully', 200);
	}

	public function update(UpdateMovieRequest $request, Movie $movie)
	{
		$file_path = '';
		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
			$movie->image = '/storage/' . $file_path;
		}
		$movie->genres = json_encode($request->genres);
		$movie->title = [
			'en' => $request->title_en,
			'ka' => $request->title_ka,
		];
		$movie->director = [
			'en' => $request->director_en,
			'ka' => $request->director_ka,
		];
		$movie->description = [
			'en' => $request->description_en,
			'ka' => $request->description_ka,
		];
		if ($movie->save())
		{
			return response()->json('Movie updated successfully', 201);
		}
	}

	public function read()
	{
		$movies = Movie::where('user_id', JwtUser()->id)->orderBy('id', 'desc')->with('quotes')->get();
		return response()->json($movies);
	}

	public function search(Request $request)
	{
		$search = $request->search;
		$movies = Movie::where('user_id', JwtUser()->id)->where('title', 'like', '%' . $search . '%')->orWhere('user_id', jwtUser()->id)->where('title', 'like', '%' . ucwords($search) . '%')
			->orderBy('id', 'desc')->with('quotes')->get();
		return response()->json($movies);
	}

	public function show(Movie $movie)
	{
		if ($movie->user_id !== jwtUser()->id)
		{
			return response()->json("User don't have access to this movie", 405);
		}
		$movie->genres = json_decode($movie->genres, true);
		return response()->json($movie->load([
			'quotes' => function ($query) {
				$query->orderBy('id', 'desc');
			},
		]));
	}

	public function destroy(Movie $movie)
	{
		if ($movie->delete())
		{
			return response()->json('Movie deleted successfully', 202);
		}
		else
		{
			return response()->json(['Problem deleting film'], 404);
		}
	}
}
