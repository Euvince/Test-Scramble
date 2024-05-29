<?php

namespace App\Responses\Comment;

use Illuminate\Contracts\Support\Responsable;
use App\Http\Resources\Comment\CommentResource;

class SingleCommentResponse implements Responsable
{

    public function __construct(
        private readonly string $message,
        private readonly int $statusCode = 200,
        private readonly CommentResource|array $resource,
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
