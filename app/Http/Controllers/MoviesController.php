<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;

class MoviesController extends Controller
{
	public function create(StoreMovieRequest $request)
	{
		$file_path = '';
		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}

		$movie = Movie::create([
			'image'   => '/storage/' . $file_path,
			'user_id' => jwtUser()->id,
			'genres'  => $request->genres,
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
		return 'Done: ' . $file_name . '. ' . $file_path;
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
		$movie->genres = $request->genres;
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
		$movie->save();
	}

	public function read()
	{
		$movies = Movie::where('user_id', JwtUser()->id)->orderBy('id', 'desc')->with('quotes')->get();
		return response()->json($movies);
	}

	public function show(Movie $movie)
	{
		$movie->genres = json_decode($movie->genres, true);
		return response()->json($movie);
	}

	public function destroy(Movie $movie)
	{
		$movie->delete();
		return response();
	}
}
