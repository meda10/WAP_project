<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Title;
use App\Models\Item;
use App\Models\Language;


class Reservation extends Model
{
    use HasFactory;

    const UPDATED_AT = NULL;

    protected $fillable = ['reservation', 'reservation_till', 'invoice_id', 
                            'price', 'returned', 'paid', 'issued', 'created_at', 
                            'user_id', 'item_id', 'fine', 'discount_id'];

    public static function getTitleReservations($titleUrl, $store_id)
    {
        $reservations = [];
        $reservationsDB = Reservation::whereHas('languages', function($query) use($store_id) { $query->where('store_id', $store_id); })
                                        ->whereHas('titles', function($query) use($titleUrl) {
                                                $query->where('url', $titleUrl);  
                                            })->get();

        $interval = new \DateInterval('P1D');

        foreach ($reservationsDB as $reservation) {
            $reservation->reservation;
            $reservation->reservation_till;

            $start = new \DateTime($reservation->reservation);

            $end = new \DateTime($reservation->reservation_till);
            $end->setTime(0,0,1);

            $daterange = new \DatePeriod($start, $interval, $end);

            foreach ($daterange as $date) {
                if (!isset($reservations[$reservation->languages->language_name]))
                    $reservations[$reservation->languages->language_name] = [];

                if (!isset($reservations[$reservation->languages->language_name][$date->format('Y-m-d')]))
                    $reservations[$reservation->languages->language_name][$date->format('Y-m-d')] = 1;
                else
                    $reservations[$reservation->languages->language_name][$date->format('Y-m-d')] += 1;
            }
        }

        return $reservations;
    }

    public static function getUserReservations($user_id)
    {
        return Reservation::where('user_id', $user_id)
                                ->with('titles')->with('languages')
                                ->with('stores')->with('discounts')->orderBy('reservation')->get();
    }

    public function items()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function titles()
    {
        return $this->hasOneThrough(Title::class, Item::class, 'id', 'id', 'item_id', 'title_id');
    }

    public function languages()
    {
        return $this->hasOneThrough(Language::class, Item::class, 'id', 'id', 'item_id', 'language_id');
    }

    public function discounts()
    {
        return $this->hasOne(Discount::class, 'id');
    }
    
    public function stores()
    {
        return $this->hasOneThrough(Store::class, Item::class, 'id', 'id', 'item_id', 'store_id');
    }
}
