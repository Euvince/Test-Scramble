<?php

namespace App\Responses\Role;

use App\Http\Resources\Role\RoleResource;
use Illuminate\Contracts\Support\Responsable;

class SingleRoleResponse implements Responsable
{

    public function __construct(
        private readonly string $message,
        private readonly int $statusCode = 200,
        private readonly RoleResource|array $resource,
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
