<?php

namespace App\Service\CountryService;

use App\Action\ResponseAction;
use App\Http\Resources\Country\CountryResource;
use App\Models\Country;
use App\Service\BaseServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CountryService implements BaseServices
{

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // TODO: Implement index() method.
        $countries = Country::query()->latest()->get();
        return ResponseAction::successResponse('Country List', CountryResource::collection($countries));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Implement store() method.
        $country = new Country();
        $country->name = $request->name;
        $country->saveOrFail();
        return ResponseAction::successResponse('Country Added', new CountryResource($country->refresh()));
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request, $id): JsonResponse
    {
        // TODO: Implement update() method.

        $country = Country::query()->findOrFail($id);
        $data['name'] = $request->name ?: $country->name;
        $country->updateOrFail($data);
        return ResponseAction::successResponse('Country name updated', new CountryResource($country->refresh()));
    }

    /**
     * @throws Throwable
     */
    public function destroy($id): JsonResponse
    {
        // TODO: Implement destroy() method.
        $country = Country::query()->findOrFail($id);
        $country->deleteOrFail();
        return ResponseAction::successResponse('Country deleted', null);
    }
}
