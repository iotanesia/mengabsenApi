<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimburs extends Model
{
    protected $table = 'reimburs';
    
    protected $fillable = [
        'user_id',
        'jenis_id',
        'tujuan_id',
        'status',
        'no_pengembalian',
        'tgl_pemakaian',
        'bank',
        'bukti_path',
        'bukti_file',
        'biaya',
        'alasan_tolak'
    ];

    use HasFactory;
}
