<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationWithUserResource extends JsonResource
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
            'price' => $this->price,
            'paid' => $this->paid,
            'fine_paid' => $this->fine_paid,
            'quantity' => count($this->items),
            'language' => $this->items[0]->languages->language,
            'title_name' => $this->titles->title_name,
            'reservation' => date_format(date_create($this->reservation), "d.m.Y"),
            'reservation_till' => date_format(date_create($this->reservation_till), "d.m.Y"),
            'discount' => 0,
            'user_email' => $this->users->email,
            'store_address' => $this->items[0]->stores->address . ', ' . 
                        $this->items[0]->stores->city . ', ' . $this->items[0]->stores->zip_code
        ];

        if ($this->discounts != null) $toReturn['discount'] = $this->discounts->percent;
        return $toReturn;
    }
}
