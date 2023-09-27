<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'location';
    protected $fillable = [
        'id',
        'user_id',
        'address',
        'long',
        'lat',
        'type',
        'name'
    ];
}
