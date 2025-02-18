<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register new user.
     * @param \App\Http\Requests\AuthRequest $request
     * @return JsonResponse|mixed
     */
    public function register(AuthRequest $request): JsonResponse
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
     * @return JsonResponse|mixed
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials!',
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Authentication error!',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Logout user.
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Session closed!'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Logout error!'
            ], 400);
        }
    }
}
