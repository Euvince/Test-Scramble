<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;
use App\Http\Responses\Role\SingleRoleResponse;
use App\Http\Responses\Role\RoleCollectionResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleController extends Controller
{

    public function __construct(
        private AuthManager $auth,
        /* private RoleRequest $roleRequest */
    )
    {
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(AuthManager $auth) : RoleCollectionResponse | LengthAwarePaginator
    {
        return new RoleCollectionResponse(
            statusCode : 200,
            total : Role::count(),
            message : "Liste des rôles",
            collection : Role::query()->with(['users'])->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request) : SingleRoleResponse
    {
        $role = Role::create($request->validated());

        return new SingleRoleResponse(
            statusCode : 200,
            message : "Rôle crée avec succès",
            resource : new RoleResource(resource : $role)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role) : SingleRoleResponse
    {
        return new SingleRoleResponse(
            statusCode : 200,
            message : "Informations sur le rôle",
            resource : new RoleResource(resource : Role::query()->with(['users'])->where('id', $role->id)->first())
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role) : SingleRoleResponse
    {
        $role->update($request->validated());

        return new SingleRoleResponse(
            statusCode : 200,
            message : "Rôle édité avec succès",
            resource : new RoleResource(resource : Role::query()->with(['users'])->where('id', $role->id)->first())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role) : JsonResponse
    {
        $role->delete();
        return response()->json([
            'status' => 200,
            'message' => "Rôle supprimé avec succès",
        ]);
    }
}
