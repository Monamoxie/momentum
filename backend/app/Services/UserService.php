<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(string $email, string $password): ?User
    {
        $user = new User;
        $user->email = $email;
        $user->password = Hash::make($password);

        if ($user->save()) {
            return $user;
        }

        return null;
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where("email", $email)->first();
    }

    public function authenticateUser(string $email, string $password): User|null|bool
    {
        $authenticate = Auth::attempt(['email' => $email, 'password' => $password]);
        if ($authenticate) {
            return $this->getUserByEmail($email);
        }

        return false;
    }

    public function createToken(User $user)
    {
        $token = $user->createToken($user->email)->plainTextToken;
        return $token;
    }
}
