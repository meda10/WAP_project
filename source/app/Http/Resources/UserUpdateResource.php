<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserUpdateResource extends JsonResource
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
            'jmeno' => $this->name,
            'prijmeni' => $this->surname,
            'email' => $this->email,
            'role' => $this->role,
            'adresa' => $this->address,
            'mesto' => $this->city,
            'psc' => $this->zip_code,
            'potvrzeni' => $this->confirmed,
            'obchod' => $this->store_id,
        ];
    }
}
