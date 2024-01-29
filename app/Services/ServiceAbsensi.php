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
    public static function historyAbsen($request) {
        $data = Model::orderBy('created_at', 'desc')->paginate(100);
        return [
            'items' => $data->transform(function ($item, $key) {
                $attendTime = $item->check_in ? Carbon::parse($item->check_in) : null;
                $leaveTime = $item->check_out ? Carbon::parse($item->check_out) : null;
                $now = Carbon::now();
    
                $res['no'] = $key + 1;
                $res['username'] = $item->refUser->username ?? null;
                $res['type'] = $item->type;
                $res['check_in'] = $item->check_in;
                $res['desc'] = $item->desc ?? '-';
                $res['check_out'] = $item->check_out ?? '-';
                $res['desc_out'] = $item->desc_out ?? '-';
                $res['status'] = Carbon::parse($item->check_in)->format('H:i:s') > GlobalParam::getJamMasuk()->param_name ? 'Late' : 'On Time';
                if ($item->check_out == null) $res['status'] = $attendTime->format('d') == $now->format('d') ? '-' : 'Lupa Absen Pulang';
                $res['lama_bekerja'] = $attendTime && $leaveTime ? $attendTime->diff($leaveTime)->format('%H Jam %i Menit %s Detik') : '-';
    
                return $res;
            }),
            'attributes' => [
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'per_page' => (int)$data->perPage(),
                'last_page' => $data->lastPage(),
            ],
        ];
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

    public static function generateRekapAbsen($request) {
        try {
            $dataAbsen = Model::select('user.username as name', 'absen.check_in', 'absen.check_out', 'absen.type',
             'absen.desc as desc_in', 'absen.desc_out', 'absen.address as addess_in', 'absen.address_out')
            ->leftJoin('auth.users as user','user.id','absen.user_id')->where(function ($query) use ($request){
                if($request->startDate && $request->endDate){
                    $startDateParam = Carbon::parse($request->startDate.' 00:00:00')->format('Y-m-d H:i:s');
                    $endDateParam = Carbon::parse($request->endDate.' 23:59:59')->format('Y-m-d H:i:s');
                    // $query->where('absen.check_in', '>=', $startDateParam)
                    // ->where('absen.check_out', '<=', $endDateParam);
                    $query->whereBetween('absen.check_in', [$request->startDate, $request->endDate]);
                }
                if($request->name){
                    $query->where('user.username','ilike', "%" . $request->name . "%");
                }
            })->orderBy('absen.created_at', 'desc')->get();
            foreach($dataAbsen as $item){
                $attendTime = $item->check_in ? Carbon::parse($item->check_in) : null;
                $leaveTime = $item->check_out ? Carbon::parse($item->check_out) : null;
                $item->diff_time = $attendTime && $leaveTime ? $attendTime->diff($leaveTime)->format('%H Jam %i Menit %s Detik') : '-';
                $item->is_late = Carbon::parse($item->check_in)->format('H:i:s') > GlobalParam::getJamMasuk()->param_name ? true : false;
            }
            $filename = 'REKAP-ABSEN-'.Carbon::now()->format('Ymd');
            if($request->startDate && $request->endDate){
                'REKAP-ABSEN-'.Carbon::parse($request->startDate)->format('dmY').'-'.Carbon::parse($request->endDate)->format('dmY');
            }
            $pathToFile =  storage_path().'/absen/rekap/'.$filename.'.xls';
            $dataSend['data'] = $dataAbsen;
            $dataSend['filename'] = $filename.'.xls';
            $dataSend['pathToFile'] = $pathToFile;
            return $dataSend;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}