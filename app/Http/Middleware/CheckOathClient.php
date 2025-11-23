<?php

namespace App\Http\Middleware;

use App\Http\Helper\OathClientHelper;
use App\Http\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOathClient
{
    private $client;
    function __construct(OathClientHelper $client)
    {
        $this->client = $client;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $headers =$request->header();
        $clientSecret  =$request->header('secret');
        $clientName  =$request->header('name');
        $clientIp =$request->ip();

        if(!$clientSecret || !$clientName) return ResponseHelper::authorizationFail();
        $client =$this->client->checkClient($clientName ,$clientSecret ,$clientIp);
        if(!$client) return ResponseHelper::authorizationFail();
        return $next($request);
    }
}
