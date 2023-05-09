<?php

namespace App\Http\Resources\Weather;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'weather_condition' => $this->weather_condition,
            'icon' => $this->icon,
            'temperature' => $this->temperature,
            'feels_like' => $this->feels_like,
            'humidity' => $this->humidity,
            'wind_speed' => $this->wind_speed,
        ];
    }
}
