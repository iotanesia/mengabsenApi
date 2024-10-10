<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Services\Master as Service;

class MasterController extends Controller
{
    public function getMasterSideMenu(Request $request){
        return Response::responseData(['items' => Service::getMasterSideMenu($request)]);
    }

    public function getMasterAllMenu(Request $request){
        return Response::responseData(['items' => Service::getMasterAllMenu($request)]);
    }

    public function createMenu(Request $request){
        return Response::responseData(['items' => Service::createMenu($request)]);
    }

    public function deleteMenu(Request $request, $id){
        return Response::responseData(['items' => Service::deleteMenu($request, $id)]);
    }

    public function updateMenu(Request $request){
        return Response::responseData(['items' => Service::updateMenu($request)]);
    }

    public function createJenisIzin(Request $request){
        return Response::responseData(['items' => Service::createJenisIzin($request)]);
    }

    public function getJenisCuti(Request $request){
        return Response::responseData(['items' => Service::getJenisCuti($request)]);
    }
}
