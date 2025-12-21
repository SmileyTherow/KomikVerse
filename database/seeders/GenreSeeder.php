<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;
use Illuminate\Support\Str;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $items = ['Action','Romance','Comedy','Horror','Fantasy'];
        foreach ($items as $name) {
            Genre::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}