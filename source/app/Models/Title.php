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

    public static function filterTitles($type, $numberOfTitles, $genre, $pageNumber, $order)
    {
        Title::select(['title_name', 'description', 'year', 'url', 'type'])
        ->where('type', $type)->where('')->first();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'title_genre');
    }
}
