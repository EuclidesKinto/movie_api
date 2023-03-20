<?php

namespace Tests\Feature\Api\v1;

use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function should_get_reviews_all_index(): void
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
        $movie = Movie::factory()->create();
        $review = [
            'name' => $user->name,
            'rating' => $this->faker->randomNumber(9),
            'comment' => $this->faker->paragraphs,
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ];

        $res = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->postJson('/api/movies/'.$movie->id.'/reviews', $review);

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->getJson('/api/movies/'.$movie->id.'/reviews');

        $response->assertStatus(200);
    }

    /** @test */
    public function should_review_post_endpoint_throw_an_unauthorized_status(): void
    {
//        Review::factory()->create();
        $response = $this->postJson('/api/movies/'.Movie::factory()->create()->id.'/reviews', [
            Review::factory()->create()
        ]);
        $response->assertUnauthorized();
    }

    /** @test */
    public function should_create_review_validate_rating(): void
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
        $movie = Movie::factory()->create();
        $review = [
            'name' => $this->faker->name,
            //'rating' => $this->faker->randomNumber(9),
            'comment' => $this->faker->paragraphs,
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ];

        $res = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->postJson('/api/movies/'.$movie->id.'/reviews', $review);

        $res->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['message','errors'])
        );

        $res->assertJsonValidationErrorFor('rating');
    }

    /** @test */
    public function should_create_review_validate_comment(): void
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
        $movie = Movie::factory()->create();
        $review = [
            'name' => $this->faker->name,
            'rating' => $this->faker->randomNumber(9),
//            'comment' => $this->faker->paragraphs,
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ];

        $res = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->postJson('/api/movies/'.$movie->id.'/reviews', $review);

        $res->assertJson(fn(AssertableJson $json) =>
            $json->hasAll(['message','errors'])
        );

        $res->assertJsonValidationErrorFor('comment');
    }

    /** @test */
    public function should_create_review_validate_rating_comment(): void
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
        $movie = Movie::factory()->create();
        $review = [
            'name' => $this->faker->name,
//            'rating' => $this->faker->randomNumber(9),
//            'comment' => $this->faker->paragraphs,
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ];

        $res = $this->withHeaders([
            'Authorization' => "Bearer ".$userAuth['token'],
        ])->postJson('/api/movies/'.$movie->id.'/reviews', $review);

        $res->assertJson(fn(AssertableJson $json) =>
        $json->hasAll(['message','errors'])
        );

        $res->assertJsonValidationErrorFor('rating');
        $res->assertJsonValidationErrorFor('comment');
    }
}
