<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstJenisIzin extends Model
{
    protected $primaryKey = 'id'; 

    protected $table = 'master.mst_jenis_izin';

    protected $fillable = [
        
    ];

    public $timestamps = true;

    use HasFactory;
}
