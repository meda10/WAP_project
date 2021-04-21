<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (isset($this->pivot['director'])) {
            return [
                'value' => strval($this->id),
                'label' => $this->name." ".$this->surname,
                'herec' => strval($this->id),
                'reziser' => [strval($this->pivot['director'])]
            ];
        } else{
            return [
                'value' => strval($this->id),
                'label' => $this->name." ".$this->surname,
                'herec' => strval($this->id),
            ];
        }
    }

}

