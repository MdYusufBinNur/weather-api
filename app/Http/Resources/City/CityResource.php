<?php

namespace App\Http\Resources\City;

use App\Http\Resources\Country\CountryResource;
use App\Http\Resources\Weather\WeatherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'country' => new CountryResource($this->country),
            'weather' => new WeatherResource($this->weather)
        ];
    }
}
