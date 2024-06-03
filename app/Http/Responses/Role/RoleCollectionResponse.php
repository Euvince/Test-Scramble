<?php

namespace App\Http\Responses\Role;

use App\Http\Resources\Role\RoleCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleCollectionResponse implements Responsable
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
            data : RoleCollection::make(
                resource : $this->collection,
            )->response()->getData(),
        );
    }
}
