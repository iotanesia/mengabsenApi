<?php

namespace App\Services;

use App\Models\Master as Model;
use App\Models\User as User;
use App\Models\MstJenisIzin as ModelIzin;
use App\ApiHelper;
use Illuminate\Support\Facades\Validator;
use App\Models\MstPermission as MstPermission;

class Master
{
    public static function getMasterSideMenu($request)
    {
        $data =  Model::where('pathname', '#')
            ->whereIn('name', ['User Management', 'Master'])
            ->orderBy('order', 'asc')->paginate($request->limit ?? 10);

        // $data = $data->map(function($items){
        //     $items->menu = Model::where('parent', $items->id)->get();
        //     return $items;
        // });
       return $data = $data->map(function ($items) {
            unset($items->created_at, $items->updated_at, $items->tag_variant, $items->parent, $items->created_by, $items->updated_by, $items->mst_part, $items->deleted_at);

            $items->children = Model::where('parent', $items->id)->get(['id', 'name', 'pathname', 'parent', 'order']);
            return $items;
        });

        

        // return [
        //     'data' => $data,
        //     'last_page' => ceil(Model::where('pathname', '#')->whereIn('name', ['User Management', 'Master'])->count() / $request->limit ?? 10),
        // ];
    }

    public static function getMasterAllMenu($request)
    {
        $data = Model::all();
        return $data;
    }

    public static function createMenu($request)
    {
        $create = new Model();
        $create->name = $request->name;
        $create->category = $request->category;
        $create->icon = $request->icon;
        $create->parent = $request->parent;
        $create->order = $request->order;
        $create->pathname = $request->url;
        if ($create->name == Null || $create->parent == Null || $create->order == Null || $create->category == Null || $create->icon == Null || $create->pathname == Null) {
            return [
                'message' => "semua data harus terisi"
            ];
        }
        $create->save();
        return $create;
    }

    public static function updateMenu($request)
    {
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

    public static function deleteMenu($request, $id)
    {
        $delete = Model::find($id);
        if (!$delete) {
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

    public static function createJenisIzin($request)
    {
        $jenis = new ModelIzin();
        $jenis->nama_jenis = $request->jenis_izin;
        if ($jenis == Null) {
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

    public static function getJenisCuti($request)
    {
        $jenis = ModelIzin::all();
        return [
            'status' => true,
            'items' => $jenis
        ];
    }

    public static function getPermissionByIdRole($request)
    {
        $permission = MstPermission::where('code_role', $request->code_role)->get();

        // public static function getPermissionByIdRole($request) {
        //     $permission = MstPermission::where('id_role', $request->id_role)->get();
        //     if (!$permission) {
        //         return [
        //             'message' => 'data tidak ditemukan'
        //         ];
        //     } else {
        //         return $permission;
        //     }
        // }
        if (!$permission) {
            return (object)[
                'message' => 'data tidak ditemukan'
            ];
        } else {
            $data = MstPermission::where('pathname', '#')
                ->whereIn('name', ['User Management', 'Master'])
                ->orderBy('id', 'asc')
                ->get();



            return $permission->transform(function ($item) {
                $item->subMenu = Model::where('parent', $item->id_menu)->get(['id', 'name', 'pathname', 'parent', 'icon', 'order']);

                return $item;
            });

            $result = new \stdClass(); // Create an empty object to hold the permissions.

            // $data->each(function ($item) use ($result) {
            //     // Get the first `subMenu` and `children` items.
            //     $subMenu = MstPermission::where('parent', $item->id)->first();
            //     $children = MstPermission::where('parent', $item->id)->first();

            //     // If `subMenu` exists, add it directly to the result object.
            //     if ($subMenu) {
            //         $result->subMenu = $subMenu;
            //     }

            //     // If `children` exists, add it directly to the result object.
            //     if ($children) {
            //         $result->children = $children;
            //     }
            // });

            // return $result; // Return the result as an object without nested "Master" or "User Management".
        }
    }




    public static function addPermissionByIdRole($request)
    {
        $validator = validator::make($request->all(), [
            'code_role' => 'required',
            'id_menu' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                'message' => 'semua data harus terisi'
            ];
        } else {
            foreach ($request->id_menu as $value) {
                if (MstPermission::where('code_role', $request->code_role)->where('id_menu', $value)->count() == 0) {
                    $data = Model::where('id', $value)->first(['name', 'pathname', 'parent']);
                    if ($data->pathname != "#") {
                        $permission = new MstPermission();
                        $mstMenu = $data;
                        $permission->code_role = $request->code_role;
                        $permission->id_menu = (int) $value;
                        $permission->name = $mstMenu->name;
                        $permission->pathname = $mstMenu->pathname;
                        $permission->parent = $mstMenu->parent;
                        $permission->save();
                    }
                }
            }

            return [
                'message' => 'berhasil menambahkan permission',
                'status' => true,
                'items' => $permission != null ? $permission : '',
            ];
        }
    }

    public static function deleteUserContainer($request, $id)
    {
        $delete = User::find($id);
        if (!$delete) {
            return [
                'message' => 'id tidak ditemukan'
            ];
        } else {
            $delete->delete();
            return [
                'message' => 'user berhasil dihapus',
                'detail' => $delete,
            ];
        }
    }
}
