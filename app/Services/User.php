<?php

namespace App\Services;
use App\Models\User as Model;
use App\ApiHelper as Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Constants\Group;
use App\Models\GlobalParam;
use App\Models\MstRole;
use App\Models\UserRole;
use App\Models\Master;

class User {

    public static function authenticateuser($params)
    {
        $required_params = [];
        if (!$params->email) $required_params[] = 'email';
        if (!$params->password) $required_params[] = 'password';
        if (count($required_params)) throw new \Exception("Parameter berikut harus diisi: " . implode(", ", $required_params));
        $user = Model::where('email',$params->email)->first();
        if(!$user) throw new \Exception("Pengguna belum terdaftar.");
        if (!Hash::check($params->password, $user->password)) throw new \Exception("Email atau password salah.");
        $user->access_token = Helper::createJwt($user);
        $user->expires_in = Helper::decodeJwt($user->access_token)->exp;
        $user->role = (UserRole::where('id_user', $user->id)->count() <= 0 ? 'Staff' : MstRole::where('code', UserRole::where('id_user', $user->id)->value('code_role'))->value('name'));
        $user->menu = Master::whereIn('icon', ['folder-cog', 'user-cog', 'layers'])
        ->whereIn('name',    ['Dashboard', 'User Management', 'Master'])
        ->orderBy('order', 'asc')
        ->get()->map(function($items){
            $items->subMenu = Master::where('parent', $items->id)->get();
            return $items;
        });;

        unset($user->ip_whitelist);
        return [
            'items' => $user,
            'attributes' => null
        ];
    }

    public static function getAllData($request) {   
        $data = Model::where(function ($query) use ($request) {
            if($request->search) {
                $query->where('username','ilike',"%{$request->search}%")
                    ->orWhere('email','ilike',"%{$request->search}%")
                    ->orWhere('ip_whitelist','ilike',"%{$request->search}%");
            }
        })->paginate($request->limit ?? 10);
        
        return [
            'data' => $data->items(),
            'last_page' => $data->lastPage(),
            'current_page' => $data->currentPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
        ];
    }

    public static function admin($id)
    {
        return [
            'items' => Model::where('group_id',Group::ADMIN)->find($id),
            'attributes' => null
        ];
    }

    public static function byId($id)
    {
        return [
            'items' => Model::find($id),
            'attributes' => null
        ];
    }

    public static function saveData($params)
    {
        $data = $params->all();
        $data['password'] = Hash::make($params->password);
        DB::beginTransaction();
        try {
            $res = Model::create($data);
            DB::commit();
            return $res;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function updateData($params,$id)
    {
        DB::beginTransaction();
        try {
            $update = Model::find($id);
            if(!$update) throw new \Exception("id tidak ditemukan.");
            $update->username = $params->username;
            $update->app_name = $params->app_name;
            $update->email = $params->email;
            $update->ip_whitelist = $params->ip_whitelist;
            $update->description = $params->description;
            $update->save();
            DB::commit();
            return [
                'items' => $update,
                'attributes' => null
            ];
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public static function deleteData($id)
    {
        DB::beginTransaction();
        try {
            $delete = Model::destroy($id);
            DB::commit();
            return [
                'items' => $delete,
                'attributes' => null
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function version() {
        return GlobalParam::where('param_type','Version')->get();
    }
    public static function profile($params) {
        return Model::find($params->current_user->id);
    }
    public static function updateProfile($params) {
        DB::beginTransaction();
        try {
            $user = Model::find($params->current_user->id);
            $user->fill($params->all());
            $user->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function create($request){

        if(Model::where('email', $request->email)->exists()){
            return [
                'message' => 'email sudah terdaftar'
            ];
        }

        
        $user = new Model();
        $user->app_name = "mengabsen";

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->nama_lengkap = $request->name;

        if ($user->username == Null || $user->email == Null || $user->password == Null || $user->nama_lengkap == Null) {
            return [
                "message" => "harap mengisi semua field."
            ];
        } else {
            $user->save();
            $role = new UserRole();
            $role->id_user = $user->id;
            $role->code_role = $request->code_role;
            $role->save();
    
            return [$user, $role];
        }
    }

    public static function getById($request, $id){
        $search = Model::find($id);
        $role = UserRole::where('id_user', $id)->first();
        $detail_role = MstRole::where('code', $role->code_role)->first();
        if(!$search) {
            return [
                'message' => 'id tidak ditemukan'
            ];
        } else {
            return [$search, $role, $detail_role];
        }
    }

    public static function getAllUser($request) {
        return Model::all();
    }

    public static function updateById($request, $id){
            $update = Model::find($id);
            if(!$update) {
                return [
                    'message' => 'id tidak ditemukan'
                ];
            } 

            $update->username = $request->username;
            $update->nama_lengkap = $request->nama;
            $update->password = $request->password;
            $update->email = $request->email;
            $update->save();

            $role = UserRole::where('id_user', $id)->first();
            $role->code_role = $request->code_role;
            $role->save();

            return [
                'message' => 'user berhasil diupdate',
                'user' => $update,
                'role' => $role
            ];
    }

    public static function deleteById($request, $id){
        $delete = Model::find($id);
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

    public static function getAllRole($request) {
        return MstRole::all();
    
    }

    public static function addMstRole($request) {
        $role = new MstRole();
        $role->code = $request->code;
        $role->name = $request->name;
        if ($role->code == Null || $role->name == Null) {
            return [
                'message' => 'semua data harus terisi'
            ];
        }else {
            $role->save();
        }
        return $role;
    }

    public static function updateRole($request) {
        $update = MstRole::find($request->id);
        $update->code = $request->code;
        $update->name = $request->name;
        if ($update->code == Null || $update->name == Null) {
            return [
                'message' => 'semua data harus terisi'
            ];
        } else {
            $update->update();
            return $update;
        }

    }

    public static function deleteRole($request, $id) {
        $delete = MstRole::find($id);
        if(!$delete){
            return [
                'message' => 'id tidak ditemukan'
            ];
        } else {
            $delete->delete();
            return [
                'message' => 'role berhasil dihapus',
                'detail' => $delete
            ];
        }
    }

    public static function getRoleById($request, $id) {
        $search = MstRole::find($id);
        
        if(!$search) {
            return [
                'message' => 'id tidak ditemukan'
            ];
        } else {
            return $search;
        }
    }
}
