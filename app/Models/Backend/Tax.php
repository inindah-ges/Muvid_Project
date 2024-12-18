<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    protected $table = 'taxes';

    use HasFactory;

    protected $fillable = [
        'name',
        'rate'
    ];

    public function sellingDetails()
    {
        return $this->hasMany(SellingDetail::class);
    }

    // Format rate to percentage
    public function getRatePercentageAttribute()
    {
        return $this->rate . '%';
    }
}
