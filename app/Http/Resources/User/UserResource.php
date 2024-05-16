<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\RoleResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @user User $resource
 */
class UserResource extends JsonResource
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
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),

            'posts' => PostCollection::make($this->whenLoaded('posts')),
            'comments' => CommentCollection::make($this->whenLoaded('comments')),
            'roles' => RoleCollection::make($this->whenLoaded('roles')),

            /* 'posts' => PostResource::collection($this->whenLoaded('posts')), */
            /* 'comments' => CommentResource::collection($this->whenLoaded('comments')), */
            /* 'roles' => RoleResource::collection($this->whenLoaded('roles')), */
        ];
    }
}
