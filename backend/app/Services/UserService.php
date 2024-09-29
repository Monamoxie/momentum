<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function createUser(string $email, string $password): ?User
    {
        $user = new User;
        $user->email = $email;
        $user->password = $password;

        if ($user->save()) {
            return $user;
        }

        return null;
    }

    public function login(string $email, string $password)
    {
        return Auth::attempt($email, $password);
    }

    public function createToken(string $email, string $password) {}
}
