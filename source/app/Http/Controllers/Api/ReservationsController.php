<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use App\Models\Reservation;
use App\Models\Discount;
use App\Models\Title;
use App\Models\Item;


class ReservationsController extends Controller
{
    public function makeReservation(Request $request)
    {

        if ($request->discount != '') {
            if (Discount::checkCodeExistNotUsed($request->discount) == [])
                return response()->json(['error' => 'discount_code'], 400);
        }


        $interval = new \DateInterval('P1D');
        $reservationsCannotFinish = [];

        foreach ($request->reservations as $reservation) {
            // check if there is enough cassetes in store
            $start = new \DateTime($reservation['reservationTimeRange'][0]);
            $end = new \DateTime($reservation['reservationTimeRange'][1]);
            $end->setTime(0,0,1);

            $daterange = new \DatePeriod($start, $interval, $end);

            $existingReservations = Reservation::getTitleReservations($reservation['url'], $request['storeId']);

            if (isset($existingReservations[$reservation['language_name']])) {
                foreach ($daterange as $date) {
                    if (isset($existingReservations[$reservation['language_name']][$date->format('Y-m-d')])) {
                        $existingReservationCount = $existingReservations[$reservation['language_name']][$date->format('Y-m-d')];
                        $titleMaxCounts = Title::getTitleItemsMaxCounts($reservation['type'], $reservation['url'], $request['storeId']);

                        // cannot reserve, no free cassetes
                        if ($titleMaxCounts[$reservation['language_name']] < $existingReservationCount + $reservation['quantity']) {
                            $reservationsCannotFinish[] = $reservation;
                            break;
                        }
                    }
                }
            }
        }

        // if there is some reservation that  cannot be performed then return bad request with list of titles 
        // that cannot be reserved
        if (count($reservationsCannotFinish) != 0) {
            return response()->json(['error' => 'time_violation', 'violation' => $reservationsCannotFinish], 400);
        }

        // TODO create invoice
        $invoiceId = 111;
        $paid = $request->paymentMethod == 'online' ? 1 : 0;
        

        if ($request->discount == '')
            $discountId = null;
        else
            $discountId = Discount::where('code', $request->discount)->first()->id;

        
        foreach ($request->reservations as $reservation) {
            $freeItemsIds = Item::getFreeItemsIds($reservation['url'], $reservation['language_name'], 
                $request['storeId'], $reservation['reservationTimeRange'][0], $reservation['reservationTimeRange'][1]);
            
            $new_reservation = Reservation::create([
                'reservation' => $reservation['reservationTimeRange'][0],
                'reservation_till' => $reservation['reservationTimeRange'][1],
                'price' => $reservation['price'],
                'paid' => $paid,
                'invoice_id' => $invoiceId,
                'user_id' => $request->userId,
                'title_id' => $reservation['title_id'],
                'discount_id' => $discountId
            ]);

            for ($i = 0; $i < $reservation['quantity']; $i++)
                $new_reservation->items()->attach($freeItemsIds[$i]->id);
        }

        return response()->json(['success' => 'success'], 200);
    }

    public function getUserReservations(Request $request)
    {
        return Reservation::getUserReservations($request->user_id);
    }

    public function cancelReservation(Request $request)
    {
        $reservation = Reservation::find($request->reservationId);
        $reservation->items()->detach();
        $reservation->delete();
        $discountId = $reservation->discount_id;
        $discount = Discount::getById($discountId);
        if ($discount != null && count($discount->reservations) == 0) $discount->delete();
    }
}
