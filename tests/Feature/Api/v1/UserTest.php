<?php

namespace Tests\Feature\Api\v1;

use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     */
    /** @test */
    public function get_users_index(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/api/auth/register', $data);

        $token = $response['token'];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/users');

        $response->assertStatus(200);
    }
}
