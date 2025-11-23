<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\Http;

class CourseHelper
{
    public function getCourses($lastId = 0, $url, $siteKey = 'L1')
    {
        try {
            $data = [
                'table_name' => 'courses',
                'next_step' => $lastId,
            ];

            // $url = $url.'/api/data-integration/view';
            // logger()->info('Sending request to fetch courses:', [
            //     'url' => $url,
            //     'data' => $data,
            //     'timestamp' => now()->toDateTimeString(),
            // ]);
            // $response = Http::retry(3, 5000) // Retry 3 times with a 5s delay
            //     ->timeout(120) // Set timeout to 120 seconds
            //     ->withHeaders([
            //         'name' => config('app.CLIENT_NAME'),
            //         'secret' => config('app.CLIENT_SECRET'),
            //     ])->post($url, $data);
            $site = config("services.sites.$siteKey");
            $url = $site['url'] . '/api/data-integration/view';
            $secret = $site['shared_secret'];
            $timestamp = time();

            $signature = hash_hmac('sha256', $timestamp . json_encode($data), $secret);

            $response = Http::retry(3, 5000) // Retry 3 times with a 5s delay
                ->timeout(120) // Set timeout to 120 seconds
                ->withHeaders([
                    'X-Timestamp' => $timestamp,
                    'X-Signature' => $signature,
                ])->post($url, $data);

            if ($response->failed()) {
                logger()->error('Failed to fetch courses from remote API.', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'url' => $url,
                    'request_data' => $data,
                    'timestamp' => now()->toDateTimeString(),
                ]);
                throw new \Exception('Failed to fetch courses from remote API.');
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Failed to fetch courses: ' . $e->getMessage());
            return [];
        }
    }

    public function getCategories($lastId = 0, $url, $siteKey = 'L1')
    {
        try {
            $data = [
                'table_name' => 'categories',
                'next_step' => $lastId,

            ];


            // $url = $url.'/api/data-integration/view';
            // logger()->info('Sending request to fetch categories:', [
            //     'url' => $url,
            //     'data' => $data,
            //     'timestamp' => now()->toDateTimeString(),
            // ]);
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
                logger()->error('Failed to fetch categories from remote API.', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                throw new \Exception('Failed to fetch categories from remote API.');
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Failed to fetch categories: ' . $e->getMessage());
            return [];
        }
    }


}
