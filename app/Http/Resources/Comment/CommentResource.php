<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @comment Comment $resource
 */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* return parent::toArray($request); */
        return [
            'id' => $this->resource->id,
            'content' => $this->resource->content,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),

            'post' => new PostResource($this->whenLoaded('post')),
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
