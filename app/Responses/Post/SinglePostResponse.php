<?php

namespace App\Responses\Post;

use App\Http\Resources\Post\PostResource;
use Illuminate\Contracts\Support\Responsable;

class SinglePostResponse implements Responsable
{

    public function __construct(
        private readonly string $message,
        private readonly int $statusCode = 200,
        private readonly PostResource|array $resource,
    )
    {
    }

    public function toResponse($request) {
        return response()->json(
            status : $this->statusCode,
            data : [
                'status' => $this->statusCode,
                'message' => $this->message,
                'data' =>   $this->resource,
            ]
        );
    }
}
