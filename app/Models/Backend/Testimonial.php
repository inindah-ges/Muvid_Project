<?php

namespace App\Models\Backend;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'selling_id',
        'rate',
        'comment',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function selling(): BelongsTo
    {
        return $this->belongsTo(Selling::class, 'selling_id', 'id');
    }
}

