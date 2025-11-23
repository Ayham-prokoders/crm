<?php

namespace App\Http\Controllers\Api;
use App\Http\Helper\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function getToken(Request $request)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return ResponseHelper::DataNotFound('Token not found in header');
        }
        $token = str_replace('Bearer ', '', $token);

        $key = env('AES_KEY');
        if (!$key) {
            return ResponseHelper::operationFail('Encryption key not found');
        }

        $encrypted = $this->encryptAES($token, $key);

        // Apply urlencode to safely encode the encrypted token
        return ResponseHelper::success([
            'iv' => $encrypted['iv'],
            'encrypted_token' => urlencode($encrypted['encryptedData']) // This ensures URL safety
        ]);
    }

    private function encryptAES($plaintext, $key)
    {
        $iv = openssl_random_pseudo_bytes(16);
        $encryptedData = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return [
            'iv' => bin2hex($iv),
            'encryptedData' => base64_encode($encryptedData),
        ];
    }
}
