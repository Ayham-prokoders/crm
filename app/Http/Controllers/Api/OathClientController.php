<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Helper\ResponseHelper;
use App\Models\OathClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OathClientController extends Controller
{
    function create(Request $request)
    {
        $name = $request->input('name');
        $ip = $request->input('ip');
        $outhClient =OathClient::create([
            'name' =>$name,
            'ip'  =>$ip,
            'secret_code'=>Str::uuid()->toString()
        ]);
        return ResponseHelper::success($outhClient);
    }


    function all( )
    {
       
    }
}
