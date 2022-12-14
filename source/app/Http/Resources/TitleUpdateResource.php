<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class TitleUpdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $genres = GenreResource::collection($this->genres);
        $genre_arr = [];
        foreach ($genres as $value) {
            array_push($genre_arr, $value->id);
        }
        return [
            'titul' => $this->title_name,
            'rok' => $this->year,
            'zeme_puvodu' => $this->state_id,
            'typ' => $this->type,
            'cena' => $this->price,
            'popis' => $this->description,
            'herci' => ParticipantSelectResource::collection($this->participant),
            'zanr' => $genre_arr,
            'polozka' => ItemsResource::collection($this->items),
        ];
    }
}
