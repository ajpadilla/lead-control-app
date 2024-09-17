<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_login_with_valid_credentials()
    {
        // Arrange: Create a user with known credentials
        $user = User::factory()->create([
            'password' => Hash::make('password123'), // Known password
        ]);

        // Act: Make a POST request to the login endpoint
        $response = $this->postJson('api/lead-control/v1/auth', [
            'username' => $user->username,
            'password' => 'password123',
        ]);

        // Assert: Verify the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'meta' => [
                    'success',
                    'errors'
                ],
                'data' => [
                    'token',
                    'minutes_to_expire'
                ]
            ])
            ->assertJson([
                'meta' => [
                    'success' => true,
                    'errors' => [],
                ],
                'data' => [
                    'minutes_to_expire' => 1440, // Ensure this value is included
                ],
            ]);
    }


    /** @test */
    public function it_cannot_login_with_invalid_credentials()
    {
        // Act: Make a POST request to the login endpoint with invalid credentials
        $response = $this->postJson('api/lead-control/v1/auth', [
            'username' => 'nonexistentuser',
            'password' => 'wrongpassword',
        ]);

        // Assert: Verify the response
        $response->assertStatus(401)
            ->assertJsonStructure([
                'meta' => [
                    'success',
                    'errors'
                ]
            ])
            ->assertJson([
                'meta' => [
                    'success' => false,
                    'errors' => [
                        'Invalid credentials provided.',
                    ],
                ],
            ]);
    }


    /** @test */
    public function it_responds_with_validation_errors_when_invalid_data_is_provided()
    {
        // Act: Make a POST request to the login endpoint with invalid data
        $response = $this->postJson('api/lead-control/v1/auth', [
            'username' => '', // Invalid data
            'password' => '', // Invalid data
        ]);

        // Assert: Verify the response status and format
        $response->assertStatus(422)
            ->assertJsonStructure([
                'meta' => [
                    'success',
                    'errors'
                ]
            ])
            ->assertJson([
                'meta' => [
                    'success' => false,
                    'errors' => [
                        'The username field is required.',
                        'The password field is required.',
                    ],
                ],
            ]);
    }

}
