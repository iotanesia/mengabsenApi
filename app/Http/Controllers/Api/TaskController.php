<?php

namespace App\Http\Controllers\Api;

use App\Services\Task;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Http\Controllers\Controller;
use App\Models\Task as ModelsTask;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;

class TaskController extends Controller
{
    public function tujuan(Request $request){
        return Response::responseData(['items' => Task::tujuan($request)]);
    }

    public function create(Request $request){
        return Response::responseData(['items' => Task::create($request)]);
    }

    public function get(Request $request){
        return Response::responseData(['items' => Task::get($request)]);
    }

    public function getAll(Request $request){
        return Response::responseData(['items' => Task::getAll($request)]);
    }

    public function update(Request $request, $id){
        return Response::responseData(['items' => Task::update($request, $id)]);
    }

    public function form(Request $request, $id){
        $data = ModelsTask::where('task.id', $id)->join('public.tujuan_tugas', 'tujuan_tugas.id', '=', 'task.tujuan_id')->first();
        $user = User::where('id', $request->current_user->id)->first();

        $file = IOFactory::load(Storage::path('form/form kunjungan.xlsx'));
        $worksheet = $file->getActiveSheet();

        $worksheet->setCellValue('D8', $data->meeting_date);
        $worksheet->setCellValue('D9', $data->meeting_start);
        $worksheet->setCellValue('D10', $user->nama_lengkap);
        $worksheet->setCellValue('D11', $user->NIK);
        $worksheet->setCellValue('D12', $data->guest_name);
        $worksheet->setCellValue('D13', $data->company_name);
        $worksheet->setCellValue('D14', $data->name);

        // IOFactory::registerWriter('Pdf', Mpdf::class);
        $writer = IOFactory::createWriter($file, 'Xlsx');
        $filePath = Storage::path('public/form/'.'SuratTugas'. $user->nama_lengkap .'.xlsx');
        $writer->save($filePath);

        return response()->download($filePath);
    }

    public function bukti(Request $request, $id){
        $data = ModelsTask::find($id);
        $file = Storage::path(Response::TASK_PATH ."/". $data->path);

        return response()->download($file, $data->filename);
    }
}
