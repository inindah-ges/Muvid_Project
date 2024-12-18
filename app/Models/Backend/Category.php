<?php

namespace App\Models\Backend;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    protected $table = 'categories';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'slug',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->slug = Str::slug($model->name);
        });
    }

    // relasi one to many
    public function rawMaterial(): HasMany
    {
        return $this->hasMany(RawMaterial::class);
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}
