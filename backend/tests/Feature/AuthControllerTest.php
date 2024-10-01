<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_successfully()
    {
        $email = fake()->email;
        $password = fake()->password;
        $response = $this->postJson('/api/v1/signup', [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
    }

    public function test_signup_fails_due_to_missing_fields()
    {
        $password = fake()->password;
        $response = $this->postJson('/api/v1/signup', [
            'email' => '',
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_signup_fails_due_to_existing_email()
    {
        $email = fake()->email;
        $password = fake()->password;
        User::factory()->create([
            'email' => $email,
        ]);

        $response = $this->postJson('/api/v1/signup', [
            'email' => $email,
            'password' => $password,
            'password_confirmation' => fake()->password,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_signin_successfully()
    {
        $email = fake()->email;
        $password = fake()->password;
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/v1/signin', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'token'
                ]
            ]);
    }

    public function test_signin_fails_due_to_invalid_credentials()
    {
        $email = fake()->email;
        $password = fake()->password;

        $response = $this->postJson('/api/v1/signin', [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid credentials',
            ]);
    }
}
