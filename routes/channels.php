<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('add-notification.{id}', function ($user, $id) {
	return (int) $id === (int) jwtUser()->id;
});

Broadcast::channel('add-comment', function () {
	return true;
});

Broadcast::channel('add-like', function () {
	return true;
});
