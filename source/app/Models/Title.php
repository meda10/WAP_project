<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AppHelper;

use App\Models\Genre;
use App\Models\State;

class Title extends Model
{
    use HasFactory;
    
    const UPDATED_AT = NULL;

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

    public static function getTitle($type, $name)
    {
        return Title::where('type', 'like', "{$type}%")
                        ->where('url', $name)
                        ->with('states')
                        ->first();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'title_genre');
    }

    public static function getAllCount()
    {
        return Title::get()->count();
    }

    public function states()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
}
