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
}
