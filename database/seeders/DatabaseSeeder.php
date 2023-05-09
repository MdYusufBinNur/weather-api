<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'name' => 'UAE',
                'cities' => [
                    ['name' => 'Abu Dhabi', 'lat' => '24.4539', 'lon' => '54.3773'],
                    ['name' => 'Dubai', 'lat' => '25.2048', 'lon' => '55.2708'],
                    ['name' => 'Sharjah', 'lat' => '25.3463', 'lon' => '55.4209'],
                ],
            ],
            [
                'name' => 'UK',
                'cities' => [
                    ['name' => 'London', 'lat' => '51.5074', 'lon' => '-0.1278'],
                ],
            ],
            [
                'name' => 'USA',
                'cities' => [
                    ['name' => 'New York', 'lat' => '40.7128', 'lon' => '-74.0060'],
                ],
            ],
            [
                'name' => 'Japan',
                'cities' => [
                    ['name' => 'Tokyo', 'lat' => '35.6762', 'lon' => '139.6503'],
                ],
            ],
        ];

        foreach ($countries as $countryData) {
            $country = Country::query()->firstOrCreate(['name' => $countryData['name']],['name' => $countryData['name']]);
            foreach ($countryData['cities'] as $cityData) {

                City::query()->where('country_id', '=', $country->id)->create([
                    'name' => $cityData['name'],
                    'country_id' => $country->id,
                    'lat' => $cityData['lat'],
                    'lon' => $cityData['lon'],
                ]);
            }
        }

        User::query()->firstOrCreate(['email' => 'admin@iq.com'],
        [
            'name' => 'Admin',
            'email' => 'admin@iq.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now()
        ]);
    }
}
