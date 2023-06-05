<?php

namespace App\Services;
use App\Models\Absen as Model;
use App\ApiHelper as Helper;
use Carbon\Carbon;

class ServiceAbsensi {
    public static function absensi($request)
    {
        $absen = Model::where('user_id',$request->current_user->id)->whereDate('created_at',Carbon::now()->format('Y-m-d'))->first();
        $data = $request->all();
        $absen ? $data['check_out'] = Carbon::now() : $data['check_in'] = Carbon::now();
        if($absen) {
            $absen->fill($data);
            $absen->save();
        }
        return Model::create($data);
    }
}