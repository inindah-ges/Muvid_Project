<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bisa menggunakan model insert atau model factories createMany

        User::factory()->createMany([
            [
                'name' => 'pegawai',
                'email' => 'pegawai@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'owner',
                'email' => 'owner@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'owner',
            ],
            [
                'name' => 'pelanggan',
                'email' => 'pelanggan@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
            ],
        ]);

    }
}
