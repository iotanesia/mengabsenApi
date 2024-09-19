<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';
    // protected $timestamps = false;

    protected $fillable = [
        'id',
        'user_id',
        'from',
        'to',
        'status',
        'alasan',
        'lampiran',
        'updated_by',
        'filename',
        'jenis_izin',
        'alasan_tolak',
        'pic_status',
        'hrd_status'
    ];
}
