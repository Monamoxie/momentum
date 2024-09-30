<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup(SignupRequest $request, UserService $userService)
    {
        $user = $userService->createUser($request->email, $request->password);
        if (!$user instanceof User) {
            return response()->json([
                'errors' => [
                    'account' => ['User was not created. Please try again']
                ]
            ], 500);
        }

        return new UserResource($user);
    }

    public function signin(SigninRequest $request, UserService $userService)
    {
        $user = $userService->authenticateUser($request->email, $request->password);

        if ($user instanceof User) {
            $token = $userService->createToken($user);
            return $this->successResponse(message: "Login Successful", data: [
                "token" => $token,
                "user" => new UserResource($user)
            ]);
        }

        return $this->errorResponse(message: "Invalid credentials", code: 422);
    }
}
