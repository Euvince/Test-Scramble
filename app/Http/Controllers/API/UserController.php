<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Actions\Auth\StoreUserAction;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Response\User\SingleUserResponse;
use App\Response\User\UserCollectionResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserController extends Controller
{

    public function __construct(
        private readonly AuthManager $auth,
        /* private readonly RegisterRequest $registerRequest */
    )
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() : UserCollectionResponse | LengthAwarePaginator
    {
        return new UserCollectionResponse(
            statusCode : 200,
            total : User::count(),
            message : "Liste des utilisateurs",
            collection : User::query()->with(['posts', 'comments', 'roles'])->paginate(3)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request) : SingleUserResponse
    {
        $user = StoreUserAction::handle(
            name : $request->name,
            email : $request->email,
            password : Hash::make($request->password)
        );

        return new SingleUserResponse(
            statusCode : 200,
            message : "Utilisateur crée avec succès",
            resource : new UserResource(resource : User::query()->with(['roles'])->where('id', $user->id)->first())
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) : SingleUserResponse
    {
        return new SingleUserResponse(
            statusCode : 200,
            message : "Informations de l'utilisateur",
            resource : new UserResource(resource : User::query()->with(['posts', 'comments', 'roles'])->where('id', $user->id)->first())
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegisterRequest $request, User $user) : SingleUserResponse
    {
        unset($request->validated()['password']);
        $user->update(array_merge($request->validated(), ['password' => Hash::make($request->password)]));
        return new SingleUserResponse(
            statusCode : 200,
            message : "Utilisateur édité avec succès",
            resource : new UserResource(resource : User::query()->with(['posts', 'comments', 'roles'])->where('id', $user->id)->first())
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) : JsonResponse
    {
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => "Utilisateur supprimé avec succès",
        ]);
    }
}
