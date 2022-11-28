<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules()
	{
		return [
			'image'          => 'image',
			'genres'         => 'required',
			'title_en'       => 'required',
			'title_ka'       => 'required',
			'director_en'    => 'required',
			'director_ka'    => 'required',
			'description_en' => 'required',
			'description_ka' => 'required',
		];
	}
}
