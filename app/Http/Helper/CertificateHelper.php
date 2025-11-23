<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Http;

class CertificateHelper
{
    public function getCertificates($lastId = 0, $url, $siteKey = 'L1')
    {
        try {
            $data = [
                'table_name' => 'certificates',
                'next_step' => $lastId,
            ];

            // $response = Http::withHeaders([
            //     'name' => config('app.CLIENT_NAME'),
            //     'secret' => config('app.CLIENT_SECRET'),
            // ])->post($url, $data);
            $site = config("services.sites.$siteKey");
            $url = $site['url'] . '/api/data-integration/view';
            $secret = $site['shared_secret'];
            $timestamp = time();

            $signature = hash_hmac('sha256', $timestamp . json_encode($data), $secret);

            $response = Http::withHeaders([
                'X-Timestamp' => $timestamp,
                'X-Signature' => $signature,
            ])->post($url, $data);

            //
            if ($response->failed()) {
                throw new \Exception('Failed to fetch certificates from remote API.');
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Failed to fetch certificates: ' . $e->getMessage());
            return [];
        }
    }

}
