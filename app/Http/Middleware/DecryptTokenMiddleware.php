<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Laravel\Sanctum\PersonalAccessToken;

class DecryptTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token') && $request->has('iv')) {
            $encryptedToken = $request->input('token');
            \Log::info('token=' . $encryptedToken);
            $iv = $request->input('iv');
            \Log::info('iv=' . $iv);
            $key = env('AES_KEY');
            \Log::info('AES_KEY=' . $key);
            // Decrypt the token
            $decryptedToken = $this->decryptAES($encryptedToken, $iv, $key);

            if ($decryptedToken) {
                \Log::info('decryptedToken=' . $decryptedToken);

                // Try to find a matching personal access token
                $accessToken = PersonalAccessToken::findToken($decryptedToken);

                if ($accessToken && $accessToken->tokenable) {
                    $user = $accessToken->tokenable; // Retrieve the user associated with the token
                    Auth::setUser($user); // Authenticate the user for the request
                    \Log::info('Authenticated User ID: ' . $user->id);
                } else {
                    \Log::info('No valid access token found for decrypted token.');
                }
            } else {
                \Log::error('Token decryption failed.');
            }
        }

        if (Auth::check()) {
            \Log::info('Authenticated User:', ['user_id' => Auth::id()]);
        } else {
            \Log::info('No User Authenticated');
        }

        return $next($request);
    }

    private function decryptAES($encryptedData, $iv, $key)
    {
        // Decode the Base64 encoded encrypted data
        $encryptedData = base64_decode($encryptedData);
        \Log::info('decryptAES/encryptedData=' . base64_encode($encryptedData)); // Log Base64 for debugging

        // Convert IV from hex to binary
        $iv = hex2bin($iv);
        if ($iv === false) {
            \Log::error('IV conversion failed.');
            return null;
        }
        \Log::info('decryptAES/iv=' . $iv);

        // Decrypt the data
        $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            \Log::error('Decryption failed: ' . openssl_error_string());
            return null;
        }

        return $decrypted;
    }
}
