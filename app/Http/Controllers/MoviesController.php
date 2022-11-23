<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Models\Movie;

class MoviesController extends Controller
{
	public function store(StoreMovieRequest $request)
	{
		$file_path = '';
		if ($request->file('image'))
		{
			$file_name = time() . '_' . request()->file('image')->getClientOriginalName();
			$file_path = request()->file('image')->storeAs('images', str_replace(' ', '_', $file_name), 'public');
		}

		$movie = Movie::create([
			'image'  => '/storage/' . $file_path,
			'genres' => $request->genres,
			'title'  => [
				'en', $request->title_en,
				'ka', $request->title_ka,
			],
			'director' => [
				'en', $request->director_en,
				'ka', $request->director_ka,
			],
			'description' => [
				'en', $request->description_en,
				'ka', $request->description_ka,
			],
		]);
		return 'Done: ' . $file_name . '. ' . $file_path;
	}
}
