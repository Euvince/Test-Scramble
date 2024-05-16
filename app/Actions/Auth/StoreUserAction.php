<?php

namespace App\Actions\Auth;

use App\Models\Role;
use App\Models\User;

class StoreUserAction
{
    public function __construct()
    {
    }

    public static function handle (
        string $name, string $email, string $password
    ) : User {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
        $user->roles()->sync([Role::where('name', 'user')->first()->id]);
        return $user;
    }

}
