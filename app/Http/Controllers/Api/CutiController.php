<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Services\Cuti;
use App\Models\Jabatan;
use App\Models\UserRole;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Models\Cuti as C;
use App\Constants\ErrorCode;
use Illuminate\Http\Request;
use App\ApiHelper as Response;
use App\Constants\ErrorMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class CutiController extends Controller
{

    public function getAll(Request $request)
    {
        return Response::responseData(['items' => Cuti::getAll($request)]);
    }

    public function get(Request $request)
    {
        return Response::responseData(['items' => Cuti::get($request)]);
    }

    public function detail(Request $request, $id)
    {
        return Response::responseData(['items' => Cuti::detail($request, $id)]);
    }

    public function create(Request $request)
    {
        return Response::responseData(['items' => Cuti::create($request)]);
    }

    public function update(Request $request, $id)
    {
        return Response::responseData(['items' => Cuti::update($request, $id)]);
    }

    public function delete(Request $request, $id)
    {
        return Response::responseData(['items' => Cuti::delete($request, $id)]);
    }

    public function lampiran(Request $request, $id)
    {
        $lampiran = C::where('id', $id)->value('lampiran');
        $lampiran_type = explode('.', $lampiran);
        $file = Storage::path('public/images/cuti/'. $lampiran);
        $fileName = 'lampiran.'. $lampiran_type[1];
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return response()->download($file, $fileName, $headers);
    }

    public function formCuti(Request $request, $id){
        $cuti = C::join('auth.users', 'cuti.user_id', '=', 'users.id')->where('cuti.id', $id)->orderBy('cuti.from')->first(["cuti.id", "cuti.from", "cuti.to", "cuti.status", "cuti.alasan", "cuti.jabatan","cuti.created_at", "cuti.updated_by" ,"cuti.jenis_izin", "cuti.filename", "users.nama_lengkap", "users.NIK"]);

        if($cuti->status != true){
            return Response::unauthorizedResponse(ErrorCode::UNAUTHORIZED, ErrorMessage::UNAUTHORIZED);
        }

        $file = IOFactory::load(Storage::path('form/form cuti.xlsx'));
        $worksheet = $file->getActiveSheet();

        $date = explode('T', $cuti->created_at);
        $create = explode(' ', $date[0]);

        $worksheet->setCellValue('E4', " ".Carbon::createFromFormat('Y-m-d', $create[0])->format('d/m/Y'));
        $worksheet->setCellValue('B7', "$cuti->NIK");
        $worksheet->setCellValue('G7', $cuti->nama_lengkap);
        $worksheet->setCellValue('L7', Jabatan::where("id", $cuti->jabatan)->value('name'));
        $worksheet->setCellValue('E10'," " .Carbon::createFromFormat('Y-m-d', $cuti->from)->format('d/m/Y') .' s/d ' . Carbon::createFromFormat('Y-m-d', $cuti->to)->format('d/m/Y'));
        $worksheet->setCellValue('B19', $cuti->alasan);
        $worksheet->setCellValue('B30', $cuti->nama_lengkap);
        $worksheet->setCellValue('G30', User::where('id', $cuti->updated_by)->value('nama_lengkap'));

        $drawing = new Drawing();
        $drawing->setPath(Storage::path('image/approved.png'));
        $drawing->setCoordinates('I27');
        $drawing->setWorksheet($worksheet);

        
        if($cuti->jenis_izin == "Cuti"){
            $worksheet->setCellValue('C14', " ✔ ");
        } else if ($cuti->jenis_izin == "Izin"){
            $worksheet->setCellValue('H14', " ✔ ");
        } else {
            $worksheet->setCellValue('M14', " ✔ ");
        }

        
        IOFactory::registerWriter('Pdf', Mpdf::class);
        $writer = IOFactory::createWriter($file, 'Pdf');
        $filePath = Storage::path('public/form/'.'Cuti'. $cuti->nama_lengkap . $cuti->to .'.pdf');
        $writer->save($filePath);

        return response()->download($filePath);

    }

    public function jabatan(Request $request){
        return Response::responseData(['items' => Cuti::jabatan($request)]);
    }

    public function sisa(Request $request){
        return Response::responseData(['items' => Cuti::sisa($request)]);
    }
}
