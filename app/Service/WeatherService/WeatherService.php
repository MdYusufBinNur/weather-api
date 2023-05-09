<?php

namespace App\Service\WeatherService;

use App\Action\ResponseAction;
use App\Http\Resources\City\CityResource;
use App\Http\Resources\Weather\WeatherResource;
use App\Models\City;
use App\Models\Weather;
use App\Service\BaseServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService implements BaseServices
{

    public function index(): JsonResponse
    {
        // TODO: Implement index() method.
        $weather = City::query()->with('country','weather')->get();
        return ResponseAction::successResponse('Weather list', CityResource::collection($weather));
    }

    public function store(Request $request): JsonResponse
    {
        // TODO: Implement store() method.
        $url = "https://api.openweathermap.org/data/2.5/weather?";
        $appID = env('WEATHER_APP_ID');
        try {
            $cities = City::query()->get();
            foreach ($cities as $city) {
                $lat = $city->lat;
                $lon = $city->lon;
                $response = Http::get($url . 'lat=' . $lat . '&lon=' . $lon . '&appid=' . $appID . '&units=metric');
                $data = $response->json();
                $weatherData['weather_condition'] = $data['weather'][0]['main']; //Clear, Cloudy
                $weatherData['icon'] = $data['weather'][0]['icon'];
                $weatherData['temperature'] = $data['main']['temp'];
                $weatherData['feels_like'] = $data['main']['feels_like'];
                $weatherData['humidity'] = $data['main']['humidity'];
                $weatherData['wind_speed'] = $data['wind']['speed'] * 3.6; //Converting meter/sec to km/hr
                $weatherData['city_id'] = $city->id;
                Weather::query()->where('city_id','=',$city->id)->updateOrInsert(['city_id' => $city->id], $weatherData);
            }
            return ResponseAction::successResponse('Weather Data Added', null);
        } catch (Exception $e) {
            Log::error('Weather Info ERROR ==> '. $e->getMessage());
            return ResponseAction::errorResponse($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
