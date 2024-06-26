<?php

namespace App\Http\Responses\Post;

use App\Http\Resources\Post\PostCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostCollectionResponse implements Responsable
{

    public function __construct(
        private readonly int|null $total = null,
        private readonly string $message,
        private readonly int $statusCode = 200,
        private readonly Collection|LengthAwarePaginator $collection,
    )
    {
    }

    public function toResponse($request) {
        return response()->json(
            status : $this->statusCode,
            data : PostCollection::make(
                resource : $this->collection,
            )->response()->getData(),
        );
    }
}
