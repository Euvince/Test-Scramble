<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @role Role $resource
 */
class RoleResource extends JsonResource
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
            'created_at' => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->resource->updated_at->format('Y-m-d H:i:s'),

            'users' => ResourceCollection::make($this->whenLoaded('users'))
        ];
    }
}
