<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\StoreRequest;
use App\Service\CountryService\CountryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CountryController extends Controller
{
    protected CountryService $service;

    public function __construct(CountryService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->service->index();
    }

    /**
     * @param StoreRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
//        $request->validationData();
        return $this->service->store($request);
    }

    /**
     * @param StoreRequest $request
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(StoreRequest $request, $id): JsonResponse
    {
        $request->validationData();
        return $this->service->update($request, $id);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy($id): JsonResponse
    {
        return $this->service->destroy($id);
    }
}

