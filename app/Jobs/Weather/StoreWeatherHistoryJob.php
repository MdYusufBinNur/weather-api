<?php

namespace App\Jobs\Weather;

use App\Models\Weather;
use App\Models\WeatherHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreWeatherHistoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $weather = Weather::query()->get();
        foreach ($weather as $value) {
            $weatherData['weather_condition'] = $value->weather_condition; //Clear, Cloudy
            $weatherData['icon'] = $value->icon;
            $weatherData['temperature'] = $value->temperature;
            $weatherData['feels_like'] = $value->feels_like;
            $weatherData['humidity'] = $value->humidity;
            $weatherData['wind_speed'] = $value->wind_speed; //Converting meter/sec to km/hr
            $weatherData['city_id'] = $value->city_id;
            WeatherHistory::query()->insert($weatherData);
        }
    }
}
