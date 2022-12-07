<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quote extends Model
{
	use HasFactory, HasTranslations;

	protected $guarded = ['id'];

	protected $with = ['movie', 'comments', 'likes'];

	public $translatable = ['quote'];

	public function author()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function movie()
	{
		return $this->belongsTo(Movie::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function likes()
	{
		return $this->hasMany(Like::class);
	}
}
