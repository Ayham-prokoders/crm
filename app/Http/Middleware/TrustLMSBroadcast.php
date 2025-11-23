<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrustLMSBroadcast
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('Incoming LMS Broadcast Auth Request', [
            'Authorization' => $request->header('Authorization'),
            'channel' => $request->input('channel_name'),
        ]);
        \Log::info('Expected LMS Token: ' . config('services.lms.broadcast_token'));

        // if (Auth::check()) {
        //     return $next($request);
        // }

         if ($request->is('api/webhooks/*')  ||
             $request->is('api/sync/email-builders')) {
            return $next($request); // skip auth
        }

        $token = $request->bearerToken();
        \Log::info('Real LMS Token: ' . $token );

        if ($token === config('services.lms.broadcast_token')) {
            $channel = $request->input('channel_name');

            if (preg_match('/notifications\.([a-zA-Z0-9\-]+)/', $channel, $matches)) {
                $userId = $matches[1];
                $user = User::find($userId);

                if ($user) {
                    \Log::info("LMS Authenticated user {$user->id} for channel $channel");
                    Auth::setUser($user);
                } else {
                    \Log::warning("LMS User ID $userId not found.");
                }
            } else {
                \Log::warning("LMS Channel name format not matched: " . $channel);
            }
        } else {
            \Log::warning("LMS Bearer token does not match.");
        }

        return $next($request);
    }
}
