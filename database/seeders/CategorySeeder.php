<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Backend\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Bahan Baku Nabati',
                'slug' => Str::slug('bahan-baku-nabati'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Bahan Baku Hewani',
                'slug' => Str::slug('bahan-baku-hewani'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Bazar',
                'slug' => Str::slug('bazar'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Live Musik',
                'slug' => Str::slug('live-musik'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Nonton Bareng',
                'slug' => Str::slug('nonton-bareng'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Game Night',
                'slug' => Str::slug('game-night'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Coffee',
                'slug' => Str::slug('coffee'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Mocktail',
                'slug' => Str::slug('mocktail'),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Food',
                'slug' => Str::slug('food'),
            ],
        ]);
    }
}
