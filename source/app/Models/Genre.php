<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AppHelper;

use App\Models\Title;

class Genre extends Model
{
    use HasFactory;

    public $timestamps = false;


    public static function create(array $attributes = [])
    {
        $attributes['url'] = AppHelper::friendlyUrl($attributes['genre_name']);
        return static::query()->create($attributes);
    }

    public static function getGenresMenu($type)
    {
        return Genre::select(['genre_name AS name', 'url'])
                        ->withCount(['titles AS total' => function($query) use($type) {
                                return $query->where('type', $type)->groupBy('type');
                            }])
                        ->orderBy('total', 'DESC')
                        ->take(5)
                        ->get();
    }

    public static function getGenresMoviesMenu() 
    {
        return Genre::getGenresMenu('movie');
    }

    public static function getGenresSeriesMenu() 
    {
        return Genre::getGenresMenu('serial');
    }

    public static function getGenreByUrl($genreUrl) 
    {
        return Genre::select(['genre_name AS name'])->where('url', $genreUrl)->first();
    }

    public static function getGenreIdByUrl($genreUrl)
    {
        return Genre::select(['id'])->where('url', $genreUrl)->first()->id;
    }

    public function titles()
    {
        return $this->belongsToMany(Title::class, 'title_genre');
    }

    public static function getAllGenres($type)
    {
        return Genre::select(['genre_name AS name', 'url'])
                        ->whereHas('titles', function($query) use($type) { $query->where('type', $type); })
                        ->orderBy('name', 'asc')
                        ->get();
    }

    public static function getAllMoviesGenres()
    {
        return Genre::getAllGenres('movie');
    }

    public static function getAllSeriesGenres()
    {
        return Genre::getAllGenres('serial');
    }
}
