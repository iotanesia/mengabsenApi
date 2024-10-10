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
    public function version(Request $request) {
        return Response::responseData(['items' => Service::version($request)]);
    }
    public function profile(Request $request) {
        return Response::responseData(['items' => Service::profile($request)]);
    }
    public function updateProfile(Request $request) {
        return Response::responseData(['items' => Service::updateProfile($request)]);
    }

    public function create(Request $request){
        return Response::responseData(['items' => Service::create($request)]);
    }

    public function getById(Request $request, $id){
        return Response::responseData(['items' => Service::getById($request, $id)]);
    }

    public function getAllUser(Request $request){
        return Response::responseData(['items' => Service::getAllUser($request)]);
    }

    public function updateById(Request $request, $id){
        return Response::responseData(['items' => Service::updateById($request, $id)]);
    }

    public function deleteById(Request $request, $id){
        return Response::responseData(['items' => Service::deleteById($request, $id)]);
    }

    public function getAllData(Request $request){
        return Response::responseData(['items' => Service::getAllData($request)]);
    }

    public function getAllRole(Request $request){
        return Response::responseData(['items' => Service::getAllRole($request)]);
    }

    public function addMstRole(Request $request){
        return Response::responseData(['items' => Service::addMstRole($request)]);
    }

    public function updateRole(Request $request){
        return Response::responseData(['items' => Service::updateRole($request)]);
    }

    public function deleteRole(Request $request, $id){
        return Response::responseData(['items' => Service::deleteRole($request, $id)]);
    }

    public function getRoleById(Request $request, $id){
        return Response::responseData(['items' => Service::getRoleById($request, $id)]);
    }
}
