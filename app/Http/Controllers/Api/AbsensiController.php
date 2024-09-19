<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Exports\RekapExport;
use App\Services\ServiceAbsensi as Service;
use Maatwebsite\Excel\Facades\Excel;

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

    public function historyAbsen(Request $request)
    {
        return Response::responseData(Service::historyAbsen($request));
    }
    
    public function attendNow(Request $request)
    {
        return Response::responseData(['items' => Service::getData($request)]);
    }
    public function ddLeave(Request $request)
    {
        return Response::responseData(['items' => [['value'=>'Izin','label'=>'Izin'],['value'=>'Sakit','label'=>'Sakit'],['value'=>'Cuti','label'=>'Cuti']]]);
    }

    public function generateRekapAbsen(Request $request){
        $data = Service::generateRekapAbsen($request);
        return Excel::download(new RekapExport($data['data'], 'rekap'), $data['filename']);
    }
    
}
