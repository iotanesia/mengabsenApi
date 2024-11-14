<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'public.task';
    protected $fillable = [
        'user_id',
        'tujuan_id',
        'meeting_date',
        'meeting_start',
        'meeting_end',
        'guest_name',
        'company_name',
        'accompanied',
        'detail',
        'status',
        'path',
        'long',
        'lat',
        'desc',
        'visit_date',
        'hrd_status',
        'alasan_tolak'
    ];

    use HasFactory;
}
