<?php

namespace App\Service\WeatherService;

use App\Action\ResponseAction;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use App\Models\Weather;
use App\Models\WeatherHistory;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{

    public function index(): JsonResponse
    {
        // TODO: Implement index() method.
        $weather = City::query()->with('country', 'weather')->get();
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
                Weather::query()->where('city_id', '=', $city->id)->updateOrInsert(['city_id' => $city->id], $weatherData);
                WeatherHistory::query()->insert($weatherData);
            }
            return ResponseAction::successResponse('Weather Data Added', null);
        } catch (Exception $e) {
            Log::error('Weather Info ERROR ==> ' . $e->getMessage());
            return ResponseAction::errorResponse($e->getMessage());
        }
    }

    /**
     * @param $cityId
     * @return JsonResponse
     *
     */
    public function history($cityId): JsonResponse
    {
        $city = City::query()->findOrFail($cityId);
        $date = Carbon::yesterday();
        $response = [
            'temperature' => ['hour' => [], 'value' => []],
            'humidity' => ['hour' => [], 'value' => []],
            'wind_speed' => ['hour' => [], 'value' => []]
        ];

        for ($i = 0; $i < 12; $i++) {

            $avgTemperature = WeatherHistory::query()->where('city_id', '=', $cityId)
                ->whereBetween('created_at', [$date->copy()->hour($i * 2)->minute(0)->second(0), $date->copy()->hour(($i * 2) + 1)->minute(59)->second(59)])
                ->avg('temperature');
            $avgHumidity = WeatherHistory::query()->where('city_id', '=', $cityId)
                ->whereBetween('created_at', [$date->copy()->hour($i * 2)->minute(0)->second(0), $date->copy()->hour(($i * 2) + 1)->minute(59)->second(59)])
                ->avg('humidity');
            $avgWindSpeed = WeatherHistory::query()->where('city_id', '=', $cityId)
                ->whereBetween('created_at', [$date->copy()->hour($i * 2)->minute(0)->second(0), $date->copy()->hour(($i * 2) + 1)->minute(59)->second(59)])
                ->avg('wind_speed');

            $hour = $i == 0 ? '00' : str_pad($i * 2, 2, '0', STR_PAD_LEFT);
            $response['temperature']['hour'][] = $hour;
            $response['temperature']['value'][] = round($avgTemperature, 2);
            $response['humidity']['hour'][] = $hour;
            $response['humidity']['value'][] = round($avgHumidity, 2);
            $response['wind_speed']['hour'][] = $hour;
            $response['wind_speed']['value'][] = round($avgWindSpeed, 2);

        }
        $response['report_date'] = $date->format('Y-m-d');
        return ResponseAction::successResponse('Weather report', $response);
    }
}
