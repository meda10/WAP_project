<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Genre;

use Illuminate\Support\Facades\Log;



class TitlesController extends Controller
{
    public function genresMenu()
    {
        $genres = Genre::getGenresMenu();
        return ['movies' => $genres, 'series' => $genres];
    }

    public function filterMoviesByGenre(Request $request)
    {
        // TODO
        return null;
    }

    public function filterSeriesByGenre(Request $request)
    {
        // TODO
        return null;
    }

    public function getGenreInfoFromUrl(Request $request)
    {
        $genre = Genre::getGenreByUrl($request->url);
        if ($genre != null) {Log::info($genre); return $genre;}
        abort(404);
    }
}
