<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['language_id', 'store_id', 'title_id'];

    public static function getFreeItemsIds($url, $languageName, $storeId, $start, $end)
    {
        $start = new \DateTime($start);
        $end = new \DateTime($end);
        $end->setTime(0,0,1);

        return Item::select(['id'])
                        ->whereHas('titles', function($query) use($url) { $query->where('url', $url); })
                        ->whereHas('languages', function($query) use($languageName) { $query->where('language_name', $languageName); })
                        ->whereDoesntHave('reservations', function($query) use($start, $end) { 
                            $query->whereBetween('reservation', [$start, $end])
                            ->orWhereBetween('reservation_till', [$start, $end])
                            ->orWhere(function($q) use($start, $end) {
                                $q->where('reservation', '<', $start)->where('reservation_till', '>', $end);
                            });
                        })
                        ->where('store_id', $storeId)->get();
    }

    public static function getItemByTitleInfo($titleUrl, $titleStore, $itemId)
    {
        return Item::whereHas('titles', function($query) use($titleUrl) { $query->where('url', $titleUrl); })
                        ->whereHas('store_id', $storeId)
                        ->whereHas('id', $itemId)->get();
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
        return $this->belongsToMany(Reservation::class, 'reservation_item');
    }

    public function stores()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}
