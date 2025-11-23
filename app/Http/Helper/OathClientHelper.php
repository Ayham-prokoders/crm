<?php

namespace App\Http\Helper;
use App\Models\OathClient;
use Illuminate\Support\Facades\Auth;
class OathClientHelper
{
    function checkClient($clientName ,$clientSecret ,$clientIp)
    {
        $client = OathClient::where('name' ,$clientName)
        ->where('secret_code' ,$clientSecret)
        ->where('ip' ,$clientIp)
        ->first();
        return $client;
    }
}
