<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Manga',
            'Komik Barat',
            'Komik Eropa',
            'Light Novel',
            'Graphic Novel',
            'Webtoon',
            'Manhwa',
            'Manhua',
            'One Shot',
            'Series',
            'Volume Tunggal',
            'Edisi Spesial',
            'Terbaru',
            'Populer',
            'Dewasa',
            'Anak-anak',
            'Remaja',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
