<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TitleUpdateResource;
use App\Models\Item;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Participant;
use App\Models\Genre;
use App\Models\Title;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;
use const http\Client\Curl\AUTH_ANY;

use Illuminate\Support\Facades\Log;


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
        return response()->json(['ok'=> 'error', 'message' => ''], 404); //todo what is it for?
    }

    public function getAllTitlesSearch($storeId)
    {
        return Title::getAllTitles($storeId);
    }

    public function getTitles(Request $request)
    {
        return Title::filterTitles($request->type, $request->genre_url, $request->number_of_titles, $request->page_number, $request->order, $request->store_id);
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
            try {
                if($value['reziser'][0] == 'director'){
                    if(!in_array($value['herec'], $actor_id_arr, true)){
                        $title->participant()->attach($value['herec'], ['director' => 1]);
                    }
                }
            } catch (\Exception $e){
                if(!in_array($value['herec'], $actor_id_arr, true)){
                    $title->participant()->attach($value['herec']);
                }
            }
            array_push($actor_id_arr, $value['herec']);
        }

        foreach ($request['novy_herec'] as $herec){
            $participant = Participant::create([
                'name' => $herec['jmeno'],
                'surname' => $herec['prijmeni'],
                'birth' => $herec['datum_narozeni'],
            ]);
            try {
                if($herec['reziser'][0] == 'director'){
                    $title->participant()->attach($participant['id'], ['director' => 1]);
                }
            } catch (\Exception $e){
                $title->participant()->attach($participant['id']);
            }
        }
        foreach ($request['polozka'] as $item){
            for ($i = 0; $i < $item['pocet']; $i++){
                Item::create([
                    'language_id' => $item['jazyk'],
                    'store_id' => $item['prodejna'],
                    'title_id' => $title->id,
                ]);
            }
        }
        foreach ($request['obrazek'] as $obrazek){
            try {
                Storage::delete("/public/img/".$url.".jpg");
            }catch (\Exception $e){}
            Storage::move("/public/".$obrazek['url'], "/public/img/".$url.".jpg");
        }
        return response()->json(['url'=> $url], 200);
    }


    public function show($url)
    {
        return new TitleUpdateResource(Title::get_title_edit_by_url($url));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $url)
    {
        $title = Title::get_title_by_url($url);
        $this->title_update_validator();
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

        $title->participant()->detach();
        $actor_id_arr = [];
        foreach ($request['herci'] as $value) {
            try {
                if($value['reziser'][1] == '1' && $value['reziser'][0] == '0'){
                    if(!in_array($value['herec'], $actor_id_arr, true)){
                        $title->participant()->attach($value['herec'], ['director' => 1]);
                    }
                }
            } catch (\Exception $e){
                try {
                    if($value['reziser'][0] == '1'){
                        if(!in_array($value['herec'], $actor_id_arr, true)){
                            $title->participant()->attach($value['herec'], ['director' => 1]);
                        }
                    }
                }catch (\Exception $e){
                    if(!in_array($value['herec'], $actor_id_arr, true)){
                        $title->participant()->attach($value['herec']);
                    }
                }
            }
            array_push($actor_id_arr, $value['herec']);
        }

        foreach ($request['novy_herec'] as $herec){
            $participant = Participant::create([
                'name' => $herec['jmeno'],
                'surname' => $herec['prijmeni'],
                'birth' => $herec['datum_narozeni'],
            ]);
            try {
                if($herec['reziser'][0] == '1'){
                    $title->participant()->attach($participant['id'], ['director' => 1]);
                }
            } catch (\Exception $e){
                $title->participant()->attach($participant['id']);
            }
        }
        $title->save();

        $title->items()->delete();
        foreach ($request['polozka'] as $item){
            for ($i = 0; $i < $item['pocet']; $i++){
                Item::create([
                    'language_id' => $item['jazyk'],
                    'store_id' => $item['prodejna'],
                    'title_id' => $title->id,
                ]);
            }
        }

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
     */
    public function destroy($id)
    {
        $title = Title::findOrFail($id);

        $reservations = Reservation::hasReservations($title->id);

        if($reservations != null){
            return response()->json(['ok'=> 'error', 'message' => 'Titul nelze smazat protože existují rezervace'], 403);
        }

        $title->genres()->detach();
        $title->participant()->detach();
        $title->items()->delete();
        try {
            Storage::delete("/public/img/".$title->url.".jpg");
        }catch (Exception $e){}
        $title->delete();

        return response()->json(['ok'=> 'ok', 'message' => 'ok'], 200);
    }

    protected function Title_validator(){
        return request()->validate([
            'titul' => 'required|string|max:255|unique:titles,title_name', //
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

    protected function title_update_validator(){
        return request()->validate([
            'titul' => 'required|string|max:255',
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
