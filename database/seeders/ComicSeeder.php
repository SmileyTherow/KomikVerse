<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comic;
use App\Models\Category;
use App\Models\Genre;

class ComicSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::first();
        $genres = Genre::limit(2)->pluck('id')->toArray();

        Comic::create([
            'title' => 'Contoh Manga 1',
            'category_id' => $category->id,
            'description' => 'Deskripsi singkat Contoh Manga 1.',
            'stock' => 5,
        ])->genres()->attach($genres);

        Comic::create([
            'title' => 'Contoh Komik Barat 1',
            'category_id' => Category::where('name','Komik Barat')->first()->id,
            'description' => 'Deskripsi singkat Contoh Komik Barat 1.',
            'stock' => 2,
        ])->genres()->attach(Genre::where('name','Action')->pluck('id')->toArray());
    }
}