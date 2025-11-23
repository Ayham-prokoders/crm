<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Helper\ResponseHelper;

class BlogController extends Controller
{

    public function list_posts(Request $request,$lastId=0)
    {

        try {
            $data=[
                'table_name' => 'posts',
                'next_step' => $lastId,

            ];
            $url = env('API_URL').'/api/data-integration/view';
            $response = Http::withHeaders([
                    'name' => config('app.CLIENT_NAME'),
                    'secret' => config('app.CLIENT_SECRET'),
                ])->
            post($url , $data );
            // return($response);
            if ($response->failed()) {
                throw new \Exception('Failed to fetch posts from remote API.');
            }

             // Log the response for debugging
        logger()->info('Response from API: ' . $response->body());
                        $response->throw();
            $responses= $response->json();
            $posts=$responses['table_data'];
        } catch (\Exception $e) {
            logger()->error('Failed to fetch posts: ' . $e->getMessage());
            return [];
    };
       
        // return $courses;
        return ResponseHelper::success($posts);
        
    } 

    public function list_events(Request $request, $lastId=0)
    {

        try {
            $data=[
                'table_name' => 'events',
                'next_step' => $lastId,

            ];
            $url = env('API_URL').'/api/data-integration/view';

            $response = Http::withHeaders([
                    'name' => config('app.CLIENT_NAME'),
                    'secret' => config('app.CLIENT_SECRET'),
                ])->
            post($url, $data );
            if ($response->failed()) {
                throw new \Exception('Failed to fetch events from remote API.');
            }            $response->throw();
            $responses= $response->json();
            $events=$responses['table_data'];
        } catch (\Exception $e) {
            logger()->error('Failed to fetch events: ' . $e->getMessage());
            return [];
    };
       
            // return $courses;
        return ResponseHelper::success($events);
        
    } 
    
    public function list_news(Request $request, $lastId=0)
    {

        try {
            $data=[
                'table_name' => 'news',
                'next_step' => $lastId,

            ];
            $url = env('API_URL').'/api/data-integration/view';

            $response = Http::withHeaders([
                    'name' => config('app.CLIENT_NAME'),
                    'secret' => config('app.CLIENT_SECRET'),
                ])->
            post($url, $data );
            if ($response->failed()) {
                throw new \Exception('Failed to fetch news from remote API.');
            }            $response->throw();
            $responses= $response->json();
            $news=$responses['table_data'];
        } catch (\Exception $e) {
            logger()->error('Failed to fetch news: ' . $e->getMessage());
            return [];
    };
       
            // return $news;
        return ResponseHelper::success($news);
        
    } 
    

}
