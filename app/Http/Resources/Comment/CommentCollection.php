<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{

    public $collects = CommentResource::class;

    /*
        public function __construct(
            public $resource,
            private readonly int $total
        )
        {
            parent::__construct($this->resource);
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
            /* 'total' => $this->total */
        ];
    }
}
