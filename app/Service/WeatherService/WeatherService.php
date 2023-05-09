<?php

namespace App\Service\WeatherService;

use App\Action\ResponseAction;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use App\Models\Weather;
use App\Models\WeatherHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{

    public function index(): JsonResponse
    {
        // TODO: Implement index() method.
        $weather = City::query()->with('country','weather')->get();
        return ResponseAction::successResponse('Weather list', CityResource::collection($weather));
    }

    public function store(): JsonResponse
    {
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
                WeatherHistory::query()->insert($weatherData);
            }
            return ResponseAction::successResponse('Weather Data Added', null);
        } catch (Exception $e) {
            Log::error('Weather Info ERROR ==> '. $e->getMessage());
            return ResponseAction::errorResponse($e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $now = Carbon::now(); // get the current time
        $last24Hours = $now->subDay();
        $records = WeatherHistory::query()
            ->where('city_id','=', $request->city_id)
            ->whereBetween('created_at',[$last24Hours, $now])
            ->get();
    }
}
