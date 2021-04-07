<?php

namespace App\Http\Controllers\Api;

use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Models\Reservation;
use App\Models\Discount;
use App\Models\Title;
use App\Models\Item;
use App\Models\User;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\UsersResource;
use App\Helpers\AppHelper;


class ReservationsController extends Controller
{
    /**
     * Make new reservation
     */
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

        $paid = $request->paymentMethod == 'online' ? 1 : 0;
        
        $discountId = null;
        $discountPercent = 0;

        if ($request->discount != '') {
            $discount = Discount::where('code', $request->discount)->first();
            $discountId = $discount->id;
            $discountPercent = $discount->percent;
        }

        foreach ($request->reservations as $index=>$reservation) {
            $freeItemsIds = $reservationsFreeItemsIds[$index];

            $new_reservation = Reservation::create([
                'reservation' => $reservation['reservationTimeRange'][0],
                'reservation_till' => $reservation['reservationTimeRange'][1],
                'price' => $reservation['price'],
                'paid' => $paid,
                'invoice_id' => 111, // TODO change this 
                'user_id' => $request->userId,
                'title_id' => $reservation['title_id'],
                'discount_id' => $discountId
            ]);

            for ($i = 0; $i < $reservation['quantity']; $i++)
                $new_reservation->items()->attach($freeItemsIds[$i]->id);
        }

        $this->createInvoice($request->reservations, $discountPercent);

        return response()->json(['success' => 'success'], 200);
    }

    /**
     * Return all reservaions of logged in user
     */
    public function getUserReservations()
    {
        $user = Auth::user();
        return ReservationResource::collection(Reservation::getUserReservations($user->email));
    }

    /**
     * Return all reservaions of user by given email
     * Or HTTP 400 if given not existing email
     */
    public function getUserReservationsByEmail(Request $request)
    {
        $userEmail = $request->user_email;
        $user = User::where('email', $userEmail)->first();

        if ($user == null) 
            return response()->json(['error' => 'user_not_found'], 400);

        $reservations = ReservationResource::collection(Reservation::getUserReservations($userEmail));
        return response()->json(['user' => $user, 'reservations' => $reservations], 200);
    }

    /**
     * Return issued reservation
     */
    public function returnReservation(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservationId);
        $reservation['returned'] = 1;
        $reservation->save();
    }

    /**
     * Cancel reservation
     */
    public function cancelReservation(Request $request)
    {
        $reservation = Reservation::find($request->reservationId);
        $reservation->items()->detach();
        $reservation->delete();
        $discountId = $reservation->discount_id;
        $discount = Discount::getById($discountId);
        if ($discount != null && count($discount->reservations) == 0) $discount->delete();
    }

    /**
     * Set every reservation to paid and send invoice for all items
     */
    public function payReservation(Request $request)
    {

        $items = [];

        foreach ($request->reservations as $reservation) {
            $reservationDB = Reservation::findOrFail($reservation['id']);
            $reservationDB['paid'] = $reservationDB['issued'] = 1;
            $reservationDB->save();
            
            $itemName = $reservation['title_name'] . ' (' . $reservation['language'] . ' dabing)';
            $itemQuantity = intval($reservation['quantity']);
            $itemPrice = intval($reservation['price']) * intval($reservation['reservationNumberOfDays']);

            $startDate = new \DateTime($reservation['reservation']);
            $endDate = new \DateTime($reservation['reservation_till']);
            $itemDateRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');

            $item = (new InvoiceItem())
                ->title($itemName)
                ->pricePerUnit($itemPrice)
                ->units($itemDateRange)
                ->quantity($itemQuantity);

            if ($reservation['discount'] != 0)
                $item->discountByPercent($reservation['discount']);
            
            $items[] = $item;
        }

        $user = $request->user;
        AppHelper::createInvoiceAndSend($user, $items, 0);

        return response()->json(['success' => 'ok'], 200);
    }


    /**
     * Pay for fines
     */
    public function payFines(Request $request)
    {
        $items = [];

        foreach ($request->reservations as $reservation) {
            // return reservation
            $reservationDB = Reservation::findOrFail($reservation['id']);
            $reservationDB['returned'] = 1;
            $reservationDB['fine_paid'] = 1;
            $reservationDB->save();

            $itemName = $reservation['title_name'] . ' (' . $reservation['language'] . ' dabing)' . ' - pokuta';
            $itemPrice = intval($reservation['fineSum']);

            $startDate = new \DateTime($reservation['reservation']);
            $endDate = new \DateTime($reservation['reservation_till']);
            $itemDateRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');

            $item = (new InvoiceItem())
                ->title($itemName)
                ->pricePerUnit($itemPrice)
                ->quantity(1)
                ->units($itemDateRange);
            
            $items[] = $item;
        }

        $user = $request->user;
        AppHelper::createInvoiceAndSend($user, $items, 0);

        return response()->json(['success' => 'ok'], 200);
    }

    /**
     * Creates invoice and sends it to users email
     */
    private function createInvoice($reservations, $discount)
    {
        $items = [];

        foreach ($reservations as $reservation) {
            $itemName = $reservation['name'] . ' (' . $reservation['language_name'] . ' dabing)';
            $itemQuantity = intval($reservation['quantity']);
            $itemPrice = intval($reservation['price']) * intval($reservation['reservationNumberOfDays']);

            $startDate = new \DateTime($reservation['reservationTimeRange'][0]);
            $endDate = new \DateTime($reservation['reservationTimeRange'][1]);
            $itemDateRange = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');

            $items[] = (new InvoiceItem())
                ->title($itemName)
                ->pricePerUnit($itemPrice)
                ->units($itemDateRange)
                ->quantity($itemQuantity);
        }

        $user = (new UsersResource(Auth::user()))->toArray(null);
        AppHelper::createInvoiceAndSend($user, $items, $discount);
    }
}
