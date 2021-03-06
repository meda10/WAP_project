<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Genre;
use App\Models\Title;


class TitlesController extends Controller
{
    public function genresMenu()
    {
        $genres = Genre::getGenresMenu();
        return ['movies' => $genres, 'series' => $genres];
    }

    public function getGenreInfoFromUrl(Request $request)
    {
        $genre = Genre::getGenreByUrl($request->url);
        if ($genre != null) return $genre;
        abort(404);
    }

    public function getTitles(Request $request)
    {
        return Title::filterTitles($request->type, $request->genre_url, $request->number_of_titles, $request->page_number, $request->order);
    }
}
