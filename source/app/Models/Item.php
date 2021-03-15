<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function getFreeItemsIds($url, $languageName, $storeId)
    {
        return Item::select(['id'])
                        ->whereHas('titles', function($query) use($url) { $query->where('url', $url); })
                        ->whereHas('languages', function($query) use($languageName) { $query->where('language_name', $languageName); })
                        ->whereDoesntHave('reservations')
                        ->where('store_id', $storeId)->get();
    }

    public function titles()
    {
        return $this->hasOne(Title::class, 'id', 'title_id');
    }

    public function languages()
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    public function reservations()
    {
        return $this->hasOne(Reservation::class, 'item_id', 'id');
    }
}
