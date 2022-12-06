<?php

namespace App\Models;

use App\Notifications\VerifyCreatedEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class email extends Model
{
	use HasFactory;

	use notifiable;

	protected $guarded = ['id'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function sendEmailVerification($id)
	{
		$this->notify(new VerifyCreatedEmail($id));
	}
}
