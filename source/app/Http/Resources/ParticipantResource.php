<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'jmeno' => $this->name,
            'prijmeni' => $this->surname,
            'datum_narozeni' => $this->birth,
            'actions' => '',
        ];
    }
}
