<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Email extends Model
{
	use HasFactory;

	use notifiable;

	protected $guarded = ['id'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
