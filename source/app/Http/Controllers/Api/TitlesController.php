<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;


class TitlesController extends Controller
{
    public function genresMenu()
    {
        // TODO when there will be titles administration change this to something better
        //$genres = Genre::select(['genre_name'])->orderBy('genre_name', 'ASC')->take(5)->pluck('genre_name')->toArray();
        $genres = Genre::select(['genre_name AS name'])->orderBy('genre_name', 'ASC')->take(5)->get();
        return ['movies' => $genres, 'series' => $genres];
    }
}
