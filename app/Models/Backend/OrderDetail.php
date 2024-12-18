<?php

namespace App\Models\Backend;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'order_id',
        'product_id',
        'tax_id',
        'discount',
        'quantity',
        'price',
        'subtotal'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    // Helper untuk mendapatkan total setelah tax dan discount
    public function getTotalAttribute()
    {
        $afterDiscount = $this->subtotal - ($this->discount ?? 0);
        if ($this->tax) {
            return $afterDiscount + ($afterDiscount * $this->tax->rate / 100);
        }
        return $afterDiscount;
    }
}
