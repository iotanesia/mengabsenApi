<?php

namespace App\Services;

use DateTime;
use App\ApiHelper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\UserRole;
use App\Models\Cuti as Model;
use Illuminate\Support\Facades\DB;

class Cuti
{

    public static function getAll($request){

        $role = UserRole::where('id_user', $request->current_user->id)->value('code_role');
       
        if($request->status != "all"){
            
            $cuti = Model::join('auth.users', 'cuti.user_id', '=', 'users.id')
            ->join('jabatan', DB::raw('cast(cuti.jabatan as bigint)'), '=', 'jabatan.id')
            ->where('cuti.status',  ($request->status == "null" ? null : $request->status))
            ->whereMonth("cuti.created_at", $request->month)
            ->whereYear("cuti.created_at", $request->year)
            ->orderBy('cuti.created_at', "desc")
            ->get(["cuti.id", "cuti.from", "cuti.to", "cuti.status", "cuti.alasan", "jabatan.name as jabatan", "cuti.jenis_izin", "cuti.filename", "cuti.alasan_tolak", "cuti.pic_status", "cuti.hrd_status", "cuti.created_at","users.nama_lengkap", "users.NIK"]);
        } else {
            $cuti = Model::join('auth.users', 'cuti.user_id', '=', 'users.id')
            ->join('jabatan', DB::raw('cast(cuti.jabatan as bigint)'), '=', 'jabatan.id')
            ->whereMonth("cuti.created_at", $request->month)
            ->whereYear("cuti.created_at", $request->year)
            ->orderBy('cuti.created_at', "desc")
            ->get(["cuti.id", "cuti.from", "cuti.to", "cuti.status", "cuti.alasan", "jabatan.name as jabatan", "cuti.jenis_izin", "cuti.filename", "cuti.alasan_tolak", "cuti.pic_status", "cuti.hrd_status", "cuti.created_at","users.nama_lengkap", "users.NIK"]);  
        }

        if($role == "01" || $role == "04"){
            return $cuti;
        } else {
            throw new \Exception("Tidak dapat Akses"); 
        }
    }

    public static function get($request){
        if($request->status != "all"){   
            $data = Model::join('auth.users', 'cuti.user_id', '=', 'users.id')
            ->join('jabatan', DB::raw('cast(cuti.jabatan as bigint)'), '=', 'jabatan.id')
            ->where('cuti.user_id', $request->current_user->id)
            ->where('cuti.status', ($request->status == "null" ? null : $request->status))
            ->whereMonth("cuti.created_at", $request->month)
            ->whereYear("cuti.created_at", $request->year)
            ->orderBy('cuti.created_at', "desc")
            ->get(["cuti.id", "cuti.from", "cuti.to", "cuti.status", "cuti.alasan", "jabatan.name as jabatan", "cuti.jenis_izin", "cuti.filename", "cuti.created_at", "cuti.alasan_tolak" ,"cuti.pic_status", "cuti.hrd_status", "users.nama_lengkap", "users.NIK"]);
        } else {
            $data = Model::join('auth.users', 'cuti.user_id', '=', 'users.id')
            ->join('jabatan', DB::raw('cast(cuti.jabatan as bigint)'), '=', 'jabatan.id')
            ->where('cuti.user_id', $request->current_user->id)
            ->whereMonth("cuti.created_at", $request->month)
            ->whereYear("cuti.created_at", $request->year)
            ->orderBy('cuti.created_at', "desc")
            ->get(["cuti.id", "cuti.from", "cuti.to", "cuti.status", "cuti.alasan", "jabatan.name as jabatan", "cuti.jenis_izin", "cuti.filename", "cuti.created_at", "cuti.alasan_tolak" ,"cuti.pic_status", "cuti.hrd_status", "users.nama_lengkap", "users.NIK"]);
        }

        return $data;
    }
    
    public static function detail($request, $id){
        return Model::where('cuti.id', $id)->join('auth.users as user', 'user.id', '=' ,'cuti.user_id')->first();
    }

    public static function create($request)
    {    
        $tglfrom = explode('/', $request->from);
        $tglto = explode('/', $request->to);
        
        $user = User::where('id', $request->current_user->id)->first();

        
        if($request->from == null || $request->to == null || $request->alasan == null || $request->jabatan == null || $request->jenis_izin == null){
            throw new \Exception("Silahkan Isi Semua Data");
        }

        if($request->lampiran == null && $request->jenis_izin == "Sakit"){
            throw new \Exception("Silahkan Upload Lampiran");
        }

        if($user->sisa_cuti <= 0){
            throw new \Exception("Tidak ada Sisa Cuti Tersisa"); 
        }
        
        if(new \DateTime($tglfrom[2] . '-' . $tglfrom[1] . '-'. $tglfrom[0]) > new \DateTime($tglto[2] . '-' . $tglto[1] . '-'. $tglto[0])){
            throw new \Exception("Tanggal Pengajuan tidak valid");
        }
        
        $cuti = new Model();
        $cuti->user_id = $request->current_user->id;
        $cuti->from = $tglfrom[2] . '-' . $tglfrom[1] . '-'. $tglfrom[0];
        $cuti->to = $tglto[2] . '-' . $tglto[1] . '-'. $tglto[0];
        $cuti->status = null;
        $cuti->alasan = $request->alasan;

        if($request->jenis_izin == "Sakit"){
            $lampiran = $request->file('lampiran');
            $cuti->lampiran = $lampiran->hashname();
            $lampiran->storeAs(ApiHelper::CUTI_PATH, $cuti->lampiran);
        }
        
        $cuti->updated_by = $request->current_user->id;
        $cuti->jabatan = $request->jabatan;
        $cuti->jenis_izin = $request->jenis_izin;
        $cuti->filename = $request->filename;

        if($request->jenis_izin == "Cuti"){
            $user->sisa_cuti -= Carbon::parse($cuti->from)->diffInDays($cuti->to) + 1;
            $user->save();
        }

        $cuti->save();  

        return true;
    }

    public static function update($request, $id)
    {
        $cuti = Model::where('id', $id)->first();
        $cuti->update($request->all());

        $data = Model::where('id', $id)->first();

        if(($data->pic_status && $data->hrd_status) || ($data->hrd_status === null && $data->pic_status)){
            $data->status = true;
            $data->save();
        } else if ($data->pic_status === false || $data->hrd_status === false){ 
            $user = User::where('id', $data->user_id)->first();
            $user->sisa_cuti += Carbon::parse($data->from)->diffInDays($data->to) + 1;
            $user->save();

            $data->status = false;
            $data->save();
        }

        return Model::where('cuti.id', $id)->Join('auth.users as user', 'user.id', '=', 'cuti.user_id')->first();
    }

    public static function delete($request, $id){
        $cuti = Model::find($id)->delete();
        return $cuti;
    }

    public static function jabatan($request){
        return Jabatan::all();
    }

    public static function sisa($request){
        return User::where('id', $request->current_user->id)->value('sisa_cuti');
    }
}
