<?php

namespace App\Services;
use App\Models\Absen as Model;
use App\ApiHelper as Helper;
use App\Models\GlobalParam;
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

            if($absen) {
                $data['check_out'] = Carbon::now();
                $data['long_out'] = $data['long'];
                $data['lat_out'] = $data['lat'];
                $data['address_out'] = $data['address'];
                $data['desc_out'] = $data['desc'];
                $data['location_id_out'] = $data['location_id'];
                $data['image_out'] = $data['image'];
                $absen->fill($data);
                $absen->save();
                unset($data['long'],$data['lat'],$data['address'],$data['desc'],$data['location_id'],$data['image']);
            } else {
                $data['check_in'] = Carbon::now();
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
        $data = Model::where('user_id',$request->current_user->id)->get();
        return $data->transform(function($item){
            $attendTime = $item->check_in ? Carbon::parse($item->check_in) : null;
            $leaveTime = $item->check_out ? Carbon::parse($item->check_out) : null;
            $item->location_name = $item->refLocation->name ?? null;
            $item->is_expired = Carbon::now()->format('Y-m-d') != Carbon::parse($item->check_in)->format('Y-m-d') && !$item->check_out ? true : false;
            $item->diff_time = $attendTime && $leaveTime ? $attendTime->diff($leaveTime)->format('%H Jam %i Menit %s Detik') : '-';
            $item->is_late = Carbon::parse($item->check_in)->format('H:i:s') > GlobalParam::getJamMasuk()->param_name ? true : false;
            unset($item->refLocation);
            return $item;
        });
    }
    public static function getData($request) {
        $now = Model::where('user_id',$request->current_user->id)
               ->whereDate('created_at',Carbon::now())
               ->first();
        $yesterday = Model::where('user_id',$request->current_user->id)
                     ->whereDate('created_at',Carbon::now()->subDay(1))
                     ->first();
        return [
            'attend_time_hari_ini' => isset($now->check_in) ? Carbon::parse($now->check_in)->format('Y-m-d H:i:s') : null,
            'leave_time_hari_ini' => isset($now->check_out) ? Carbon::parse($now->check_out)->format('Y-m-d H:i:s')  : null,
            'is_late_hari_ini' => isset($now->check_in) ? (Carbon::parse($now->check_in)->format('H:i:s') > GlobalParam::getJamMasuk()->param_name ? true : false) : null,
            'attend_time_hari_kemarin' => isset($yesterday->check_in) ? Carbon::parse($yesterday->check_in)->format('Y-m-d H:i:s')  : null,
            'leave_time_hari_kemarin' => isset($yesterday->check_out) ? Carbon::parse($yesterday->check_out)->format('Y-m-d H:i:s')  : null,
            'is_late_hari_kemarin' => isset($yesterday->check_in) ? (Carbon::parse($yesterday->check_in)->format('H:i:s') > GlobalParam::getJamMasuk()->param_name ? true : false) : null,
        ];
    }
}