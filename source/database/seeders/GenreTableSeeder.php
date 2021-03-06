<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genre::create(['genre_name' => 'Akční']);
        Genre::create(['genre_name' => 'Komedie']);
        Genre::create(['genre_name' => 'Romantický']);
        Genre::create(['genre_name' => 'Drama']);
        Genre::create(['genre_name' => 'Krimi']);
        Genre::create(['genre_name' => 'Thriller']);
        Genre::create(['genre_name' => 'Sci-fy']);
        Genre::create(['genre_name' => 'Fantasy']);
        Genre::create(['genre_name' => 'Dokumentární']);
        Genre::create(['genre_name' => 'Životopisný']);
        Genre::create(['genre_name' => 'Mysteriózní']);
        Genre::create(['genre_name' => 'Western']);
        Genre::create(['genre_name' => 'Dobrodružný']);
    }
}
