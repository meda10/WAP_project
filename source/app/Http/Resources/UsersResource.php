<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $role = [ 'director' => 'Ředitel', 'employee' => 'Zaměstnanec', 'manager' => 'Manager', 'customer' => 'Zákazník'];
        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'role' => $role[$this->role],
            'confirmed' => $this->confirmed,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'actions' => '',
        ];
    }
}
