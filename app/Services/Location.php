<?php

namespace App\Services;

use App\Models\BannerDashboard;
use App\Models\Location AS Model;
use Illuminate\Support\Facades\DB;

class Location {
    public static function add($params) {
        DB::beginTransaction();
        try {
            $data = $params->all();
            $data['user_id'] = $params->current_user->id;
            $res = Model::create($data);
            DB::commit();
            return ['items'=>$res];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function edit($params,$id) {
        DB::beginTransaction();
        try {
            $data = $params->all();
            $data['user_id'] = $params->current_user->id;
            $res = Model::find($id);
            $res->fill($data);
            $res->save();
            DB::commit();
            return ['items'=>$res];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function delete($id) {
        DB::beginTransaction();
        try {
            $res = Model::find($id)->delete();
            DB::commit();
            return ['items'=>$res];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public static function bannerDashboard() {
        return ['items'=>BannerDashboard::get()];
    }
}