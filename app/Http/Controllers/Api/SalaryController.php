<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ApiHelper as Response;
use App\Services\Salary;

class SalaryController extends Controller
{
    public function users(Request $request){
        return Response::responseData(['items' => Salary::users($request)]);
    }

    public function getAll(Request $request){
        return Response::responseData(['items' => Salary::getAll($request)]);
    }

    public function create(Request $request){
        return Response::responseData(['items' => Salary::create($request)]);
    }
}
