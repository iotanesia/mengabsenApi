<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlobalParam extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'global_param';
    protected $fillable = [
        'param_type',
        'param_code',
        'param_name',
        'desc'
    ];
    public static function getJamMasuk() {
        return GlobalParam::select('param_code','param_name')->where('param_type','ABSENSI')->where('param_code','JAM_MASUK')->first();
    }
}
