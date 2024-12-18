<?php

namespace Database\Seeders;

use App\Models\Backend\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tax::insert([
            'name' => 'PPN',
            'rate' => 10
        ]);
    }
}
