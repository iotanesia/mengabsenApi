<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class BannerDashboard extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'banner_dashboard';
    protected $keyType = 'string';
    public function getImageAttribute()
    {
        if (is_null($this->attributes['image'])
            || str_starts_with($this->attributes['image'], 'http')) {
            return $this->attributes['image'];
        }
        return url(Storage::url($this->attributes['image']));
    }
}
