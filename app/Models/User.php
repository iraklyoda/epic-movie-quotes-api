<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	protected $fillable = [
		'username',
		'email',
		'password',
		'google_id',
	];

	protected $hidden = [
		'password',
	];

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function movies()
	{
		return $this->hasMany(Movie::class);
	}

	public function sendEmailVerificationNotification()
	{
		$this->notify(new VerifyEmail());
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPassword($token));
	}

	/*
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
}
