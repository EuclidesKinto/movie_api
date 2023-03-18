<?php

namespace Tests\Feature\Api\v1;

use Illuminate\Testing\Fluent\AssertableJson;
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
    public function if_get_users_all_index(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'pontue123',
            'password_confirmation' => 'pontue123',
        ];
        $res = $this->post('/api/auth/register', $data);

        $token = $res['token'];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/users');

        $response->assertStatus(200);
    }

    /** @test */
    public function if_get_users_all_type_index(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'pontue123',
            'password_confirmation' => 'pontue123',
        ];

        $res = $this->post('/api/auth/register', $data);

        $token = $res['token'];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/users');
//        dd($response['data']['data']);

        $response->assertJson(fn (AssertableJson $json)=>
            $json->whereAllType([
                'data.data.0.id' => 'integer',
                'data.data.0.name' => 'string',
                'data.data.0.email' => 'string',
                'data.data.0.email_verified_at' => 'string|null',
                'data.data.0.created_at' => 'string',
                'data.data.0.updated_at' => 'string',
            ])
        );

        $response->assertStatus(200);
    }

    /** @test */
    public function should_get_users_all_type_index(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'pontue123',
            'password_confirmation' => 'pontue123',
        ];

        $res = $this->post('/api/auth/register', $data);

        $token = $res['token'];
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/users');
//        dd($response['data']['data']);

        $response->assertJson(fn (AssertableJson $json)=>
        $json->whereAllType([
            'data.data.0.id' => 'integer',
            'data.data.0.name' => 'string',
            'data.data.0.email' => 'string',
        ])
        );

        $response->assertStatus(200);
    }
}
