<?php

namespace App\Models\Backend;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Selling extends Model
{
    protected $table = 'sellings';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'date',
        'total_price',
        'invoice'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'date' => 'date'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->invoice = self::generateInvoiceNumber();
        });
    }

    private static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastInvoice = self::whereDate('created_at', now())->latest()->first();
        $sequence = $lastInvoice ? (int)substr($lastInvoice->invoice, -4) + 1 : 1;

        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sellingDetails()
    {
        return $this->hasMany(SellingDetail::class);
    }
}
