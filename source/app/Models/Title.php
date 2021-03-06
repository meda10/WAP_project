<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AppHelper;

use App\Models\Genre;

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
            return Title::select(['title_name', 'description', 'url', 'type', 'year'])
                            ->whereHas('genres', function($query) use($genre_url) { $query->where('url', 'like', "{$genre_url}%"); })
                            ->where('type', 'like', "{$type}%")
                            ->skip($numberOfTitles * ($pageNumber - 1))
                            ->take($numberOfTitles)
                            ->orderBy('created_at', $order)->get();
        } catch (\InvalidArgumentException $e) {
            return abort(400, 'Bad use of API');
        }
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'title_genre');
    }
}
