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
                            'user_id', 'fine', 'discount_id', 'title_id'];

    public static function getTitleReservations($titleUrl, $store_id)
    {
        $reservations = [];
        
        $reservationsDB = Reservation::whereHas('titles', function($query) use($titleUrl) {
                                $query->where('url', $titleUrl);
                            })->with('items')
                            ->get();
    
        $interval = new \DateInterval('P1D');

        foreach ($reservationsDB as $reservation) {
            $itemsCount = count($reservation->items);
            
            $reservation->reservation;
            $reservation->reservation_till;
            $language_name = Language::getLanguageName($reservation->items[0]->language_id)->language_name;

            $start = new \DateTime($reservation->reservation);

            $end = new \DateTime($reservation->reservation_till);
            $end->setTime(0,0,1);

            $daterange = new \DatePeriod($start, $interval, $end);

            foreach ($daterange as $date) {
                if (!isset($reservations[$language_name]))
                    $reservations[$language_name] = [];

                if (!isset($reservations[$language_name][$date->format('Y-m-d')]))
                    $reservations[$language_name][$date->format('Y-m-d')] = $itemsCount;
                else
                    $reservations[$language_name][$date->format('Y-m-d')] += $itemsCount;
            }
        }

        return $reservations;
    }

    public static function getUserReservations($userEmail)
    {
        return Reservation::whereHas('users', function($query) use($userEmail) {
                                    $query->where('email', $userEmail);
                                })
                                ->with('items', 'items.stores', 'items.languages')
                                ->with('discounts')->with('titles')->orderBy('reservation')->get();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'reservation_item');
    }

    public function titles()
    {
        return $this->hasOne(Title::class, 'id', 'title_id');
    }

    public function discounts()
    {
        return $this->hasOne(Discount::class, 'id', 'discount_id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
