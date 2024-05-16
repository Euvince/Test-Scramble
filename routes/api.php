<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [App\Http\Controllers\API\AuthController::class, 'register'])->name('register');
Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login'])->name('login');
Route::get('logout', [App\Http\Controllers\API\AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource(name : 'roles', controller : App\Http\Controllers\API\RoleController::class);
    Route::apiResource(name : 'users', controller : App\Http\Controllers\API\UserController::class);
    Route::apiResource(name : 'posts', controller : App\Http\Controllers\API\PostController::class);
    Route::apiResource(name : 'posts.comments', controller : App\Http\Controllers\API\CommentController::class);
});

