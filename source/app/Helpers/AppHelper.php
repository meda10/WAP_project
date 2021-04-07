<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Models\Discount;
use App\Models\Reservation;
use App\Http\Resources\ReservationWithUserResource;
use App\Mail\SendReservationCanceled;
use App\Mail\SendReservationFined;

use Illuminate\Support\Facades\Log;


class AppHelper
{
    public static function friendlyUrl($name) {
        $url = $name;
        
        $accentedChars = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'č' => 'c', 'Č' => 'c', 'ů' => 'u', 'ě' => 'e', 'Ů' => 'u', 
                            'Ě' => 'e', 'ř' => 'r', 'Ř' => 'r'];

        foreach ($accentedChars as $key => $value)
            $url = str_replace($key, $value, $url);
        
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        return $url;
    }

    public static function generateDiscountCode()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        do {
            $code = '';
            for ($i = 0; $i < 10; $i++) {
                $type = rand(0, 1);
                
                if ($type) {
                    $nextChar = strval(rand(0, 9));
                } else {
                    $nextChar = $chars[rand(0, strlen($chars) - 1)];
                }

                $code .= $nextChar;
            }

            $generate = Discount::checkCodeNotExist($code);
        } while ($generate);

        return $code;
    }

    public static function checkUsersReservations()
    {
        $reservations = Reservation::where('reservation', '=', date("Y-m-d"))
                                    ->where('issued', 0)
                                    ->with('items', 'items.stores', 'items.languages')
                                    ->with('titles')
                                    ->with('users')
                                    ->orderBy('user_id', 'asc')
                                    ->get();

        if (count($reservations) == 0) return;

        $reservationToBeCanceled = (new ReservationWithUserResource($reservations[0]))->toArray(null);
        $reservationsToBeCanceled = [];
        $userEmail = $reservationToBeCanceled['user_email'];

        foreach ($reservations as $index => $reservation) {
            $reservationToBeCanceled = (new ReservationWithUserResource($reservation))->toArray(null);
            $reservationsToBeCanceled[] = $reservationToBeCanceled;
            
            $userEmailDB = $reservationToBeCanceled['user_email'];

            if ($userEmailDB != $userEmail || $index == count($reservations) - 1) {
                Mail::to($userEmail)->send(new SendReservationCanceled($reservationsToBeCanceled));
                $reservationsToBeCanceled = [];
                $userEmail = $userEmailDB;
            }
            
            $reservation->items()->detach();
            $reservation->delete();
            $discountId = $reservation->discount_id;
            $discount = Discount::getById($discountId);
            if ($discount != null && count($discount->reservations) == 0) $discount->delete();
        }
    }

    public static function checkUsersReservationsFines()
    {
        // TODO how big fine?
        $FINE_PER_DAY = 200;

        $reservations = Reservation::where('reservation_till', '<', date("Y-m-d"))
                                    ->where('issued', 1)
                                    ->where('returned', 0)
                                    ->with('items', 'items.stores', 'items.languages')
                                    ->with('titles')
                                    ->with('users')
                                    ->orderBy('user_id', 'asc')
                                    ->get();

        if (count($reservations) == 0) return;

        $reservationsToBeFined = [];
        $sumPrice = 0;
        
        $reservationToFined = (new ReservationWithUserResource($reservations[0]))->toArray(null);
        $userEmail = $reservationToFined['user_email'];
        
        foreach ($reservations as $index => $reservation) {
            $reservationToFined = (new ReservationWithUserResource($reservation))->toArray(null);

            // count fine and store it to DB
            $reservation_till = new \DateTime($reservationToFined['reservation_till']);
            $today = new \DateTime(date("Y-m-d"));
            $interval = $reservation_till->diff($today);
            $newFine = $interval->days * $FINE_PER_DAY * $reservationToFined->quantity;
            $reservation->fine = $newFine;
            $reservation->save();

            // count sum price for this reservation 
            $reservation_date = new \DateTime($reservationToFined['reservation']);
            $interval = $reservation_till->diff($reservation_date);
            $reservationPrice = 0;
            if (!$reservationToFined['paid']) {
                $reservationPrice = (($interval->days + 1) * $reservationToFined['price'] * $reservationToFined['quantity']);
                $reservationPrice = ((100 - $reservationToFined['discount']) / 100) * $reservationPrice;
            }
            $reservationToFined['sum_price'] = $newFine + $reservationPrice;
            $sumPrice += $reservationToFined['sum_price'];

            $reservationsToBeFined[] = $reservationToFined;

            $userEmailDB = $reservationToFined['user_email'];

            if ($userEmailDB != $userEmail || $index == count($reservations) - 1) {
                Mail::to($userEmail)->send(new SendReservationFined($sumPrice, $reservationsToBeFined));
                $reservationsToBeFined = [];
                $userEmail = $userEmailDB;
                $sumPrice = 0;
            }
        }
    }
}
