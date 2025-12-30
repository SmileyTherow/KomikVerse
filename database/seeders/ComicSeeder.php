<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Comic;
use App\Models\Category;
use App\Models\Genre;

class ComicSeeder extends Seeder
{
    public function run(): void
    {
        // Helper to get genre ids by names (ignores missing)
        $getGenreIds = function (array $names) {
            return Genre::whereIn('name', $names)->pluck('id')->all();
        };

        // Ensure categories exist
        $catManga = Category::firstOrCreate(['name' => 'Manga'], ['slug' => 'manga']);
        $catKomikBarat = Category::firstOrCreate(['name' => 'Komik Barat'], ['slug' => 'komik-barat']);
        $catGraphic = Category::firstOrCreate(['name' => 'Graphic Novel'], ['slug' => 'graphic-novel']);

        $items = [
            // KOMIK (Non-Manga) – 5 Data
            [
                'title' => 'Batman: The Killing Joke',
                'publisher' => 'DC Comics',
                'author' => 'Alan Moore',
                'synopsis' => 'Kisah kelam hubungan Batman dan Joker yang menguji batas kewarasan dan moral. Jumlah halaman: 64.',
                'genres' => ['Action','Crime','Psychological'],
                'year' => 1988,
                'isbn' => '978-1-4012-2072-5',
                'category' => $catKomikBarat->id,
                'stock' => 3,
            ],
            [
                'title' => 'Spider-Man: Blue',
                'publisher' => 'Marvel Comics',
                'author' => 'Jeph Loeb',
                'synopsis' => 'Cerita emosional Peter Parker mengenang cinta pertamanya, Gwen Stacy. Jumlah halaman: 144.',
                'genres' => ['Romance','Superhero','Drama'],
                'year' => 2002,
                'isbn' => '978-0-7851-0869-9',
                'category' => $catKomikBarat->id,
                'stock' => 4,
            ],
            [
                'title' => 'Watchmen',
                'publisher' => 'DC Comics',
                'author' => 'Alan Moore',
                'synopsis' => 'Dunia alternatif di mana pahlawan super terlibat konspirasi politik besar. Jumlah halaman: 416.',
                'genres' => ['Superhero','Thriller'],
                'year' => 1987,
                'isbn' => '978-1-4012-7926-6',
                'category' => $catGraphic->id,
                'stock' => 2,
            ],
            [
                'title' => 'Tintin: The Blue Lotus',
                'publisher' => 'Casterman',
                'author' => 'Hergé',
                'synopsis' => 'Petualangan Tintin mengungkap kejahatan internasional di Tiongkok. Jumlah halaman: 62.',
                'genres' => ['Adventure','Mystery'],
                'year' => 1936,
                'isbn' => '978-0-316-35026-7',
                'category' => $catKomikBarat->id,
                'stock' => 5,
            ],
            [
                'title' => 'Asterix and the Gauls',
                'publisher' => 'Dargaud',
                'author' => 'René Goscinny',
                'synopsis' => 'Desa kecil Galia melawan Kekaisaran Romawi dengan ramuan ajaib. Jumlah halaman: 48.',
                'genres' => ['Comedy','Adventure'],
                'year' => 1961,
                'isbn' => '978-2-86497-016-8',
                'category' => $catKomikBarat->id,
                'stock' => 4,
            ],

            // MANGA – 5 Data
            [
                'title' => 'One Piece Vol. 101',
                'publisher' => 'Shueisha',
                'author' => 'Eiichiro Oda',
                'synopsis' => 'Pertempuran besar di Wano melawan Kaido mencapai puncaknya. Jumlah halaman: 208.',
                'genres' => ['Action','Adventure','Fantasy'],
                'year' => 2022,
                'isbn' => '978-4-08-882756-1',
                'category' => $catManga->id,
                'stock' => 10,
            ],
            [
                'title' => 'Naruto Vol. 72',
                'publisher' => 'Shueisha',
                'author' => 'Masashi Kishimoto',
                'synopsis' => 'Naruto menghadapi pertarungan terakhir demi perdamaian dunia ninja. Jumlah halaman: 216.',
                'genres' => ['Action','Ninja','Adventure'],
                'year' => 2015,
                'isbn' => '978-4-08-880789-1',
                'category' => $catManga->id,
                'stock' => 8,
            ],
            [
                'title' => 'Attack on Titan Vol. 34',
                'publisher' => 'Kodansha',
                'author' => 'Hajime Isayama',
                'synopsis' => 'Akhir dari konflik manusia dan titan yang penuh tragedi. Jumlah halaman: 192.',
                'genres' => ['Action','Drama','Dark Fantasy'],
                'year' => 2021,
                'isbn' => '978-4-06-523416-8',
                'category' => $catManga->id,
                'stock' => 7,
            ],
            [
                'title' => 'Demon Slayer Vol. 23',
                'publisher' => 'Shueisha',
                'author' => 'Koyoharu Gotouge',
                'synopsis' => 'Pertarungan terakhir melawan Muzan demi menyelamatkan umat manusia. Jumlah halaman: 224.',
                'genres' => ['Action','Supernatural'],
                'year' => 2020,
                'isbn' => '978-4-08-882495-9',
                'category' => $catManga->id,
                'stock' => 6,
            ],
            [
                'title' => 'Dragon Ball Vol. 42',
                'publisher' => 'Shueisha',
                'author' => 'Akira Toriyama',
                'synopsis' => 'Goku dan kawan-kawan menghadapi Majin Buu di pertarungan final. Jumlah halaman: 240.',
                'genres' => ['Action','Fantasy'],
                'year' => 1995,
                'isbn' => '978-4-08-851495-9',
                'category' => $catManga->id,
                'stock' => 9,
            ],
        ];

        foreach ($items as $data) {
            $slug = Str::slug($data['title']) . '-' . Str::random(5);

            $comic = Comic::firstOrCreate(
                ['title' => $data['title']],
                [
                    'slug' => $slug,
                    'author' => $data['author'] ?? null,
                    'publisher' => $data['publisher'] ?? null,
                    'year' => $data['year'] ?? null,
                    'isbn' => $data['isbn'] ?? null,
                    'description' => $data['synopsis'] ?? null,
                    'stock' => $data['stock'] ?? 1,
                    'status' => ($data['stock'] ?? 0) > 0 ? 'available' : 'out_of_stock',
                    'category_id' => $data['category'] ?? null,
                ]
            );

            // attach genres
            if (!empty($data['genres'])) {
                $genreIds = $getGenreIds($data['genres']);
                if (!empty($genreIds)) {
                    $comic->genres()->sync($genreIds);
                }
            }
        }
    }
}
