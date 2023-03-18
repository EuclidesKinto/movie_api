<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_registers_a_new_user()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

}
