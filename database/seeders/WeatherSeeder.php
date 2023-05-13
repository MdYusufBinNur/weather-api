<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Models\WeatherHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WeatherSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $cityIds = City::query()->pluck('id')->toArray(); // Replace with an array of city IDs you want to generate data for
        $intervalMinutes = 120; // The number of minutes per interval (2 hours)
        $startDate = date('Y-m-d', strtotime('-1 day')); // Get yesterday's date in the yyyy-mm-dd format
        $endDate = date('Y-m-d'); // Get today's date in the yyyy-mm-dd format

        $weatherConditions = ['Clear', 'Cloudy']; // List of possible weather conditions

        foreach ($cityIds as $cityId) {
            $date = $startDate;
            while ($date <= $endDate) {
                for ($hour = 0; $hour < 24; $hour++) {
                    for ($minute = 0; $minute < 60; $minute += $intervalMinutes) {
                        $temperature = rand(20, 35);
                        $feelsLike = $temperature + rand(-5, 5);
                        $humidity = rand(40, 90);
                        $windSpeed = rand(0, 20);
                        $weatherCondition = $weatherConditions[rand(0, 1)];

                        WeatherHistory::query()->create([
                            'city_id' => $cityId,
                            'temperature' => $temperature,
                            'feels_like' => $feelsLike,
                            'humidity' => $humidity,
                            'wind_speed' => $windSpeed,
                            'weather_condition' => $weatherCondition,
                            'created_at' => "$date $hour:" . sprintf('%02d', $minute) . ":00",
                            'updated_at' => "$date $hour:" . sprintf('%02d', $minute) . ":00"
                        ]);
                    }
                }
                $date = date('Y-m-d', strtotime('+1 day', strtotime($date))); // Advance to the next day
            }
        }
    }
}
