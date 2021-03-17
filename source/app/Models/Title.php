<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\AppHelper;

use App\Models\Genre;
use App\Models\State;
use App\Models\Language;
use App\Models\Item;
use App\Models\Reservation;
use App\Models\Store;


class Title extends Model
{
    use HasFactory;

    const UPDATED_AT = NULL;

    protected $fillable = [ 'title_name', 'year', 'state_id', 'type', 'price', 'description', 'url', 'created_at'];

    public static function create(array $attributes = [])
    {
        $attributes['url'] = AppHelper::friendlyUrl($attributes['title_name']);
        return static::query()->create($attributes);
    }

    public static function filterTitles($type, $genre_url, $numberOfTitles, $pageNumber, $order)
    {
        try {
            $filteredTitles = Title::select(['title_name', 'description', 'url', 'type', 'year'])
                            ->whereHas('genres', function($query) use($genre_url) { $query->where('url', 'like', "{$genre_url}%"); })
                            ->where('type', 'like', "{$type}%")
                            ->orderBy('created_at', $order);

            $response['titles_count'] = $filteredTitles->get()->count();
            $response['titles'] = $filteredTitles->skip($numberOfTitles * ($pageNumber - 1))
                                    ->take($numberOfTitles)
                                    ->get();
            return $response;

        } catch (\InvalidArgumentException $e) {
            return abort(400, 'Bad use of API');
        }
    }

    public static function getAllTitles()
    {
        return Title::select(['title_name', 'url', 'type AS typeUrl'])->get();
    }

    public static function getTitle($type, $name, $store_id)
    {
        return Title::where('type', 'like', "{$type}%")
                        ->where('url', $name)
                        ->with('states')
                        ->with('genres')
                        ->with('languages', function($query) use($store_id) { $query->where('items.store_id', $store_id); })
                        ->first();
    }


    public static function getTitleItemsMaxCounts($type, $name, $store_id)
    {
        $title = Title::getTitle($type, $name, $store_id);
        $counts = [];

        foreach ($title->languages as $languageMaxCount)
            $counts[$languageMaxCount['language_name']] = $languageMaxCount['total'];

        return $counts;
    }

    public static function get_title_edit_by_id($id){
        return Title::where('id', $id)
                        ->with('genres')
                        ->with('languages')
                        ->with('participant')
                        ->get();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'title_genre');
    }

    public function stores()
    {
        return $this->hasOneThrough(Store::class, Item::class, 'title_id', 'id', 'id', 'store_id');
    }

    public static function getAllCount()
    {
        return Title::get()->count();
    }

    public function participant()
    {
        return $this->belongsToMany(Participant::class, 'participant_title');
    }

    public function states()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, Item::class, 'title_id', 'language_id')
                    ->select(['languages.language', 'languages.language_name', DB::raw('count(*) as total')])
                    ->groupBy('language_id');
    }
}
