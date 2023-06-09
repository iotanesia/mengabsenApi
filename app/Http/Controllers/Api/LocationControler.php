<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Models\Location;
use App\Services\Location as Service;

class LocationControler extends Controller
{
    public function getAll(Request $request)
    {
        return Response::responseData(['items' => Location::all()]);
    }
    public function save(Request $request)
    {
        return Response::responseData(Service::add($request));
    }
    public function update(Request $request,$id)
    {
        return Response::responseData(Service::edit($request,$id));
    }
    public function delete(Request $request,$id)
    {
        return Response::responseData(Service::delete($id));
    }
}
