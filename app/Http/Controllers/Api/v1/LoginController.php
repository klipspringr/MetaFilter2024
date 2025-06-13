<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LoginController extends BaseApiController
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            $message = trans('Login successful');

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user_id' => $user->id,
            ];

            return $this->sendResponse($message, $data);
        }

        return $this->sendError(
            error: 'Unauthorised.',
            errorMessages: [
                'message' => trans(key: 'The provided credentials are incorrect.'),
            ],
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(data: [
            'message' => trans(key: 'Logged out successfully'),
        ]);
    }
}
