<?php

namespace App\Responses\User;

use App\Http\Resources\User\UserResource;
use Illuminate\Contracts\Support\Responsable;

class SingleUserResponse implements Responsable
{

    public function __construct(
        private readonly string $message,
        private readonly int $statusCode = 200,
        private readonly UserResource|array $resource,
        private readonly string|null $token = null,
    )
    {
    }

    public function toResponse($request) {
        return response()->json(
            status : $this->statusCode,
            data : [
                'status' => $this->statusCode,
                'message' => $this->message,
                'token' => $this->token,
                'data' =>   $this->resource,
            ]
        );
    }
}
