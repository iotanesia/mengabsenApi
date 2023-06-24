<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Services\ServiceAbsensi as Service;
use App\Services\Signature;
use Illuminate\Support\Facades\File;

class AbsensiController extends Controller
{
    public function absen(Request $request)
    {
        return Response::responseData(['items' => Service::absensi($request)]);
    }
    public function listAbsen(Request $request)
    {
        return Response::responseData(['items' => Service::getList($request)]);
    }
    public function attendNow(Request $request)
    {
        return Response::responseData(['items' => Service::getData($request)]);
    }
    public function ddLeave(Request $request)
    {
        return Response::responseData(['items' => ['Izin','Sakit','Cuti']]);
    }
}
