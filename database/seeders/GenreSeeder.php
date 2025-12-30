<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run(): void
    {
        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Romance',
            'Thriller',
            'Horror',
            'Mystery',
            'Superhero',
            'Psychological',
            'Crime',
            'Sci-Fi',
            'Slice of Life',
            'Supernatural',
            'Dark Fantasy',
            'Historical',
            'Sports',
            'Mecha',
            'School',
            'Ninja',
        ];

        foreach ($genres as $name) {
            Genre::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
