<?php

namespace App\Models\Backend;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterialStock extends Model
{
    protected $table = 'raw_material_stocks';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'raw_material_id',
        'user_id',
        'quantity',
        'type', // in, out
        'notes',
        'date'
    ];

    protected $casts = [
        'quantity' => 'decimal:1',
        'date' => 'datetime'
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

    public function getTypeBadgeAttribute()
    {
        return $this->type === 'in' ? 'success' : 'danger';
    }
}
