<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ParticipantController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Participant;
use App\Models\Genre;
use App\Models\Title;


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
        return Title::getTitle($request->type, $request->name);
    }

    public function getGenresMovies()
    {
        return Genre::getAllMoviesGenres();
    }

    public function getGenresSeries()
    {
        return Genre::getAllSeriesGenres();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->Title_validator();
        $title = Title::create([
            'title_name' => $request['titul'],
            'year' => $request['rok'],
            'state_id' => $request['zeme_puvodu'],
            'type' => $request['typ'],
            'price' => $request['cena'],
            'description' => $request['popis'],
            'url' => AppHelper::friendlyUrl($request['titul'])
        ]);
        $title->genres()->attach($request['zanr']);
        $participant_id = (new \App\Models\Participant)->by_name($request['herci']);
        $title->participant()->attach(array_keys($participant_id));
        foreach ($request['novy_herec'] as $herec){
            $participant = Participant::create([
                'name' => $herec['jmeno'],
                'surname' => $herec['prijmeni'],
                'birth' => $herec['datum_narozeni'],
            ]);
            $title->participant()->attach($participant['id']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function show(Title $title)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Title $title)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function destroy(Title $title)
    {
        //
    }

    protected function Title_validator(){
        return request()->validate([
            'titul' => 'required|string|max:255|unique:titles,title_name',
            'rok' => 'required|numeric|min:1800',
            'typ' => 'required|in:movie,serial',
            'cena' => 'required|numeric|min:1',
            'popis' => 'required|string|max:255',
            'zeme_puvodu' => 'required|exists:states,id',
            'zanr' => 'required|exists:genres,id',
            'novy_herec.*.jmeno' => 'required|string|max:255',
            'novy_herec.*.prijmeni' => 'required|string|max:255',
            'novy_herec.*.datum_narozeni' => 'required|date|before:today',
        ]);
    }
}
