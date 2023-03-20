<?php

namespace Tests\Feature\Api\v1;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
    public function should_get_users_all_index(): void
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
    public function should_return_error_no_authorization_get_users_all(): void
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'pontue123',
            'password_confirmation' => 'pontue123',
        ];

        $res = $this->postJson('/api/auth/register', $data);

//        $token = $res['token'];
        $response = $this->withHeaders([
            'Authorization' => "Bearer token",
        ])->getJson('/api/users');
//        dd($response['data']['data']);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function should_user_login_ok():void
    {
        User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);
        $response->assertStatus(200);
    }

    /** @test */
    public function should_user_login_error_password():void
    {
        User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue12'
        ]);
        $response->assertStatus(401);
    }

    /**@test */
    public function should_user_login_error_email():void
    {
        User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'pontuee@admin.com',
            'password' => 'pontue123'
        ]);
        $response->assertStatus(401);
    }
}
