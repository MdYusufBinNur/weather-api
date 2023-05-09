<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Service\WeatherService\WeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected WeatherService $service;

    public function __construct(WeatherService $weatherService)
    {
        $this->service = $weatherService;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->service->index();
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->service->store($request);
    }
}
