<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticateUserRequest;
use App\Http\Resources\AuthenticateUserErrorResource;
use App\Http\Resources\AuthenticateUserSuccessResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * @var UserRepository
     */
    private UserRepository $repository;


    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param AuthenticateUserRequest $request
     * @return JsonResponse
     */
    public function login(AuthenticateUserRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(new AuthenticateUserErrorResource([
                    'Invalid credentials provided.',
                ]), 401);
            }

            return (new AuthenticateUserSuccessResource([
                'token' => $token,
            ]))->response()->setStatusCode(200);

        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }
    }
}
