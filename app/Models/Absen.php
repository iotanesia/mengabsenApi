<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Absen extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'absen';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'user_id',
        'address',
        'long',
        'lat',
        'image',
        'type',
        'desc',
        'location_id',
        'check_in',
        'check_out',
        'address_out',
        'long_out',
        'lat_out',
        'image_out',
        'desc_out',
        'location_id_out',
    ];
    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    public function getIncrementing()
    {
        return false;
    }
    public function getKeyType()
    {
        return 'string';
    }
    public function refUser()
    {
        return $this->belongsTo(User::class,'id','user_id');
    }
    public function getImageAttribute()
    {
        if (is_null($this->attributes['image'])
            || str_starts_with($this->attributes['image'], 'http')) {
            return $this->attributes['image'];
        }
        return url(Storage::url($this->attributes['image']));
    }
}
