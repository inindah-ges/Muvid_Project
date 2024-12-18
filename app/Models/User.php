<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use App\Models\Backend\Order;
use App\Models\Backend\Selling;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->role) {
            'admin' => 'danger',
            'pegawai' => 'info',
            'owner' => 'warning',
            'pelanggan' => 'success',
            default => 'secondary'
        };
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function sellings()
    {
        return $this->hasMany(Selling::class);
    }


    /**
     * Periksa apakah pengguna memiliki peran tertentu/spesifik role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Periksa apakah pengguna memiliki salah satu peran yang diberikan.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    /**
     * Periksa apakah pengguna adalah Admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Periksa apakah pengguna adalah Owner.
     *
     * @return bool
     */
    public function isOwner()
    {
        return $this->hasRole('owner');
    }

    /**
     * Periksa apakah pengguna adalah Pegawai.
     *
     * @return bool
     */
    public function isPegawai()
    {
        return $this->hasRole('pegawai');
    }

    /**
     * Periksa apakah pengguna adalah Pelanggan.
     *
     * @return bool
     */
    public function isPelanggan()
    {
        return $this->hasRole('pelanggan');
    }
}
