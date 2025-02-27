<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Register new user.
     * @param \App\Http\Requests\RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());

            return response()->json([
                'user' => $user,
                'message' => 'User saved successfully!',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error saving user!'
            ], 400);
        }
    }

    /**
     * Login user.
     * @param \App\Http\Requests\LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid credentials!',
                ], 401);
            }

            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Authentication error!',
            ], 400);
        }
    }

    /**
     * Logout user.
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth('api')->logout();

            return response()->json([
                'message' => 'Successfully logged out!'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Logout error!'
            ], 400);
        }
    }

    /**
     * Get the token array structure.
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'user' => auth('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
