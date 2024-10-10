<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{

    protected $primaryKey = 'id'; 

    protected $table = 'master.mst_menu';

    protected $fillable = [
        
    ];

    public $timestamps = true;

    use HasFactory;
}
