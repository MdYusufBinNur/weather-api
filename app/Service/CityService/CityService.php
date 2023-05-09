<?php

namespace App\Service\CityService;

use App\Action\ResponseAction;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use App\Service\BaseServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CityService implements BaseServices
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // TODO: Implement index() method.
        $cities = City::query()->latest()->get();
        return ResponseAction::successResponse('City list', CityResource::collection($cities));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Implement store() method.
        $city = new City();
        $city->name = $request->name;
        $city->lat = $request->lat;
        $city->lon = $request->lon;
        $city->country_id = $request->country_id;
        $city->saveOrFail();
        return ResponseAction::successResponse('City added', $city->refresh());
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request, $id): JsonResponse
    {
        // TODO: Implement update() method.
        $city = City::query()->findOrFail($id);
        $data['name'] = $request->name ?: $city->name;
        $data['lat'] = $request->lat ?: $city->lat;
        $data['lon'] = $request->lon ?: $city->lon;
        $data['country_id'] = $request->country_id ?: $city->country_id;
        $city->updateOrFail($data);
        return ResponseAction::successResponse('City updated', new CityResource($city->refresh()));
    }

    /**
     * @throws Throwable
     */
    public function destroy($id): JsonResponse
    {
        // TODO: Implement destroy() method.
        $city = City::query()->findOrFail($id);
        $city->deleteOrFail();
        return ResponseAction::successResponse('City deleted', null);
    }
}
