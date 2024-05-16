<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LogUserAction
{
    public function __construct()
    {
    }

    public static function handle (
        array $credentials
    ) : bool {
        return Auth::attempt($credentials) ? true : false;
    }

}
