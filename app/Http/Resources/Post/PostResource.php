<?php

namespace App\Http\Resources\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\User\UserResource;

/**
 * @post Post $resource
 */
class PostResource extends JsonResource
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
            'title' => $this->resource->title,
            'excerpt' => $this->resource->excerpt,
            'content' => $this->resource->content,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),

            'user' => new UserResource($this->whenLoaded('user')),
            'comments' => CommentCollection::make($this->whenLoaded('comments')),

            /* 'comments' => CommentResource::collection($this->whenLoaded('comments')) */
        ];
    }
}
