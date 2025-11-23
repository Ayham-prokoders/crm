<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Http;

class CityHelper
{
    public function getCities($lastId = 0,$url,$siteKey='L1')
    {
        try {
            $data = [
                'table_name' => 'cities',
                'next_step' => $lastId,

            ];
            // $url = $url.'/api/data-integration/view';

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

            if ($response->failed()) {
                throw new \Exception('Failed to fetch Cities from remote API.');
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Failed to fetch Cities: ' . $e->getMessage());
            return [];
        }
    }
    public function getLocations($lastId = 0 ,$url,$siteKey='L1')
    {
        try {
            $data = [
                'table_name' => 'locations',
                'next_step' => $lastId,

            ];
            // $url = $url.'/api/data-integration/view';

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


            if ($response->failed()) {
                throw new \Exception('Failed to fetch locations from remote API.');
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Failed to fetch locations: ' . $e->getMessage());
            return [];
        }
    }
}
