<?php

namespace App\Models\Backend;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chef extends Model
{
    protected $table = 'chefs';

    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'position',
        'photo',
        'insta_link',
        'fb_link',
        'linkedin_link'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
