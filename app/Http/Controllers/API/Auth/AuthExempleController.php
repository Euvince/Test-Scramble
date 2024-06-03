<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\AuthManager;

class AuthExempleController extends Controller
{

    public function register (RegisterRequest $request) : JsonResponse {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ])->roles()->sync([Role::where('name', 'user')->first()->id]);
            return response()->json([
                'message' => "Utilisateur crée avec succès",
                'status_code' => 200,
                'user' => $user
            ]);
        }catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function login (LoginRequest $request) : JsonResponse {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                Auth::login($user);
                return response()->json([
                    'message' => "Connexion éffectuée avec succès",
                    'status_code' => 200,
                    'user' => $user,
                    'token' => User::find($user->id)->createToken("auth_user")->plainTextToken
                ]);
            }else {
                return response()->json([
                    'message' => "Identifiants invalides",
                    'status_code' => 422,
                ]);
            }
        }catch (\Exception $e) {
            return response()->json($e);
        }
    }

    public function logout(AuthManager $auth) : JsonResponse
    {
        try {
            $auth->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => "Utilisateur déconnecté avec succès",
                'status_code' => 200,
            ]);
        }catch (\Exception $e) {
            return response()->json($e);
        }
    }

}
