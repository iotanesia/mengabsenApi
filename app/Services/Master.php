<?php

namespace App\Services;

use App\Models\Master as Model;
use App\Models\MstJenisIzin as ModelIzin;
use App\ApiHelper;

class Master {
    public static function getMasterSideMenu($request){
        $data =  Model::where('pathname', '#')
        ->whereIn('name', ['User Management', 'Master'])
        ->orderBy('order', 'asc')
        ->get();

        $data = $data->map(function($items){
            $items->menu = Model::where('parent', $items->id)->get();
            return $items;
        });
        $data = $data->map(function($items){
            $items->children = Model::where('parent', $items->id)->get();
            return $items;
        });
        return $data;
    }

    public static function getMasterAllMenu($request){
        $data = Model::all();   
        return $data;
    }

    public static function createMenu($request) {
        $create = new Model();
        $create->name = $request->name;
        $create->category = $request->category;
        $create->icon = $request->icon;
        $create->parent = $request->parent;
        $create->order = $request->order;
        $create->pathname = $request->url;
        if ($create->name == Null|| $create->parent == Null || $create->order == Null || $create->category == Null || $create->icon == Null || $create->pathname == Null) {
            return [
                'message' => "semua data harus terisi"
            ];
        }
        $create->save();
        return $create;
    }

    public static function updateMenu($request) {
        $update = Model::find($request->id);
        $update->name = $request->name;
        $update->pathname = $request->url;
        $update->parent = $request->parent;
        $update->order = $request->order;
        if ($update->name == Null || $update->pathname == Null || $update->parent == Null || $update->order == Null) {
            return [
                'message' => "semua data harus terisi"
            ];
        } else {
            $update->save();
        }
        return $update;
    }

    public static function deleteMenu($request, $id){
        $delete = Model::find($id);
        if(!$delete){
            return [
                'message' => 'id tidak ditemukan'
            ];
        } else {
            $delete->delete();
            return [
                'message' => 'menu berhasil dihapus',
                'detail' => $delete
            ];
        }
    }

    public static function createJenisIzin($request) {
        $jenis = new ModelIzin();
        $jenis->nama_jenis = $request->jenis_izin;
        if($jenis == Null){
            return [
                'message' => 'jenis izin tidak boleh kosong'
            ];
        } else {
            $jenis->save();
            return [
                'message' => 'berhasil input jenis izin',
                'status' => true,
                'items' => $jenis
            ];
        }
    }
    
    public static function getJenisCuti($request){
        $jenis = ModelIzin::all();
        return [
            'status' => true,
            'items' => $jenis
        ];
    }   
}