<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Services\User as Service;
use App\Services\Signature;
use Illuminate\Support\Facades\File;

class AuthControler extends Controller
{
    public function login(Request $request)
    {
        return Response::responseData(Service::authenticateuser($request));
    }
    public function regis(Request $request) {
        return Response::responseData(['items' => Service::saveData($request)]);
    }
}
