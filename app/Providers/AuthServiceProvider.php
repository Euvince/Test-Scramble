<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Post' => 'App\Policies\PostPolicy',
        'App\Models\Comment' => 'App\Policies\CommentPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /* Gate::define(ability : "viewApiDocs", callback : fn (User $user) => str($user->email)->endsWith(needles : "@gmail.com"));
        Gate::define(ability : "viewApiDocs", callback : function (User $user) {
            return str($user->email)->endsWith(needles : "@gmail.com");
        }); */
    }
}
