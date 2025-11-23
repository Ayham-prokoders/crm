<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyLms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken(); 

        if (!$token || $token !== config('services.lms.broadcast_token')) {
            return response()->json([
                'message' => 'Unauthorized: Invalid LMS broadcast token',
            ], 401);
        }

        return $next($request);
    }
}
