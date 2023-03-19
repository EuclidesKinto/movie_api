<?php

namespace App\Http\Resources\Api\v1;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::query()->where('id', $this->user_id)->first();
//        dd($user);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'description' => $this->description,
            'published_by' => new UserResource($user),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
