<?php

namespace App\Http\Controllers\Api;

use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Discount;
use App\Models\Title;
use App\Models\Item;
use App\Mail\SendInvoice;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;


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
     * Return all user's reservaions
     */
    public function getUserReservations(Request $request)
    {
        return Reservation::getUserReservations($request->user_id);
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
     * Creates invoice and sends it to users email
     */
    private function createInvoice($reservations, $discount)
    {
        $invoiceId = 111;

        $user = Auth::user();
        $name = $user->name . ' ' . $user->surname;
        $address = $user->address . ', ' . $user->city . ', ' . $user->zip_code;

        $customer = new Buyer([
            'name'          => $name,
            'custom_fields' => [
                'Email' => $user->email,
                'Adresa' => $address
            ],
        ]);

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

        $invoice = Invoice::make()
            ->buyer($customer)
            ->addItems($items);

        if ($discount != 0)
            $invoice->discountByPercent(intval($discount)); 

        $data = $invoice->stream();
        Mail::to($user->email)->send(new SendInvoice($data));
    }
}
