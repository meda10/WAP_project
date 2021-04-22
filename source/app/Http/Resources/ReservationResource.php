<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $toReturn = [
            'id' => $this->id,
            'issued' => $this->issued,
            'paid' => $this->paid,
            'fine_paid' => $this->fine_paid,
            'price' => $this->price,
            'quantity' => count($this->items),
            'language' => $this->items[0]->languages->language,
            'returned' => $this->returned,
            'title_id' => $this->titles->id,
            'title_name' => $this->titles->title_name,
            'reservation' => $this->reservation,
            'reservation_till' => $this->reservation_till,
            'fine' => $this->fine,
            'discount' => 0,
            'store_id' => $this->items[0]->stores->id,
            'store_address' => $this->items[0]->stores->address . ', ' . 
                        $this->items[0]->stores->city . ', ' . $this->items[0]->stores->zip_code
        ];

        if ($this->discounts != null) $toReturn['discount'] = $this->discounts->percent;
        return $toReturn;
    }
}
