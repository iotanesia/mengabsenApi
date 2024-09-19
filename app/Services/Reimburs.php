<?php

namespace App\Services;

use App\ApiHelper;
use App\Models\JenisReimburs;
use App\Models\TujuanReimburs;
use App\Models\Reimburs as ModelsReimburs;
use Exception;

class Reimburs
{
    public static function tujuan($request){
        $jenis = JenisReimburs::where('name', $request->name)->value('id');
        return TujuanReimburs::where('jenis_id', $jenis)->get();
    }

    public static function get($request){
        if($request->status != 'all'){
            $data = ModelsReimburs::where('user_id', $request->current_user->id)
            ->where('reimburs.status', ($request->status == "null" ? null : $request->status))
            ->whereMonth("reimburs.created_at", $request->month)->whereYear("reimburs.created_at", $request->year)
            ->join('public.jenis_reimburs', 'jenis_reimburs.id', '=', 'reimburs.jenis_id')
            ->join('auth.users', 'reimburs.user_id', '=', 'users.id')
            ->orderBy('reimburs.created_at', 'desc')
            ->get(['reimburs.id', 'reimburs.user_id', 'users.nama_lengkap as nama' ,'jenis_reimburs.name as jenis_reimburs', 'reimburs.tujuan_id', 'reimburs.status', 'reimburs.no_pengembalian'
            ,'reimburs.tgl_pemakaian', 'reimburs.bank', 'reimburs.bukti_path', 'reimburs.bukti_file', 'reimburs.biaya', 'reimburs.created_at', 'reimburs.alasan_tolak']);
        } else {
            $data = ModelsReimburs::where('user_id', $request->current_user->id)
            ->whereMonth("reimburs.created_at", $request->month)->whereYear("reimburs.created_at", $request->year)
            ->join('public.jenis_reimburs', 'jenis_reimburs.id', '=', 'reimburs.jenis_id')
            ->join('auth.users', 'reimburs.user_id', '=', 'users.id')
            ->orderBy('reimburs.created_at', 'desc')
            ->get(['reimburs.id', 'reimburs.user_id', 'users.nama_lengkap as nama' ,'jenis_reimburs.name as jenis_reimburs', 'reimburs.tujuan_id', 'reimburs.status', 'reimburs.no_pengembalian'
            ,'reimburs.tgl_pemakaian', 'reimburs.bank', 'reimburs.bukti_path', 'reimburs.bukti_file', 'reimburs.biaya', 'reimburs.created_at', 'reimburs.alasan_tolak']);
        }

        return $data->transform(function($item){
            $item->tujuan = TujuanReimburs::whereIn('id', explode(',', $item->tujuan_id))->pluck('name')->toArray() ;
            $item->bukti_path = explode(',', $item->bukti_path);
            $item->bukti_file = explode(',', $item->bukti_file);
            $item->biaya = array_map('intval', explode(',', $item->biaya));
            $dateArr = explode('-', $item->tgl_pemakaian);
            $item->tgl_pemakaian = $dateArr[2] . "/" . $dateArr[1] . "/" . $dateArr[0];
            unset($item->tujuan_id);
            return $item;
        });
    }

    public static function getAll($request){
        if($request->status != 'all'){
            $data = ModelsReimburs::where('reimburs.status', ($request->status == "null" ? null : $request->status))
            ->whereMonth("reimburs.created_at", $request->month)
            ->whereYear("reimburs.created_at", $request->year)
            ->join('public.jenis_reimburs', 'jenis_reimburs.id', '=', 'reimburs.jenis_id')
            ->join('auth.users', 'reimburs.user_id', '=', 'users.id')
            ->orderBy('reimburs.created_at', 'desc')
            ->get(['reimburs.id', 'reimburs.user_id', 'users.nama_lengkap as nama' ,'jenis_reimburs.name as jenis_reimburs', 'reimburs.tujuan_id', 'reimburs.status', 'reimburs.no_pengembalian'
        ,'reimburs.tgl_pemakaian', 'reimburs.bank', 'reimburs.bukti_path', 'reimburs.bukti_file', 'reimburs.biaya', 'reimburs.created_at', 'reimburs.alasan_tolak']);
        } else {
            $data = ModelsReimburs::whereMonth("reimburs.created_at", $request->month)
            ->whereYear("reimburs.created_at", $request->year)
            ->join('public.jenis_reimburs', 'jenis_reimburs.id', '=', 'reimburs.jenis_id')
            ->join('auth.users', 'reimburs.user_id', '=', 'users.id')
            ->orderBy('reimburs.created_at', 'desc')
            ->get(['reimburs.id', 'reimburs.user_id', 'users.nama_lengkap as nama' ,'jenis_reimburs.name as jenis_reimburs', 'reimburs.tujuan_id', 'reimburs.status', 'reimburs.no_pengembalian'
            ,'reimburs.tgl_pemakaian', 'reimburs.bank', 'reimburs.bukti_path', 'reimburs.bukti_file', 'reimburs.biaya', 'reimburs.created_at', 'reimburs.alasan_tolak']);
        }

        return $data->transform(function($item){
            $item->tujuan = TujuanReimburs::whereIn('id', explode(',', $item->tujuan_id))->pluck('name')->toArray() ;
            $item->bukti_path = explode(',', $item->bukti_path);
            $item->bukti_file = explode(',', $item->bukti_file);
            $item->biaya = array_map('intval', explode(',', $item->biaya));
            $dateArr = explode('-', $item->tgl_pemakaian);
            $item->tgl_pemakaian = $dateArr[2] . "/" . $dateArr[1] . "/" . $dateArr[0];
            unset($item->tujuan_id);
            return $item;
        });
    }

    public static function create($request){    
    if($request->jenis == null || $request->tujuan_id == null|| $request->no_pengembalian == null || $request->tgl_pemakaian == null || 
    $request->bank == null || $request->bukti == null || $request->biaya == null){
        throw new \Exception("Silahkan isi semua Data");
        }
        
        $file = $request->file('bukti');
        $dateReq = explode('/', $request->tgl_pemakaian);

        $bukti_path = [];
        $bukti_file = [];
        
        foreach($file as $file){
            $bukti_file[] = $file->getClientOriginalName();
            $hash = $file->hashname();
            $bukti_path[] = $hash;
            $file->storeAs(ApiHelper::REIMBURS_PATH, $hash);
        }

        $reimburs = new ModelsReimburs();
        $reimburs->user_id = $request->current_user->id;
        $reimburs->jenis_id = JenisReimburs::where('name', $request->jenis)->value('id');
        $reimburs->tujuan_id = $request->tujuan_id;
        $reimburs->no_pengembalian = $request->no_pengembalian;
        $reimburs->tgl_pemakaian = $dateReq[2] . "-" . $dateReq[1] . "-" . $dateReq[0];
        $reimburs->bank = $request->bank;
        $reimburs->bukti_path = implode(',', $bukti_path);
        $reimburs->bukti_file = implode(',', $bukti_file);
        $reimburs->biaya = $request->biaya;
        $reimburs->save();

    

        $data = ModelsReimburs::where('reimburs.user_id', $reimburs->user_id)
        ->where('reimburs.jenis_id', $reimburs->jenis_id)
        ->where('reimburs.tujuan_id', $reimburs->tujuan_id)
        ->where('reimburs.no_pengembalian', $reimburs->no_pengembalian)
        ->where('reimburs.tgl_pemakaian', $reimburs->tgl_pemakaian)
        ->where('reimburs.bank', $reimburs->bank)
        ->where('reimburs.bukti_path', $reimburs->bukti_path)
        ->where('reimburs.bukti_file', $reimburs->bukti_file)
        ->where('reimburs.biaya', $reimburs->biaya)
        ->join('public.jenis_reimburs', 'jenis_reimburs.id', '=', 'reimburs.jenis_id')
        ->join('auth.users', 'reimburs.user_id', '=', 'users.id')
        ->get(['users.nama_lengkap as nama', 'reimburs.id', 'reimburs.user_id', 'jenis_reimburs.name as jenis_reimburs', 'reimburs.tujuan_id', 'reimburs.status', 'reimburs.no_pengembalian','reimburs.tgl_pemakaian', 'reimburs.bank', 'reimburs.bukti_path', 'reimburs.bukti_file', 'reimburs.biaya', 'reimburs.created_at'])->first();
        
        if($data){
            $data->tujuan = TujuanReimburs::whereIn('id', explode(',', $data->tujuan_id))->pluck('name')->toArray() ;
            $data->bukti_path = explode(',', $data->bukti_path);
            $data->bukti_file = explode(',', $data->bukti_file);
            $data->biaya = array_map('intval', explode(',', $data->biaya));
            $dateArr = explode('-', $data->tgl_pemakaian);
            $data->tgl_pemakaian = $dateArr[2] . "/" . $dateArr[1] . "/" . $dateArr[0];
            unset($data->tujuan_id);
        };
        return $data;
    }

    public static function update($request, $id){
        $data = ModelsReimburs::where('id', $id)->first();
        $data->update($request->all());

        return true;
    }
}
