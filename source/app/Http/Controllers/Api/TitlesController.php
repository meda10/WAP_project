<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Genre;
use App\Models\Title;
use App\Models\Reservation;


class TitlesController extends Controller
{
    public function genresMenu()
    {
        return ['movies' => Genre::getGenresMoviesMenu(), 'series' => Genre::getGenresSeriesMenu()];
    }

    public function getGenreInfoFromUrl(Request $request)
    {
        $genre = Genre::getGenreByUrl($request->url);
        if ($genre != null) return $genre;
        abort(404);
    }

    public function getAllTitlesSearch()
    {
        return Title::getAllTitles();
    }

    public function getTitles(Request $request)
    {
        return Title::filterTitles($request->type, $request->genre_url, $request->number_of_titles, $request->page_number, $request->order);
    }

    public function getTitle(Request $request)
    {
        $title = Title::getTitle($request->type, $request->name, $request->store_id);
        $title['reservations'] = Reservation::getTitleReservations($request->name, $request->store_id);
        return $title;
    }

    public function getGenresMovies()
    {
        return Genre::getAllMoviesGenres();
    }

    public function getGenresSeries()
    {
        return Genre::getAllSeriesGenres();
    }
}
