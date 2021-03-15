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
            'value' => strval($this->id),
            'label' => $this->name." ".$this->surname,
            'herec' => strval($this->id),
        ];
    }

}

