<?php

namespace App\Services;

use App\ApiHelper;
use App\Models\TujuanTugas;
use Exception;
use App\Models\Task as Models;

class Task
{
    public static function tujuan($request){
        return TujuanTugas::orderBy('id', 'asc')->get();
    }

    public static function create($request){
        if($request->date == null || $request->time == null || $request->guest == null || $request->company == null || $request->accom == null
        || $request->tujuan_id == null || $request->detail == null){
            throw new Exception("Silahkan isi semua Data");
        }

        $task = new Models();
        $task->status = false;
        $task->user_id = $request->current_user->id;
        $task->meeting_date = $request->date;
        $task->meeting_start = $request->time;
        $task->guest_name = $request->guest;
        $task->company_name = $request->company;
        $task->accompanied = $request->accom;
        $task->tujuan_id = $request->tujuan_id;
        $task->detail = $request->detail;
        $task->save();

        return true;
    }

    public static function getAll($request){
        if($request->status != 'all'){
            $data = Models::join('public.tujuan_tugas', 'tujuan_tugas.id', '=', 'task.tujuan_id')
            ->join('auth.users', 'task.user_id', '=', 'users.id')
            ->orderBy('task.created_at', 'desc')
            ->where('status', $request->status)
            ->whereMonth('task.created_at', $request->month)
            ->whereYear('task.created_at', $request->year)
            ->get(['users.nama_lengkap', 'users.NIK','task.id', 'tujuan_tugas.name as tujuan', 'task.meeting_date as date','task.company_name as company','task.status','task.meeting_start as start', 'task.meeting_end as end', 'task.guest_name as guest','task.accompanied','task.detail', 'task.path','task.created_at']);
        } else {
            $data = Models::join('public.tujuan_tugas', 'tujuan_tugas.id', '=', 'task.tujuan_id')
            ->join('auth.users', 'task.user_id', '=', 'users.id')
            ->orderBy('task.created_at', 'desc')
            ->whereMonth('task.created_at', $request->month)
            ->whereYear('task.created_at', $request->year)
            ->get(['users.nama_lengkap', 'users.NIK', 'task.id', 'tujuan_tugas.name as tujuan', 'task.meeting_date as date', 'task.company_name as company','task.status','task.meeting_start as start', 'task.meeting_end as end', 'task.guest_name as guest','task.accompanied','task.detail', 'task.path', 'task.created_at']);
        }

        return $data->transform(function($item){
            $item->accompanied = explode(',', $item->accompanied);

            $start = explode(':', $item->start);
            $item->start = $start[0] . ':' . $start[1];
            
            if($item->end != null){
                $end = explode(':', $item->end);
                $item->end = $end[0] . ':' . $end[1];
            }

            $date = $item->created_at;
            return $item;
        });
    }

    public static function get($request){
        if($request->status != 'all'){
            $data = Models::join('public.tujuan_tugas', 'tujuan_tugas.id', '=', 'task.tujuan_id')
            ->join('auth.users', 'task.user_id', '=', 'users.id')
            ->orderBy('task.created_at', 'desc')
            ->where('user_id', $request->current_user->id)
            ->where('status', $request->status)
            ->whereMonth('task.created_at', $request->month)
            ->whereYear('task.created_at', $request->year)
            ->get(['users.nama_lengkap', 'users.NIK','task.id', 'tujuan_tugas.name as tujuan', 'task.meeting_date as date', 'task.company_name as company','task.status','task.meeting_start as start', 'task.meeting_end as end', 'task.guest_name as guest','task.accompanied','task.detail','task.path', 'task.created_at']);
        } else {
            $data = Models::join('public.tujuan_tugas', 'tujuan_tugas.id', '=', 'task.tujuan_id')
            ->join('auth.users', 'task.user_id', '=', 'users.id')
            ->orderBy('task.created_at', 'desc')
            ->where('user_id', $request->current_user->id)
            ->whereMonth('task.created_at', $request->month)
            ->whereYear('task.created_at', $request->year)
            ->get(['users.nama_lengkap', 'users.NIK', 'task.id', 'tujuan_tugas.name as tujuan', 'task.meeting_date as date', 'task.company_name as company','task.status','task.meeting_start as start', 'task.meeting_end as end', 'task.guest_name as guest','task.accompanied','task.detail', 'task.path', 'task.created_at']);
        }

        return $data->transform(function($item){
            $item->accompanied = explode(',', $item->accompanied);

            $start = explode(':', $item->start);
            $item->start = $start[0] . ':' . $start[1];
            
            if($item->end != null){
                $end = explode(':', $item->end);
                $item->end = $end[0] . ':' . $end[1];
            }

            $date = $item->created_at;
            return $item;
        });
    }

    public static function update($request, $id){
        $data = Models::find($id);
        $data->update($request->all());


        $data->status = true;
        
        $file = $request->file('file');
        $data->path = $file->hashname();
        $file->storeAs(ApiHelper::TASK_PATH, $data->path);
        $data->save();

        return true;
    }
}
