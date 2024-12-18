<?php

namespace App\Models\Backend;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForecastingResult extends Model
{
    protected $table = 'forecasting_results';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'raw_material_id',
        'date',
        'predicted_amount',
        'actual_usage',
        'error_rate',
        'forecasting_method'
    ];

    protected $casts = [
        'predicted_amount' => 'decimal:2',
        'actual_usage' => 'decimal:1',
        'error_rate' => 'decimal:2',
        'date' => 'date'
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

    public function getAccuracyLevelAttribute()
    {
        if ($this->error_rate <= 10) {
            return 'High';
        } elseif ($this->error_rate <= 20) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }
}
