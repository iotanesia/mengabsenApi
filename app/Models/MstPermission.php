<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstPermission extends Model
{
    protected $primaruKey = 'id';
    protected $table = 'master.mst_permissions';
    protected $fillable = [];
    public $timestamps = true;
    use HasFactory;

}
