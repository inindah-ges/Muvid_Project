<?php

namespace App\Models\Backend;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterialUsage extends Model
{
    protected $table = 'raw_material_usages';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'raw_material_id',
        'quantity_used',
        'date',
    ];

    protected $casts = [
        'quantity_used' => 'integer',
        'date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
