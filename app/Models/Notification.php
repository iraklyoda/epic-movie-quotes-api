<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $with = ['sender'];

	public function sender()
	{
		return $this->belongsTo(User::class, 'from_id');
	}

	public function receiver()
	{
		return $this->belongsTo(User::class, 'to_id');
	}
}
