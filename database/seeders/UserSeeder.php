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
                'name' => 'Fei Fei',
                'email' => 'feifeifry@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'pegawai',
            ],
            [
                'name' => 'Fey',
                'email' => 'feithemornstar@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Tumbal Proyek 1',
                'email' => 'anontester69@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'owner',
            ],
            [
                'name' => 'Tumbal Proyek 2',
                'email' => 'root.networkscience@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
            ],
        ]);

    }
}
