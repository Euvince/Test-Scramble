<?php

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('lg', static fn (AuthManager $authManager) => dd($authManager->user()));

Route::get('lgn', static fn () => App\Models\User::firstOrFail()->createToken('auth_user')->plainTextToken);
