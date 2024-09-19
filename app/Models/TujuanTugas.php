<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TujuanTugas extends Model
{

    protected $table = 'public.tujuan_tugas';
    protected $fillable = [];

    public $timestamps = false;
    use HasFactory;

}
