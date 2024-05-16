<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends ResourceCollection
{

    public $collects = PostResource::class;

    /*
        public function __construct(
            public $resource,
            private readonly int $total,
        )
        {
            parent::_construct($this->resource);
        }
    */

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /* return parent::toArray($request); */

        return [
            'data' => $this->collection,
            /* 'total' => $this->total, */
        ];
    }
}
