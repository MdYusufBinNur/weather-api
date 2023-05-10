<?php

namespace App\Service\AuthService;

use App\Action\ResponseAction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(Request $request): JsonResponse
    {
        try {
            $user = User::query()->where('email', '=', $request->email)->firstOrFail();
            $user->updateOrFail(['last_activity' => \date('Y-m-d H:i:s', strtotime(now()))]);
            if (!Hash::check($request->password, $user->password)) {
                return ResponseAction::validationResponse('The provided password is incorrect.');
            }
            $token = $user->createToken('IQToken')->plainTextToken;
            $responseData['user'] = $user;
            $responseData['token'] = $token;
            return ResponseAction::successResponse('Login successfully', $responseData);
        } catch (\Throwable $e) {
            return ResponseAction::validationResponse($e->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        \auth()->user()->currentAccessToken()->delete();
        return ResponseAction::successResponse('Successfully Logout', null);
    }

    public function profile(): JsonResponse
    {
        return ResponseAction::successResponse('Profile Info', auth()->user());
    }
}
