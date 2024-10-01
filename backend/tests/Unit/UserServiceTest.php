<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected $userService;

    public function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    public function test_create_user_successfully()
    {
        $email = fake()->email;
        $password = fake()->password;

        $user = $this->userService->createUser($email, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->email);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    public function test_authenticate_user_successfully()
    {
        $email = fake()->email;
        $password = fake()->password;

        $user = User::factory()->create([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $authenticatedUser = $this->userService->authenticateUser($email, $password);

        $this->assertInstanceOf(User::class, $authenticatedUser);
        $this->assertEquals($user->id, $authenticatedUser->id);
    }

    public function test_authenticate_user_failure()
    {
        $email = fake()->email;
        $password = fake()->password;

        $authenticatedUser = $this->userService->authenticateUser($email, $password);

        $this->assertFalse($authenticatedUser);
    }

    public function test_create_token_for_user()
    {
        $user = User::factory()->create();
        $token = $this->userService->createToken($user);

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
    }
}
