<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\Auth\{
    LoginResponse as AuthLoginResponse,
    LogoutResponse as AuthLogoutResponse,
    RegisterResponse as AuthRegisterResponse,
    ProfileInformationUpdatedResponse as AuthProfileInformationUpdatedResponse
};
use Laravel\Fortify\Contracts\{LoginResponse, LogoutResponse, ProfileInformationUpdatedResponse, RegisterResponse};

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->instance(abstract : RegisterResponse::class, instance : new class implements RegisterResponse {
            public function toResponse($request) {
                if ($request->wantsJson()) {
                    $user = User::where('email', $request->email)->first();
                    return response()->json(data : [
                        "message" => "Compte crée avec succès, vérifiez votre adresse email.",
                        "user" => $user,
                        "token" => $user->createToken($request->email)->plainTextToken
                    ], status : 201);
                }
            }
        });

        $this->app->instance(abstract : LoginResponse::class, instance : new class implements LoginResponse {
            public function toResponse($request) {
                if ($request->wantsJson()) {
                    $user = User::where('email', $request->email)->first();
                    return response()->json(data : [
                        "message" => "Utilisateur authentifié avec succès",
                        "user" => $user,
                        "token" => $user->createToken($request->email)->plainTextToken
                    ], status : 200);
                }
            }
        });

        $this->app->instance(abstract : LogoutResponse::class, instance : new class implements LogoutResponse {
            public function toResponse($request) {
                return response()->json(data : [
                    "message" => "Utilisateur déconnecté avec succès",
                ], status : 200);
            }
        });

        $this->app->instance(abstract : ProfileInformationUpdatedResponse::class, instance : new class implements ProfileInformationUpdatedResponse {
            public function toResponse($request) {
                if ($request->wantsJson()) {
                    return response()->json(data : [
                        "message" => "Informations de profil modifiées avec succès",
                        "user" => $request->user(),
                    ], status : 200);
                }
            }
        });

        /* $this->app->instance(abstract : RegisterResponse::class, instance : new AuthRegisterResponse());
        $this->app->instance(abstract : LoginResponse::class, instance : new AuthLoginResponse());
        $this->app->instance(abstract : LogoutResponse::class, instance : new AuthLogoutResponse());
        $this->app->instance(abstract : ProfileInformationUpdatedResponse::class, instance : new AuthProfileInformationUpdatedResponse()); */

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Fonction permettant d'authenifier l'utilisateur et de le récupérer
        /* Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user &&
                Hash::check($request->password, $user->password)) {
                return $user;
            }
        }); */

    }
}
