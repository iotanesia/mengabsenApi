<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'auth.user_roles';
    public $timestamps = false;
    protected $fillable = [
        'id_user',
        'code_role',
    ];

    public function refRole()
    {
        return $this->belongsTo(MstRole::class,'code_role','code');
    }
}
