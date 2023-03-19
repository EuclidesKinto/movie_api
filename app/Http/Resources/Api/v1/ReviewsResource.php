<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::query()->where('id', $this->user_id)->first();
        $movie = Movie::query()->where('id', $this->movie_id)->first();
        return [
            'name' => $this->name,
            'email' => $user->email,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ];

    }
}
