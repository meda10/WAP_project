<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'adresa' => $this->address,
            'psc' => $this->zip_code,
            'mesto' => $this->city,
            'popis' => $this->description,
            'actions' => '',
        ];
    }
}
