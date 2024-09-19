<?php

namespace App\Http\Controllers\Api;
use App\Services\Reimburs;
use Illuminate\Http\Request;
use App\ApiHelper as Response;

use App\Http\Controllers\Controller;
use App\Models\Reimburs as ModelsReimburs;
use Illuminate\Support\Facades\Storage;

class ReimbursController extends Controller
{
    public function tujuan(Request $request){
        return Response::responseData(['items' => Reimburs::tujuan($request) ]);
    }

    public function get(Request $request){
        return Response::responseData(['items' => Reimburs::get($request)]);
    }

    public function getAll(Request $request){
        return Response::responseData(['items' => Reimburs::getAll($request)]);
    }

    public function create(Request $request){
        return Response::responseData(['items' => Reimburs::create($request)]);
    }

    public function update(Request $request, $id){
        return Response::responseData(['items' => Reimburs::update($request, $id)]);
    }

    public function bukti(Request $request){
        $file = Storage::path(Response::REIMBURS_PATH ."/". $request->name);
        $name = ModelsReimburs::where("bukti_path", "like", "%". $request->name ."%");

        $index = array_search($request->name, explode(",", $name->value('bukti_path')));

        $nameArr = explode(",", $name->value('bukti_file'));
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nameArr[$index] . '"',
        ];

        return response()->download($file, $nameArr[$index], $headers);
    }
}
