<?php

namespace Tests\Feature\Api\v1;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MovieTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function should_get_movies_all_index(): void
    {
        $user = User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);
        $userAuth = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);
//        $token = $userAuth['token'];
        $movie = [
            'name' => $this->faker->name,
            'image' => $this->faker->image,
            'description' => $this->faker->text,
            'user_id' => $user->id,
        ];

        $res = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->postJson('/api/movies', $movie);

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->getJson('/api/movies');

        $response->assertStatus(200);
    }

    /** @test */
    public function should_create_movie_validate_name(): void
    {
        $user = User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);
        $userAuth = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);

        $movie = [
//            'name' => $this->faker->name,
            'image' => $this->faker->image,
            'description' => $this->faker->text,
            'user_id' => $user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
            'Content-Type' => 'multipart/form-data',
            'Accept' => 'application/json'
        ])->postJson('/api/movies', $movie);

        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['message', 'errors'])
        );

        $response->assertJsonValidationErrorFor('name');
    }

    /** @test */
    public function should_create_movie_validate_image(): void
    {
        $user = User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);
        $userAuth = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);

        $movie = [
            'name' => $this->faker->name,
            'image' => $this->faker->image,
            'description' => $this->faker->text,
            'user_id' => $user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
            'Content-Type' => 'multipart/form-data',
            'Accept' => 'application/json'
        ])->postJson('/api/movies', $movie);

        $response->assertJson(fn(AssertableJson $json) =>
        $json->hasAll(['message', 'errors'])
        );

        $response->assertJsonValidationErrorFor('image');
    }

    /** @test */
    public function should_create_movie_validate_description(): void
    {
        $user = User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);
        $userAuth = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);

        $movie = [
            'name' => $this->faker->name,
            'image' => $this->faker->image,
//            'description' => $this->faker->text,
            'user_id' => $user->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
            'Content-Type' => 'multipart/form-data',
            'Accept' => 'application/json'
        ])->postJson('/api/movies', $movie);

        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['message', 'errors'])
        );

        $response->assertJsonValidationErrorFor('description');
    }

    /** @test */
    public function should_movie_post_endpoint_throw_an_unauthorized_status(): void
    {
        Movie::factory()->create();
        $response = $this->postJson('/api/movies', []);
        $response->assertUnauthorized();
    }


    public function test_should_movie_delete():void
    {
        $user = User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);
        $userAuth = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);
        $movie = Movie::factory()->create([
            'name' => $this->faker->name,
            'image' => $this->faker->image,
            'description' => $this->faker->text,
            'user_id' => $user->id,
        ]);
        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token']
        ])->deleteJson('/api/movies/'.$movie->id, []);
        $response->assertNoContent();
    }

    public function test_should_movie_delete_user_no_unauthorized():void
    {
        $user = User::factory()->create([
            'name' => 'pontue',
            'email' => 'pontue@admin.com',
            'password' => bcrypt('pontue123')
        ]);
        $userAuth = $this->postJson('/api/auth/login', [
            'email' => 'pontue@admin.com',
            'password' => 'pontue123'
        ]);
        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token']
        ])->deleteJson('/api/movies/'.Movie::factory()->create()->id, []);
        $response->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['message'])
        );

    }

}
