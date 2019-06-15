<?php

use App\Conversation;

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

Broadcast::channel('messages.{id}', function ($user, $id) {
    $con = Conversation::findOrFail($id);
    return $user->id == $con->user_id || $user->id == $con->shop_owner_id;
    // return true;
});
