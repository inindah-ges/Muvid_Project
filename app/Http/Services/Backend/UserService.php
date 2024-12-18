<?php

namespace App\Http\Services\Backend;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function getUsers($search = null)
    {
        return User::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10);
    }

    public function getUserByUuid($uuid)
    {
        return User::where('uuid', $uuid)->firstOrFail();
    }

    public function createUser(array $data)
    {
        return User::create([
            'uuid' => Str::uuid(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }

    public function updateUser($uuid, array $data)
    {
        $user = $this->getUserByUuid($uuid);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        return $user;
    }

    public function changePassword($uuid, string $password)
    {
        $user = $this->getUserByUuid($uuid);

        $user->update([
            'password' => Hash::make($password),
        ]);

        return $user;
    }

    public function changeRole($uuid, string $role)
    {
        $user = $this->getUserByUuid($uuid);

        $user->update([
            'role' => $role,
        ]);

        return $user;
    }

    public function deleteUser($uuid)
    {
        $user = $this->getUserByUuid($uuid);
        return $user->delete();
    }

    public function getUserRoles()
    {
        return [
            'admin' => 'Admin',
            'pegawai' => 'Pegawai',
            'owner' => 'Owner',
            'pelanggan' => 'Pelanggan',
        ];
    }

    public function getUserStats()
    {
        return [
            'total_users' => User::count(),
            'total_admin' => User::where('role', 'admin')->count(),
            'total_pegawai' => User::where('role', 'pegawai')->count(),
            'total_pelanggan' => User::where('role', 'pelanggan')->count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];
    }
}
