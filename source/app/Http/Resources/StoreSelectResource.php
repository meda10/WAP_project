<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreSelectResource extends JsonResource
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
            'label' => $this->address." ".$this->city." ".$this->zip_code,
            'store' => strval($this->id),
        ];
    }
}
