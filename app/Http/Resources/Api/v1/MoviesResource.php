<?php

namespace App\Http\Resources\Api\v1;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MoviesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::query()->where('id', $this->user_id)->first();
        $reviews = Review::query()->where('movie_id', $this->id)->get();
        $movie_avg = DB::table('reviews')->where('movie_id', $this->id)->avg('rating');
//        dd($user);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'description' => $this->description,
            'published_by' => new UserResource($user),
            'reviews_count' => $reviews->count(),
            'review_avg' => round($movie_avg, 2),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
