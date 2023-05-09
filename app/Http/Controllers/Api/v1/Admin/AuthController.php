<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Service\AuthService\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request): JsonResponse
    {
        return $this->service->login($request);
    }

    public function logout(): JsonResponse
    {
        return $this->service->logout();
    }
}
