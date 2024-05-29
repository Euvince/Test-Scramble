<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Actions\Auth\LogUserAction;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\Auth\StoreUserAction;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Responses\User\SingleUserResponse;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (RegisterRequest $request) : SingleUserResponse {
        $user = StoreUserAction::handle(
            name : $request->name,
            email : $request->email,
            password : Hash::make($request->password)
        );

        return new SingleUserResponse(
            statusCode : 200,
            message : "Utilisateur enrégistré avec succès",
            resource : new UserResource(resource : $user)
        );
    }

    public function login (LoginRequest $request) : SingleUserResponse | JsonResponse {
        if ((new LogUserAction())->handle(credentials : $request->validated())) {
            $user = Auth::user();
            Auth::login($user);

            return new SingleUserResponse(
                statusCode : 200,
                message : "Utilisateur authentifié avec succès",
                token : User::find($user->id)->createToken("auth_user")->plainTextToken,
                resource : new UserResource($user)
            );
        }else {
            return response()->json([
                'message' => "Identifiants invalides",
                'status' => 422,
            ]);
        }
    }

    public function logout (AuthManager $auth) : JsonResponse {
        $auth->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => "Utilisateur déconnecté avec succès",
            'status_code' => 200,
        ]);
    }

}
