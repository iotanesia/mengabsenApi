<?php

namespace App\Services;
use App\Models\Absen as Model;
use App\ApiHelper as Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceAbsensi {
    public static function absensi($request)
    {
        try {
            DB::beginTransaction();
            $absen = Model::where('user_id',$request->current_user->id)->whereDate('created_at',Carbon::now()->format('Y-m-d'))->first();
            $data = $request->all();
            $img = $request->file ? Helper::uploadImage($request->file,Helper::ABSENSI_PATH) : null;
            $data['user_id'] = $request->current_user->id;
            $data['image'] = $img;
            $absen ? $data['check_out'] = Carbon::now() : $data['check_in'] = Carbon::now();
            if($absen) {
                $absen->fill($data);
                $absen->save();
            } else {
                $absen = Model::create($data);
            }
            DB::commit();
            return $absen;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function getList($request) {
        return Model::where('user_id',$request->current_user->id)->get();
    }
}