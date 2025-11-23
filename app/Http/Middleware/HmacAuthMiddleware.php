<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HmacAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientHmac = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');

        if (!$clientHmac || !$timestamp) {
            return ResponseHelper::invalidData('Missing HMAC signature or timestamp');
        }
        //5 minutes
        if (abs(time() - (int) $timestamp) > 300) {
            return ResponseHelper::invalidData('Request timestamp expired');
        }
        $key= $request->project_source;
        $site = config("services.sites.$key");
        $secretKey =  $site['shared_secret'];
        // $method = strtoupper($request->getMethod());
        // $url = $request->fullUrl();

        // $bodyArray = $request->only(['target_course_id', 'new_code']);
        // $reconstructedJson = json_encode($bodyArray);
        // $dataToSign = $timestamp . $reconstructedJson;
        // Construct message to sign
        $body = $request->getContent();
        $dataToSign = $timestamp . $body;
        // Generate HMAC signature
        $calculatedHmac = hash_hmac('sha256', $dataToSign, $secretKey);

        \Log::info('HMAC verification data', [
            'timestamp' => $timestamp,
            'request_content' => $request->getContent(),
            'data_to_sign' => $dataToSign,
            'calculated_hmac' => $calculatedHmac,
            'client_hmac' => $clientHmac,
        ]);


        if (!hash_equals($calculatedHmac, $clientHmac)) {
            return response()->json(['error' => 'Invalid HMAC signature'], 403);
        }

        return $next($request);
    }
}
