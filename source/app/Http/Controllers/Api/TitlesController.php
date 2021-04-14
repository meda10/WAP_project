<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitleUpdateResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Participant;
use App\Models\Genre;
use App\Models\Title;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;


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
        $url = AppHelper::friendlyUrl($request['titul']);
        $title = Title::create([
            'title_name' => $request['titul'],
            'year' => $request['rok'],
            'state_id' => $request['zeme_puvodu'],
            'type' => $request['typ'],
            'price' => $request['cena'],
            'description' => $request['popis'],
            'url' => $url
        ]);
        $title->genres()->attach($request['zanr']);

        $actor_id_arr = [];
        foreach ($request['herci'] as $value) {
            array_push($actor_id_arr, $value['herec']);
        }
        $title->participant()->attach($actor_id_arr);

        foreach ($request['novy_herec'] as $herec){
            $participant = Participant::create([
                'name' => $herec['jmeno'],
                'surname' => $herec['prijmeni'],
                'birth' => $herec['datum_narozeni'],
            ]);
            $title->participant()->attach($participant['id']);
        }
        foreach ($request['obrazek'] as $obrazek){
            Storage::move("/public/".$obrazek['url'], "/public/img/".$url.".jpg");
        }
        return response()->json(['url'=> $url], 200);
    }


    public function show($url)
    {
//        return Title::get_by_id($request['id']);
//        return Title::get_title_edit_by_id($request['id']);
//        return new TitleUpdateResource(Title::get_title_edit_by_id($id));
//        return TitleUpdateResource::collection(Title::get_title_edit_by_id($id));
        return TitleUpdateResource::collection(Title::get_title_edit_by_url($url));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $url)
    {
//        $title = Title::where('url', $url)->first();
        $title = Title::get_title_by_url($url);
        $this->Title_validator();
        $new_url = AppHelper::friendlyUrl($request['titul']);
        $title->update([
            'title_name' => $request['titul'],
            'year' => $request['rok'],

            'state_id' => $request['zeme_puvodu'],
            'type' => $request['typ'],
            'price' => $request['cena'],
            'description' => $request['popis'],
            'url' => $new_url
        ]);
        $title->genres()->sync($request['zanr']);

        $actor_id_arr = [];
        foreach ($request['herci'] as $value) {
            array_push($actor_id_arr, $value['herec']);
        }
        $title->participant()->sync(array_values($actor_id_arr));

        foreach ($request['novy_herec'] as $herec){
            $participant = Participant::create([
                'name' => $herec['jmeno'],
                'surname' => $herec['prijmeni'],
                'birth' => $herec['datum_narozeni'],
            ]);
            $title->participant()->attach($participant['id']);
        }
        $title->save();

        $pattern = '#^img\/.*\.jpg$#';;
        foreach ($request['obrazek'] as $obrazek){
            if(preg_match($pattern, $obrazek['url'])){
                Storage::delete("/public/img/".$new_url.".jpg");
                Storage::move("/public/".$obrazek['url'], "/public/img/".$new_url.".jpg");
            }else if ($url != $new_url){
                Storage::move("/public/img/".$url.".jpg", "/public/img/".$new_url.".jpg");
            }
        }
        return response()->json(['url'=> $new_url], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $title = Title::findOrFail($id);
        $title->genres()->detach();
        $title->participant()->detach();
        $title->delete();

    }

    protected function Title_validator(){
        return request()->validate([
            'titul' => 'required|string|max:255', //|unique:titles,title_name
            'rok' => 'required|numeric|min:1899',
            'typ' => 'required|in:movie,serial',
            'cena' => 'required|numeric|min:1',
            'popis' => 'required|string|max:255',
            'zeme_puvodu' => 'required|exists:states,id',
            'zanr' => 'required|exists:genres,id',
            'novy_herec.*.jmeno' => 'required|string|max:255',
            'novy_herec.*.prijmeni' => 'required|string|max:255',
            'novy_herec.*.datum_narozeni' => 'required|date|before:today',
            'obrazek.*.url' => 'required',
        ]);
    }

    public function upload_image(Request $request){
        $path = $request->file('obrazek')->storePublicly('img', 'public');
        $name = $request->file('obrazek')->getClientOriginalName();
        return response()->json(['url' => $path, 'name' => $name], 200);
    }
}
