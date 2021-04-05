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

        $reservationsFreeItemsIds = [];
        $canCreateReservations = true;

        // get all free items id for all titles (reservations)
        foreach ($request->reservations as $reservation) {
            $freeItemsIds = Item::getFreeItemsIds($reservation['url'], $reservation['language_name'], 
                    $request['storeId'], $reservation['reservationTimeRange'][0], $reservation['reservationTimeRange'][1]);
            $reservationsFreeItemsIds[] = $freeItemsIds;
            if (count($freeItemsIds) == 0) $canCreateReservations = false;
        }

        // if there is some reservation that  cannot be performed then return bad request with list of titles 
        // that cannot be reserved
        if (!$canCreateReservations) {
            return response()->json(['error' => 'time_violation', 'violation' => $reservationsCannotFinish], 400);
        }

        $invoiceId = $this->createInvoice();
        $paid = $request->paymentMethod == 'online' ? 1 : 0;
        

        if ($request->discount == '')
            $discountId = null;
        else
            $discountId = Discount::where('code', $request->discount)->first()->id;

        
        foreach ($request->reservations as $index=>$reservation) {
            $freeItemsIds = $reservationsFreeItemsIds[$index];

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

    private function createInvoice()
    {
        $invoiceId = 111;

        return $invoiceId;
    }
}
