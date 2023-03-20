<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviews = Review::query()->where('user_id', $this->id)->get();
        $movie = Movie::query()->where('user_id', $this->id)->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('d-m-Y'),
//            'movie' => $movie,
//            'reviews_count' => $reviews->count(),
//            'reviews' => $reviews,

        ];
    }
}
