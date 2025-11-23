<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    Log::info('auth user id is: ' . $user->id . ' and user id from channel is: ' . $userId);
    return (string) $user->id === (string) $userId;

});




// Route::post('/broadcasting/auth', function (Request $request) {
//     return Broadcast::auth($request);
// })->middleware(['auth:sanctum']);

// Route::post('/broadcasting/auth-from-lms', function (Request $request) {
//     return Broadcast::auth($request);
// })->middleware(['trust.lms.broadcast']);